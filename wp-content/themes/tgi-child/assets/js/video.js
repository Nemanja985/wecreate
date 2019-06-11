jQuery(document).ready(function($) {
    //Video upload
    $('.video-upload-btn').click(function() {
        $('.myprogress').css('width', '0');
        $('#video-step-input').val('');
        $('.video-upload-form').show();
    });
    $('.x-close').click(function() {
        $('.video-upload-form').hide();
    });
    $('.save-video-btn').click(function(e) {
        $('.myprogress').css('width', '0');
        $('.save-video-btn').attr('disabled', 'disabled');
        var post_id = $('#post-id-container').val();
        if (!post_id) {
            alert('Save first page first.');
            return false;
        }
        var form_data = new FormData();
        var file_data = jQuery('#video-step-input').prop('files')[0];
        form_data.append('action', 'upload_video');
        form_data.append('file', file_data);
        form_data.append('post_id', post_id);
        form_data.append('security', $('#security').val());
        jQuery.ajax({
            url: ajax_post_params.ajax_url, // there on the admin side, do-it-yourself on the front-end
            type: 'post',
            contentType: false,
            processData: false,
            data: form_data,
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        percentComplete = parseInt(percentComplete * 100);
                        $('.myprogress').text(percentComplete + '%');
                        $('.myprogress').css('width', percentComplete + '%');
                    }
                }, false);
                return xhr;
            },
            success: function(data) {
                var response = JSON.parse(data);
                if (response.error == 'error') {
                    $('.video-upload-form').hide();
                    alert("There's an error uploading video");
                } else {
                    $('.save-video-btn').removeAttr('disabled');
                    $('.video-upload-form').hide();
                    $('.video-selection-wrapper .video-selection').removeClass('active');
                    $('#vid-selection-template video source').attr('src', response.url);
                    $('#vid-selection-template video').attr('id', 'video-' + response.id);
                    $('#vid-selection-template video').attr('data-id', response.id);
                    $('#vid-selection-template .remove-video').attr('data-id', response.id);
                    $('.video-selection-scroller').prepend($('#vid-selection-template').html());
                    var w = $('.video-selection-scroller').width() + 68;
                    $('.video-selection-scroller').css('width', w);
                    // $('.video-thumbnail')[0].load(); 
                    $('#video-' + response.id)[0].load();
                    $('#first-video').show();
                    $('.upload-video-icon').hide();
                    $('#first-video source').attr('src', response.url);
                    $('#first-video')[0].load();
                    setTimeout(function() {
                        $('.video-selection.active .video-thumbnail').trigger('click');
                    }, 1000)
                }
                somethingChanged = true;
            }
        });
    });
    //Select Video
    $('.video-selection-wrapper').on('click', '.video-thumbnail', function() {
        $('.seek-right, .seek-left').css('width', '100%');
        $("#seek-ui-left").animate({
            top: "0px",
            left: "0px"
        });
        $("#seek-ui-right").animate({
            top: "0px",
            left: "0px"
        });
        var div = $('#trim-spinner')
        $('#trim-spinner').show();
        div.animate({
            height: '30px',
            opacity: '0.6'
        }, "slow");
        div.animate({
            width: '320px',
            opacity: '1'
        }, "slow");
        div.animate({
            height: '30px',
            opacity: '0.6'
        }, "slow");
        div.animate({
            width: '300px',
            opacity: '1'
        }, "slow");
        $('.video-selection-wrapper .video-selection').removeClass('active');
        $('#video-to-trim').html('');
        $(this).parent().addClass('active');
        var src = $('source', this).attr('src');
        var attached_id = $(this).attr('data-id');
        $('.video-step')[0].load();
        $('#first-video').data('src', src);
        $('#first-video source').attr('src', src);
        $('#first-video')[0].load();
        var videoPlayer = $(this)[0];
        var duration = Math.round(videoPlayer.duration);
        $('#video-to-trim').attr('data-duration', duration);
        $('#video-to-trim').attr('data-end', duration);
        $('#video-to-trim').attr('data-id', attached_id);
        $('#video-to-trim').attr('data-src', src);
        var wrapper = $('.video-selection-wrapper').width();
        var division = duration / 14;
        var offset_no = 0;
        var offset = [];
        for (var i = 0; i <= duration; i++) {
            if (offset_no < i) {
                offset.push(i);
                offset_no += division;
            }
        }
        var trimmerWidth = (duration + 1) * 51;
        var w = trimmerWidth + "px";
        $('#video-to-trim').css('width', w);
        extractVideoFrames(src, offset, 50).then(function(data) {
            $.each(data, function(index, value) {
                var img = '<div class="frames" data-frame=""><img src="' + data[index].imgUrl + '"></div>';
                $('#trim-spinner').hide();
                $('#video-to-trim').append(img);
            });
        });
        $('#trim-start, #trim-end').prop('disabled', false);
        $('#trim-start').val('00:00:00');
        $('#trim-end').val(secondsTimeSpanToHMS(duration));
    });
    //Delete video
    $('.video-selection-wrapper').on('click', '.remove-video', function() {
        if (confirm("Are you sure you want to delete this video?")) {
            $(this).parent().hide();
            var post_id = $(this).data('id');
            var author = $(this).data('author');
            var data = {
                'post_id': post_id,
                'author': author,
                'action': 'delete_video'
            };
            var me = $(this);
            jQuery.post(video_admin_params.video_ajax_url, data, function(response) {
                if (response == 0) {
                    me.parent().show();
                    alert('Cannot delete this video');
                } else {
                    var w = $('.video-selection-scroller').width() - 68;
                    $('.video-selection-scroller').css('width', w);
                }
            });
        }
    });
    //upload image
    var imgSrc = $('.image-editor').data('img');
    $('.image-editor').cropit({
        exportZoom: 1.25,
        imageBackground: true,
        imageBackgroundBorderWidth: 150,
        width: 288,
        height: 288,
        smallImage: 'allow',
        imageState: {
            src: imgSrc,
        },
    });
    $('.export').click(function() {
        var post_id = $('#post-id-container').val();
        if (!post_id) {
            $('.upload-message').html('<span class="warn-msg">Error: Save first page first!</span>');
            return false;
        }
        var origImage = $('.cropit-image-input').prop('files')[0]; //orig image
        var imageData = $('.image-editor').cropit('export', {
            type: 'image/jpeg',
            quality: .9,
            originalSize: true
        });
        if (!imageData) {
            $('.upload-message').html('<span class="warn-msg">Error: Please select photo!</span>');
            return false;
        }
        fn = $('#img-filename').data('title');
        $(this).addClass('saving');
        var me = $(this);
        var form_data = new FormData();
        // var file_data = jQuery('#cropit-hidden-image').prop('files')[0];
        form_data.append('action', 'upload_image');
        form_data.append('img', imageData);
        form_data.append('fname', fn);
        form_data.append('orig_image', origImage); //orig image
        form_data.append('post_id', post_id);
        form_data.append('security', $('#security').val());
        jQuery.ajax({
            url: ajax_post_params.ajax_url, // there on the admin side, do-it-yourself on the front-end
            type: 'post',
            contentType: false,
            processData: false,
            data: form_data,
            success: function(data) {
                var response = JSON.parse(data);
                me.removeClass('saving');
                $('.upload-message').html('<span class="success-msg">' + response.msg + '</span>');
            }
        });
    });
    //Scroll video selection
    var scrollPos = 0;
    $('.video-upload-scroll-right').click(function() {
        $('.video-selection-wrapper').animate({
            scrollLeft: scrollPos + 71
        }, 500);
        scrollPos += 71;
    });
    $('.video-upload-scroll-left').click(function() {
        var sc = $('.video-selection-wrapper').scrollLeft();
        if (sc > 0) {
            $('.video-selection-wrapper').animate({
                scrollLeft: scrollPos - 71
            }, 500);
            scrollPos -= 71;
        } else {
            scrollPos = 0;
        }
    });
    $("#seek-ui-left").draggable({
        axis: "x",
        containment: "parent",
        stop: function() {
            if ($('#video-to-trim').attr('data-id')) {
                var dragLeft = parseInt($(this).css('left'));
                var origWidth = $('.video-editor-wrapper').width();
                var duration = $('#video-to-trim').attr('data-duration');
                var trimmed = (dragLeft) / (origWidth / duration);
                var start = Math.round(trimmed);
                $('#trim-start').val(secondsTimeSpanToHMS(start));
                $('#video-to-trim').attr('data-start', start);
                var source = $('#first-video').data('src');
                var src = source + "#t=" + start;
                $('#video-to-trim').attr('data-src', src);
                $('#first-video source').attr('src', src);
                $('#first-video')[0].load();
                $('.seek-right').css('width', '100%');
                var rightWidth = $('.seek-right').width();
                $('.seek-right').width((rightWidth - dragLeft) - 50);
                return true;
            }
        }
    });
    $("#seek-ui-right").draggable({
        axis: "x",
        containment: "parent",
        stop: function() {
            if ($('#video-to-trim').attr('data-id')) {
                var dragRight = parseInt($(this).css('right'));
                var origWidth = $('.video-editor-wrapper').width();
                var duration = $('#video-to-trim').attr('data-duration');
                var trimmed = (dragRight) / (origWidth / duration);
                var end = Math.round(trimmed);
                var endTime = duration - end;
                $('#video-to-trim').attr('data-end', endTime);
                $('#trim-end').val(secondsTimeSpanToHMS(endTime));
                $('.seek-left').css('width', '100%');
                var leftWidth = $('.seek-left').width();
                $('.seek-left').width((leftWidth - dragRight) - 50);
                return true;
            }
        }
    });
    //Trim Input 
    $('#trim-start').change(function() {
        var start = $(this).val();
        var sec = hmstoSec(start);
        var origWidth = $('.video-editor-wrapper').width();
        var duration = $('#video-to-trim').attr('data-duration');
        // var trimmed = (sec) / (origWidth / duration);
        var trimmed = (origWidth / duration) * (sec);
        var drag = Math.round(trimmed);
        $('#video-to-trim').attr('data-start', sec);
        $('#seek-ui-left').animate({
            left: drag+"px"
        });

        var source = $('#first-video').data('src');
        var src = source + "#t=" + sec;
        $('#video-to-trim').attr('data-src', src);
        $('#first-video source').attr('src', src);
        $('#first-video')[0].load();

    });
    $('#trim-end').change(function() {
        var origWidth = $('.video-editor-wrapper').width();
        var duration = $('#video-to-trim').attr('data-duration');
        var end = $(this).val(); // your input string
        var secs = hmstoSec(end);
        var sec = duration - secs;
        
        // var trimmed = (sec) / (origWidth / duration);
        var trimmed = (origWidth / duration) * (sec);
        var drag = Math.round(trimmed);
        $('#video-to-trim').attr('data-end', secs);
        $('#seek-ui-right').animate({
            left: -drag+"px"
        });

    });
    window.hmstoSec = function(time) {
        var a = time.split(':'); // split it at the colons
        var sec = (+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]);
        return sec
    }
    $('.trim-btn').click(function() {
        var attached_id = $('#video-to-trim').attr('data-id');
        if (!attached_id) {
            $('.trim-msg').text('Please select video to trim.');
            return false
        }
        var vid_step = $('.video-step-items-container .video-step-item.step-active').attr('data-step_id');
        var vid_id = $('.video-step-items-container .video-step-item.step-active video').attr('id');
        var start = $('#video-to-trim').attr('data-start');
        var end = $('#video-to-trim').attr('data-end');
        var duration = $('#video-to-trim').attr('data-duration');
        var endTime = duration - end;
        var newDuration = (Number(end) - Number(start));
        var hmsDuration = secondsTimeSpanToHMS(newDuration);
        var src = $('#video-to-trim').attr('data-src');
        $('.video-step-item #duration-' + vid_step).text(hmsDuration);
        $('.video-step-wrapper #' + vid_id).attr('data-start', secondsTimeSpanToHMS(start));
        $('.video-step-wrapper #' + vid_id).attr('data-end', secondsTimeSpanToHMS(end));
        $('.video-step-wrapper #' + vid_id).attr('data-id', attached_id);
        $('.video-step-wrapper #' + vid_id).attr('src', src);
        $('.video-step-wrapper #' + vid_id)[0].load();
        $('.trim-msg').text('Video trimmed successfully!');
        setTimeout(function() {
            $('.trim-msg').text('');
        }, 5000);
    });
    //RESET TRIM
    $('.reset-btn').click(function() {
        $("#seek-ui-left").animate({
            top: "0px",
            left: "0px"
        });
        $("#seek-ui-right").animate({
            top: "0px",
            left: "0px"
        });
        $('.video-editor-wrapper').animate({
            scrollLeft: 0
        }, 500);
        $('#trim-start').val('00:00:00');
        $('#trim-end').val(secondsTimeSpanToHMS($('#video-to-trim').attr('data-duration')));
    });
    window.secondsTimeSpanToHMS = function(s) {
        var h = Math.floor(s / 3600); //Get whole hours
        s -= h * 3600;
        var m = Math.floor(s / 60); //Get remaining minutes
        s -= m * 60;
        return h + ":" + (m < 10 ? '0' + m : m) + ":" + (s < 10 ? '0' + s : s); //zero padding on minutes and seconds
    }
});