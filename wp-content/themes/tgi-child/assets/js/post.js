jQuery(document).ready(function($) {
    $('.mobile-menu-link').click(function(e) {
        e.preventDefault();
        $('#slide-out-widget-area').trigger('click');
        $('#slide-out-widget-area').addClass('open');
        // e.preventDefault();
        $('#spinner-mob').show();
        var url = $('a', this).attr('href');
        var func = url.split('#').pop();
        var data = {
            'action': func,
            'status': $(this).data('status')
        };
        // console.log($(this).data('status'));
        jQuery.post(ajax_post_params.ajax_url, data, function(response) {
            $('#profile-content-container').html(response);
            $('#spinner-mob').hide();
        });
    });
    $(document).on('click', '.profile-menu-tab', function() {
        $('#spinner').show();
        $('.profile-menu-tab').removeClass('active');
        $(this).addClass('active');
        // var func = $(this).attr('id');
        var func = $(this).attr('data-func');
        var data = {
            'action': func,
            'status': $(this).data('status')
        };
        // console.log($(this).data('status'));
        jQuery.post(ajax_post_params.ajax_url, data, function(response) {
            $('#profile-content-container').html(response);
            $('#spinner').hide();
        });
    });
    $('.scroll-to-content').click(function() {
        // $('#profile-content-container')
        $('html, body').animate({
            scrollTop: $("#profile-content-container").offset().top - 80
        }, 2000);
    });
    $(document).on('click', '.profile-menu-tab-item', function() {
        $('#spinner').show();
        $('.profile-menu-tab-item').removeClass('active');
        $(this).addClass('active');
        // var func = $(this).attr('id');
        var func = $(this).attr('data-func');
        var data = {
            'action': func,
            'status': $(this).data('status')
        };
        // console.log($(this).data('status'));
        jQuery.post(ajax_post_params.ajax_url, data, function(response) {
            $('.recipe-item-wrapper').html(response);
            $('#spinner').hide();
            profile_paged = 2;
        });
    });
    $(document).on('keypress', '#profile-recipe-search', function() {
        $('#spinner').show();
        $('.profile-menu-tab-item').removeClass('active');
        var s = $(this).val();
        // var func = $(this).attr('data-func');
        var data = {
            'action': 'user_recipe_tabs',
            's': s
        };
        // console.log($(this).data('status'));
        jQuery.post(ajax_post_params.ajax_url, data, function(response) {
            $('.recipe-item-wrapper').html(response);
            $('#spinner').hide();
            profile_paged = 2;
        });
    });
    $(document).on('keyup', '#profile-recipe-search', function(e) {
        if (e.keyCode == 8 || e.keyCode == 46) { //backspace and delete key
            $('#spinner').show();
            $('.profile-menu-tab-item').removeClass('active');
            // $(this).addClass('active');
            var s = $(this).val();
            // var func = $(this).attr('data-func');
            var data = {
                'action': 'user_recipe_tabs',
                's': s
            };
            // console.log($(this).data('status'));
            jQuery.post(ajax_post_params.ajax_url, data, function(response) {
                $('.recipe-item-wrapper').html(response);
                $('#spinner').hide();
                profile_paged = 2;
            });
        } else { // rest ignore
            e.preventDefault();
        }
    });
    $(document).on('click', '#update-user', function() {
        $('#spinner').show();
        var data = {
            'action': 'save_user_info',
            'first_name': $('#first-name').val(),
            'last_name': $('#last-name').val(),
            'email': $('#email').val(),
            'phone_number': $('#phone_number').val(),
            'occupation': $('#occupation').val(),
            'cooking_skill': $('#cooking_skill').val(),
            'description': $('#description').val()

        };
        jQuery.post(ajax_post_params.ajax_url, data, function(response) {
            $('#profile-content-container').html(response);
            $('#spinner').hide();
        });
    });
    $('body').on('click', '.user-menu-tab', function() {
        $('#spinner').show();
        $('.user-menu-tab').removeClass('active');
        $(this).addClass('active');
        var func = $(this).attr('id');
        var data = {
            'action': func
        };
        jQuery.post(ajax_post_params.ajax_url, data, function(response) {
            if (data.action == "user_edit_ajax") {
                $('#profile-content-container').html(response);
            } else {
                $('.user-account-container').html(response);
            }
            $('#spinner').hide();
        });
    });
    $('body').on('click', '.user-review-tab', function() {
        $('#spinner').show();
        var func = $(this).attr('data-func');
        $('.user-review-tab').removeClass('active');
        $(this).addClass('active');
        // var func = $(this).attr('id');
        var data = {
            'action': 'get_review_items',
            'func': func
        };
        jQuery.post(ajax_post_params.ajax_url, data, function(response) {
            $('#review-items-container').html(response);
            $('#spinner').hide();
        });
    });
    //Save recipe draft
    $('#save-recipe-post, #publish-recipe').click(function() {
        $(this).addClass('saving');
        sort_steps();
        setTimeout(sort_steps(), 1000);
        var recipe_title = $('#recipe-title-value').val();
        var device = $('#device-box-value').val();
        var task = $(this).data('task');
        var el = $(this).attr('id');
        if (!recipe_title) {
            $('.save-btn, #publish-recipe').removeClass('saving');
            if (el == 'publish-recipe') {
                $('.publish-msg-container').html('<span class="spanred">Please input Recipe Title</span>');
            } else {
                $('.message-container').html('<span class="spanred">Please input Recipe Title</span>');
            }
            return false;
        }
        if (!device) {
            // $('.message-container').html('<span class="spanred">Please select device type</span>');
            $('.save-btn, #publish-recipe').removeClass('saving');
            if (el == 'publish-recipe') {
                $('.publish-msg-container').html('<span class="spanred">Please select device type</span>');
            } else {
                $('.message-container').html('<span class="spanred">Please select device type</span>');
            }
            return false;
        }
        var step_number = $('#step-id-container').attr('data-id');
        setTimeout(transfer_data(), 1000);
        // transfer_data();
        // var save_type = $(this).attr('data-save_type');
        var msg = $(this).data('message');
        var action_type = $(this).attr('data-save_type');
        // console.log(action_type);
        var post_id = $('#post-id-container').val();
        var security = $('#security').data('sec');
        var ref = $('#_wp_http_referer').val();
        // console.log(post_id);
        var ing = Array();
        var i = 0;
        var ing_items = $('.ingridients-container').html();
        $('.ingridients-container li').each(function() {
            var name = $('.ing-name', this).text();
            var unit = $('.ing-unit', this).text();
            var amount = $('.ing-amount', this).text();
            var sys_ing = $('.sys_ing', this).text();
            new_ing = {
                name: name,
                unit: unit,
                amount: amount,
                system_ingredient: sys_ing
            }
            ing.push(new_ing);
        });
        j_ing = JSON.stringify(ing);
        var group_steps = Array();
        var urv_ing = Array();
        var source_videos = Array();

        function urv_ing_arr(ing_set) {
            var arr = $(ing_set).filter('li');
            new_urv_arr = Array();
            arr.each(function(index, li) {
                var name = $(li).attr('data-name');
                var unit = $(li).attr('data-unit');
                var amount = $(li).attr('data-amount');
                new_urv_ing = {
                    name: name,
                    unit: unit,
                    amount: amount
                };
                new_urv_arr.push(new_urv_ing);
            });
            return new_urv_arr;
        }
        var total_duration = 0;
        $('#steps-data li.steps-list').each(function() {
            var ing_set = $(this).attr('data-ing');
            urv_ingred = urv_ing_arr(ing_set);
            var step_number = $(this).attr('data-id');
            var step_parent = $(this).attr('data-parent');
            new_source_videos = {
                video: $(this).data('attach_id')
            }
            source_videos.push(new_source_videos);
            var dur = $(this).attr('data-duration');
            total_duration += dur;
            group_step_data = {
                step_number: $(this).attr('data-step_id'),
                sub_step: $(this).attr('data-sub_step'),
                step_parent: $(this).attr('data-parent'),
                name: $(this).attr('data-title'),
                video_landscape: $(this).attr('data-attach_id'),
                video_landscape_start_time: $(this).attr('data-start'),
                video_landscape_end_time: $(this).attr('data-end'),
                duration: dur,
                ingredients: urv_ingred,
                steps: Array({
                    description: $(this).data('desc'),
                    device_setting_reverse: $(this).attr('data-reverse'),
                    device_setting_speed: $(this).attr('data-speed'),
                    device_setting_temperature: $(this).attr('data-temperature'),
                    device_setting_time: $(this).attr('data-time'),
                    device_setting_weight: $(this).attr('data-weight'),
                    device_setting_turbo: $(this).attr('data-turbo'),
                })
            }
            group_steps.push(group_step_data);
            // console.log($(this).data('title'), $(this).data('url'));
        });
        j_ing = JSON.stringify(ing);
        json_group_steps = JSON.stringify(group_steps);
        json_source_videos = JSON.stringify(source_videos);
        var data = {
            'action': 'save_post',
            'nonce': security,
            'current_id': post_id,
            'action_type': action_type,
            'device': device,
            'category': $('#recipe-category-value').val(),
            'title': $('#recipe-title-value').val(),
            'description': $('#recipe-description-value').val(),
            'source_videos': json_source_videos,
            'calories': $('#nutrition-calories').val(),
            'protein': $('#nutrition-protein').val(),
            'carbohydrate': $('#nutrition-carbohydrate').val(),
            'fat': $('#nutrition-fat').val(),
            'complexity': $('#form-difficulty-value').val(),
            'servings': $('#form-serving-value').text(),
            'total_duration': total_duration,
            'ingredients': j_ing,
            'step_groups': json_group_steps,
            // 'steps'          : json_steps_data
        };
        jQuery.post(ajax_post_params.ajax_url, data, function(response) {
            if (response) {
                $('#post-id-container').val(response);
                // $('.message-container').text('Recipe successfully saved!');
                if (el == 'publish-recipe') {
                    if (task == "manage") {
                        $('.publish-msg-container').text(msg);
                    } else {
                        show_success_tab();
                    }
                } else {
                    $('.message-container').text(msg);
                }
                // var url = document.location.href+"?recipe-id="+response;
                // document.location = url;
                somethingChanged = false;
                if (task != "manage") {
                    window.history.replaceState(null, null, "?recipe-id=" + response);
                }
            }
            $('.save-btn, .publish-btn').removeClass('saving');
            setTimeout(function() {
                $('.message-container, .publish-msg-container').text('');
            }, 5000)
        });
    });

    function show_success_tab() {
        $('.status-icon h5').text('Submitted');
        $('.status-icon').addClass('active-status-tab');
        $('#preview-publish').hide();
        $('#chef-endorsement').show();
        $('.proceed-upload').hide();
        $('.tab-col').removeClass('active');
        $('.back-upload').hide();
        $('.save-btn').hide();
        $('.back-default-btn').hide();
    }
    //status tab
    $('.success-tab').on('click', function() {
        // show_success_tab();
        $('.status-icon').addClass('active-status-tab');
        $('#preview-publish').hide();
        $('#chef-endorsement').show();
        $('.proceed-upload').hide();
        $('.tab-col').removeClass('active');
        $('.back-upload').hide();
        $('.save-btn').hide();
        $('.back-default-btn').hide();
        $('#recipe-intro').hide();
        $('#upload-recipe-videos').hide();
        $('#preview-publish').hide();
    });
    //SAVE INGREDIENT
    $('.save-ingredient').click(function() {
        $post_id = $('#post-id-container').val();
        $(this).addClass('saving');
        $(this).prop('disabled', true);
        var ingName = $(this).siblings('.ing-name').text();
        var data = {
            'action': 'save_ingredient',
            'ing_name': ingName,
            'post_id': $post_id
        }
        var me = $(this);
        jQuery.post(ajax_post_params.ajax_url, data, function(response) {
            if (response == "1") {
                me.removeClass('saving');
                me.hide();
                me.siblings('.saved-ingredient').show();
                me.siblings('.sys_ing').text(1);
                $('#save-recipe-post').trigger('click');
            } else {
                me.removeClass('saving');
                $(this).prop('disabled', false);
            }
        });
    })
    //SEARCH Recipe list
    var paged = 2;
    var count_recipe = 1;
    $('.search-btn').on('click', function() {
        // console.log('click');
        var q = $('#search-input').val();
        paged = 2;
        $('#spinner').show();
        var data = {
            'action': 'filter_recipe_list',
            'q': q,
            'count_recipe': 1,
            'category': $('#recipe-cat').val(),
            'difficulty': $('#recipe-difficulty').val(),
            'device': $('#recipe-device').val()
        };
        // console.log($(this).data('status'));
        jQuery.post(ajax_post_params.ajax_url, data, function(response) {
            $('.list-container').html(response);
            $('#spinner').hide();
            $('#more-spinner-container').css('visibility', 'hidden');
            Waypoint.refreshAll();
        });
    });
    //Auto Load
    if ($(location).attr('pathname') == "/recipe-list/" || $(location).attr('pathname') == "/tgi-recipe/recipe-list/") {
        var waypoint = new Waypoint({
            element: $('#more-spinner-container'),
            offset: '100%',
            handler: function(direction) {
                if (direction === 'down') {
                    auto_scroll();
                }
            }
        });
    }

    function auto_scroll() {
        $('#more-spinner-container').css('visibility', 'visible');
        var q = $('#search-input').val();
        var data = {
            'action': 'filter_recipe_list',
            'q': q,
            'paged': paged,
            'count_recipe': count_recipe,
            'category': $('#recipe-cat').val(),
            'difficulty': $('#recipe-difficulty').val(),
            'device': $('#recipe-device').val()
        };
        jQuery.post(ajax_post_params.ajax_url, data, function(response) {
            $('.list-container').append(response);
            $('#more-spinner-container').css('visibility', 'hidden');
            Waypoint.refreshAll();
            paged++;
            count_recipe = 0;
        });
    }
    //FILTER recipe list
    $('.filter-btn').click(function() {
        $('#filter').show();
    });
    $('.filter-input').change(function() {
        var open = false;
        $('#spinner').show();
        var filter = $(this).attr('data-filter');
        var cat = $(this).val();
        if (cat.length > 0) {
            $('#' + filter + ' .filter-text').text(cat);
            $('#' + filter).addClass('active');
            $('.filter-clear').addClass('active');
        } else {
            $('#' + filter + ' .filter-text').text('');
            $('#' + filter).removeClass('active');
            $('.filter-selection-container .filter-item').each(function() {
                if ($(this).hasClass('active')) {
                    open = true;
                }
                if (!open) {
                    $('.filter-clear').removeClass('active');
                }
            });
        }
        var q = $('#search-input').val();
        var data = {
            'action': 'filter_recipe_list',
            'q': q,
            'count_recipe': 1,
            'category': $('#recipe-cat').val(),
            'difficulty': $('#recipe-difficulty').val(),
            'device': $('#recipe-device').val()
        };
        jQuery.post(ajax_post_params.ajax_url, data, function(response) {
            var recipe_count = $(response).filter('.recipe-count');
            $('.found').text($(recipe_count[0]).text());
            $('.list-container').html(response);
            paged = 1;
            $('#spinner').hide();
            Waypoint.refreshAll();
        });
    });
    $('.remove-filter').click(function() {
        var open = false;
        var type = $(this).attr('data-type');
        $('#' + type).val('');
        $('#' + type).change();
        $(this).parents('.filter-item').removeClass('active');
        $('.filter-selection-container .filter-item').each(function() {
            // var div = $(this).attr('')
            if ($(this).hasClass('active')) {
                open = true;
            }
        });
        if (!open) {
            $('.filter-clear').removeClass('active');
        }
    });
    $('.filter-clear').click(function() {
        $(this).removeClass('active');
        $('.filter-selection-container .filter-item').each(function() {
            $(this).val('');
            $(this).removeClass('active');
        });
        $('.filter-input').each(function() {
            $(this).val('');
            $('.search-btn').trigger('click');
        });
    });
    //Author recipe autoload
    // console.log($(location).attr('pathname'));
    if (window.location.href.indexOf("/author/") > -1) {
        // alert("found it");
        // if($(location).attr('pathname') == "/author/" || 
        //  $(location).attr('pathname') == "/tgi-recipe/author/"){
        var waypoint = new Waypoint({
            element: $('#more-spinner-container'),
            offset: '100%',
            handler: function(direction) {
                if (direction === 'down') {
                    author_auto_scroll();
                }
            }
        });
    }
    var author_paged = 2;

    function author_auto_scroll() {
        $('#more-spinner-container').css('visibility', 'visible');
        var q = $('#search-input').val();
        var data = {
            'action': 'get_author_recipe_autoload',
            'q': q,
            'paged': author_paged,
            'author': $('#author-page-wrapper').attr('data-author')
        };
        jQuery.post(ajax_post_params.ajax_url, data, function(response) {
            $('#list-container').append(response);
            $('#more-spinner-container').css('visibility', 'hidden');
            Waypoint.refreshAll();
            author_paged++;
        });
    }
    //PROFILE autoload recipe waypoint embedded in html
    var profile_paged = 2;
    window.profile_auto_scroll = function() {
        $('#more-spinner-container').css('visibility', 'visible');
        var q = $('#search-input').val();
        var data = {
            'action': 'get_profile_recipe',
            'q': q,
            'paged': author_paged
            // 'author': $('#author-page-wrapper').attr('data-author')
        };
        jQuery.post(ajax_post_params.ajax_url, data, function(response) {
            $('#recipe-item-wrapper').append(response);
            $('#more-spinner-container').css('visibility', 'hidden');
            Waypoint.refreshAll();
            profile_paged++;
            // console.log(profile_paged);
        });
    }
    //Author tabs
    $('.author-stat .stat-cols').click(function() {
        $('#spinner').show();
        $('.stat-cols').removeClass('active');
        $(this).addClass('active');
        var data = {
            'action': $(this).attr('data-func'),
            'author': $('#author-page-wrapper').attr('data-author')
        };
        jQuery.post(ajax_post_params.ajax_url, data, function(response) {
            $('#author-list-content').html(response);
            $('#spinner').hide();
        });
    });
    //Delete recipe
    $('#profile-content-container').on('click', '.user-delete', function() {
        if (confirm("Are you sure you want to delete this recipe?")) {
            $(this).addClass('saving');
            var post_id = $(this).data('post_id');
            var data = {
                'post_id': post_id,
                'action': 'delete_post'
            };
            var me = $(this);
            jQuery.post(ajax_post_params.ajax_url, data, function(response) {
                if (response == "1") {
                    me.parents('.user-recipe-item').hide();
                } else {
                    alert('Error deleting this recipe!');
                }
                $('.user-delete').removeClass('saving');
            });
        }
    })
    //Delete recipe
    $('#profile-content-container').on('click', '#hand-pick', function() {
        if (confirm("Confirm Hand Pick")) {
            $(this).addClass('saving');
            var post_id = $(this).data('post_id');
            var data = {
                'post_id': post_id,
                'action': 'hand_pick'
            };
            var me = $(this);
            jQuery.post(ajax_post_params.ajax_url, data, function(response) {
                if (response == "1") {
                    me.addClass('active');
                } else {
                    alert('Error encoutered!');
                }
                $('.hand-pick').removeClass('saving');
            });
        }
    })
    //Sort steps
    window.sort_steps = function() {
        $("#steps-data li.steps-list").sort(sort_li) // sort elements
            .appendTo('#steps-data'); // append again to the list
        // sort function callback
        function sort_li(a, b) {
            return ($(b).data('seq')) < ($(a).data('seq')) ? 1 : -1;
        }
    }
    //Preview and publish
    window.build_preview = function() {
        var title = $('#recipe-title-value').val();
        var t = title.replace(' ', '-')
        var ti = t + '.jpg';
        $('#img-filename').data('title', ti);
        setTimeout(transfer_data(), 1000);
        var summary = $('#recipe-summary');
        summary.html('');
        $('#steps-data li.steps-list').each(function() {
            var dur = $(this).data('duration');
            var parent = $(this).data('parent');
            var sub_step = $(this).data('sub_step');
            var sub = "";
            if (sub_step) {
                sub = "." + sub_step;
            }
            var h = "<li><div class='preview-step-no'>Step " + parent + sub + 
            "</div>" + 
            $(this).data('title') + 
            "<div class='timer-entry'>" + secondsTimeSpanToHMS(dur) + 
            "</div>" + 
            "<div class='edit-entry' data-step_id=" + $(this).data('step') + "></div> " + 
            "<div class='remove-step-entry'>x</div>" + 
            "</li>";
            summary.append(h);
        });
    }
    $('.reject-recipe, .cancel-reject').click(function() {
        $('.admin-overlay-panel').toggle();
    });
    $('#confirm-reject').click(function() {
        $(this).addClass('saving');
        var post_id = $('#post-id-container').val();
        var data = {
            'post_id': post_id,
            'msg': $('#reject-input').val(),
            'action': 'reject_post'
        };
        var me = $(this);
        jQuery.post(ajax_post_params.ajax_url, data, function(response) {
            if (response) {
                me.removeClass('saving');
                $('.admin-overlay-panel').hide();
            }
            if (response == "1") {
                $('.preview-status-text span').text('Rejected');
                $('.publish-msg-container').text('Recipe rejected!')
            } else {
                alert('Error processing request!');
            }
        });
    });
    $('#profile-content-container').on('click', '.rejection-note', function() {
        $(this).siblings('.rejection-text').show();
    });
    $('#profile-content-container').on('click', '.rejection-box-close', function() {
        $('.rejection-text').hide();
    });
    //Transfer data from from to data elements
    window.transfer_data = function() {
        // console.log('transfering data');
        var step_number = $('#step-id-container').attr('data-id');
        if (step_number == '0') {
            // console.log('step id 0');
            return false;
        }
        // console.log('transfer data:', step_number);
        var video_step = $('.video-step-items-container #video-step-' + step_number); //Step buttons
        var step_id = $('.video-step-items-container #video-step-item-' + step_number).attr('data-step_id');
        var sub_step = $('.video-step-items-container #video-step-item-' + step_number).attr('data-sub_step');
        var parent = $('.video-step-items-container #video-step-item-' + step_number).attr('data-parent');
        var attach_id = video_step.attr('data-id');
        var start = video_step.attr('data-start');
        var end = video_step.attr('data-end');
        // console.log('settings', video_step, duration, start, end);
        var duration = $('#urv-duration input').val();
        var step_title = $('#urv-title input').val();
        var step_desc = $('#urv-desc input').val();
        var $setting_reverse = $("#urv-reverse option:selected").val();
        var $setting_speed = $('#urv-speed').val();
        var $setting_temperature = $('#urv-temp input').val();
        var $setting_time = $('#urv-time input').val();
        var $setting_weight = $('#urv-weight input').val();
        var urv_ing_li = $('.urv-ingridients-container').html();
        var step_data = $('#steps-data #steps-step-' + step_number);
        step_data.attr('data-step_id', step_id);
        step_data.attr('data-sub_step', sub_step);
        step_data.attr('data-parent', parent);
        step_data.attr('data-attach_id', attach_id);
        step_data.attr('data-title', step_title);
        step_data.attr('data-desc', step_desc);
        step_data.attr('data-duration', duration);
        step_data.attr('data-start', start);
        step_data.attr('data-end', end);
        step_data.attr('data-reverse', $setting_reverse);
        step_data.attr('data-speed', $setting_speed);
        step_data.attr('data-temperature', $setting_temperature);
        step_data.attr('data-time', $setting_time);
        step_data.attr('data-weight', $setting_weight);
        step_data.attr('data-ing', urv_ing_li);
        // console.log(url, step_title, step_number);
    }
    // Fill form when changing steps / toggle steps
    $(document).on('click', '.video-step-items-container video', function() {
        var current_step = $(this).parents('.video-step-item').attr('data-step_id');
        setTimeout(transfer_data(), 1000);
        var src = $(this).attr('src');
        if(src){
            $('#first-video source').attr('src', src);
            $('#first-video')[0].load();
        }
        var parent = $(this).parents('.video-step-item').attr('data-parent');
        var sub_step_no = $(this).parents('.video-step-item').attr('data-sub_step');
        var sub_step = "";
        if (sub_step_no) {
            var sub_step = '.' + sub_step_no;
            $('#urv-duration').hide();
        } else {
            $('#urv-duration').show();
        }
        var steps_step = $('#steps-data #steps-step-' + current_step);
        $('.video-step-items-container .video-step-item').removeClass('step-active');
        $(this).parents('.video-step-item').addClass('step-active');
        $('#urv-duration input').val(steps_step.attr('data-duration'));
        $('#urv-title input').val(steps_step.attr('data-title'));
        $('#urv-desc input').val(steps_step.attr('data-desc'));
        $('#urv-reverse input').val(steps_step.attr('data-reverse'));
        $('#urv-speed').val(steps_step.attr('data-speed'));
        $('#urv-temp input').val(steps_step.attr('data-temperature'));
        $('#urv-time input').val(steps_step.attr('data-time'));
        $('#urv-weight input').val(steps_step.attr('data-weight'));
        $('#urv-steps span').text('Step ' + parent + sub_step);
        $('.urv-ingridients-container').html(steps_step.attr('data-ing'));
        $('#step-id-container').attr('data-id', current_step);

        var video = $('#first-video')[0];
        if (src) {
            var startTime = hmstoSec($(this).attr('data-start'));
            var endTime = hmstoSec($(this).attr('data-end'));

            function checkTime() {
                if (video.currentTime >= endTime) {
                    video.pause();
                } else {
                    /* call checkTime every 1/10th 
                       second until endTime */
                    setTimeout(checkTime, 100);
                }
            }
            video.currentTime = startTime;
            video.play();
            checkTime();
        }

    });
    
    //Edit Step from preview summary
    $('#recipe-summary').on('click', '.edit-entry', function() {
        var current_step = $(this).attr('data-step_id');
        // $('#step-id-container').attr('data-id', current_step);
        $('.proceed-upload').trigger('click');
        setTimeout(transfer_data(), 1000);
        var src = $('video', this).attr('src');
        // $('#first-video').data('src', src);
        $('#first-video source').attr('src', src);
        $('#first-video')[0].load();
        $('.video-step-item').removeClass('step-active');
        $('#video-step-' + current_step).parents('.video-step-item').addClass('step-active');
        $('#urv-title input').val($('#steps-step-' + current_step).attr('data-title'));
        $('#urv-desc input').val($('#steps-step-' + current_step).attr('data-desc'));
        $('#step-id-container').attr('data-id', current_step);
        $('#urv-steps span').text('Step ' + current_step);
        $('.urv-ingridients-container').html($('#steps-data #steps-step-' + current_step).attr('data-ing'));
    });
    //Reset Form
    window.reset_data_form = function() {
        $('#urv-duration input').val(0);
        $('#urv-title input').val('');
        $('#urv-desc input').val('');
        $('#urv-speed').val('0');
        $('#urv-temp input').val('0');
        $('#urv-time input').val('00:00');
        $('#urv-weight input').val('0');
        $('.urv-ingridients-container').empty();
        // $('#urv-ingridients-container li').each(function(){
        //  $(this).remove();
        // });
    }
    //Create New Step
    $('.video-step-next').click(function() {
        var w = $('.video-step-items-container').width();
        $('.video-step-items-container').css('width', w + 119);
        check_if_overflown();
        transfer_data();
        var step_count = 1;
        $('#steps-data li.steps-list').each(function(i, e) {
            if ($(this).data('parent') == $(this).data('step_id')) {
                step_count++;
            }
        });
        // var step_number = $('#steps-data li.steps-list').length + 1;
        // var main_count =  $('#step-id-container').attr('data-main_count');
        // var step =  $('#step-id-container').attr('data-step_count');
        // console.log('steps', step_number);
        // var step_number = Number(step) + 1;
        $('#urv-duration').show();
        $('#step-id-container').attr('data-step_count', step_count);
        $('#step-id-container').attr('data-id', step_count);
        $('.video-step-items-container .video-step-item').removeClass('step-active');
        $('#steps-data-template li.steps-list').attr('data-parent', step_count);
        $('#steps-data-template li.steps-list').attr('data-step_id', step_count);
        $('#steps-data-template li.steps-list').attr('id', 'steps-step-' + step_count);
        $('#steps-data-template li.steps-list').attr('data-seq', $('#steps-data li.steps-list').length);
        $('#step-template-container .video-step-item').attr('data-parent', step_count);
        $('#step-template-container .video-step-item').attr('data-step_id', step_count);
        $('#step-template-container .video-step-number').html('Step ' + step_count);
        $('#step-template-container .video-step-duration').attr('id', 'duration-' + step_count);
        $('#step-template-container .video-step-duration').attr('data-seq', $('#steps-data li.steps-list').length);
        $('#step-template-container video').attr('id', 'video-step-' + step_count);
        $('#steps-data').append($('#steps-data-template').html());
        $('.video-step-items-container').append($('#step-template-container').html());
        $('#step-template-container video').attr('id', '');
        $('#steps-data-template li.steps-list').attr('id', '');
        $('#urv-steps span').text('Step ' + step_count);
        // $('#step-id-container').attr('data-main_count', Number(main_count) + 1);
        $('.video-step-items-container .video-step-item').each(function(i, e) {
            // console.log(i);
            $(this).attr('data-seq', i + 1);
            var step_id = $(this).attr('data-step_id');
            var step_item = $('#steps-data #steps-step-' + step_id).attr('data-seq', i + 1);
        });
        somethingChanged = true;
        reset_data_form();
    });
    //CREATE SUB STEP
    $('.video-sub-step-next').click(function() {
        var w = $('.video-step-items-container').width();
        $('.video-step-items-container').css('width', w + 115);
        check_if_overflown();
        // var step =  $('#step-id-container').attr('data-id');
        // var step_count =  $('#step-id-container').attr('data-sub_count');
        var parent = $('.video-step-items-container .video-step-item.step-active').data('parent');
        $('.video-step-items-container .video-step-item.step-active').addClass('parent');
        // console.log(parent);
        // var step_number = Number(step) + 1;
        // var step_count_number = Number(step_count) + 1;
        // $('#step-id-container').attr('data-sub_count', step_count_number);
        if (!parent) {
            alert('Please select main step');
            return false;
        }
        $('#urv-duration').hide();
        var sub_count = 0;
        $('#steps-data li.steps-list').each(function(i, e) {
            if ($(this).attr('data-parent') == parent) {
                sub_count++;
            }
        });
        var step_id = parent + '.' + sub_count;
        var step_dash_id = parent + '-' + sub_count;
        // var sub_step_count = Number(sub_count) + 1;
        $('#steps-data-template li.steps-list').attr('id', 'steps-step-' + step_dash_id);
        $('#steps-data-template li.steps-list').attr('data-parent', parent);
        $('#steps-data-template li.steps-list').attr('data-step_id', step_dash_id);
        $('#steps-data-template li.steps-list').attr('data-sub_step', sub_count);
        $('#steps-data-template li.steps-list').attr('data-sub_step', sub_count);
        // $('#step-template-container .video-step-item').removeClass('step-active');
        $('#step-template-container .video-step-item').attr('id', 'video-step-item-' + step_dash_id);
        $('#step-template-container .video-step-number').html('Step ' + step_id);
        $('#step-template-container .video-step-duration').attr('id', 'duration-' + step_dash_id);
        $('#step-template-container #remove-step').removeClass('remove-step');
        $('#step-template-container #remove-step').addClass('remove-sub-step');
        $('#step-template-container .video-step-item').attr('data-step_id', step_dash_id);
        $('#step-template-container .video-step-item').attr('data-sub_step', sub_count);
        $('#step-template-container .video-step-item').attr('data-parent', parent);
        $('#step-template-container video').attr('id', 'video-step-' + step_dash_id);
        $('#steps-data').append($('#steps-data-template').html());
        // var parent_step = $('#step-id-container').attr('data-id');
        var h = $('#step-template-container').html();
        // insert_step(h);
        $(h).insertAfter($('.video-step-items-container .video-step-item.step-active'));
        // $('.video-step-items-container .video-step-item').removeClass('step-active');
        $('.video-step-items-container .parent').removeClass('step-active');
        // $('.video-step-items-container #video-step-item-'+ step_id).addClass('step-active');
        $('#step-id-container').attr('data-id', step_dash_id);
        // $('.video-step-items-container .sub-step').removeClass('step-active'); 
        // $('.video-step-items-container .video-step-wrapper').parents('.video-step-item').remove('step-active');
        // $('.video-sub-step-next').click(function(){
        //  $('.video-step-item').removeClass('step-active'); 
        // });
        // remove_class(step_number);
        // $('#step-template-container video').attr('id', '');
        // $('#steps-data-template li.steps-list').attr('id', '');
        $('#urv-steps span').text('Step ' + step_id);
        $('.video-step-items-container .video-step-item').each(function(i, e) {
            // console.log(i);
            $(this).attr('data-seq', i + 1);
            var step_id = $(this).attr('data-step_id');
            var step_item = $('#steps-data #steps-step-' + step_id).attr('data-seq', i + 1);
        });
        somethingChanged = true;
        reset_data_form();
        sort_steps();
    });
    //Check if Steps Overflown
    scrollPosition = 0

    function check_if_overflown() {
        var container = $('.video-step-items-container').width();
        var parent = $('.step-row-wrapper').width();
        // console.log('container:', container, 'parent:', parent);
        if (container > parent) {
            $('.step-row-wrapper').animate({
                scrollLeft: scrollPosition + 119
            }, 400);
        }
        scrollPosition += 119;
    }
    //Video step arrow left and right
    $('.video-step-arrow-left').click(function() {
        if (scrollPosition < 119) {
            $('.step-row-wrapper').animate({
                scrollLeft: 0
            }, 400);
            scrollPosition = 0;
        } else {
            $('.step-row-wrapper').animate({
                scrollLeft: scrollPosition - 115
            }, 400);
            scrollPosition -= 115;
        }
    });
    $('.video-step-arrow-right').click(function() {
        $('.step-row-wrapper').animate({
            scrollLeft: scrollPosition + 115
        }, 400);
        scrollPosition += 115;
    });
    //Follow
    $('#follow-btn').on('click', function() {
        $(this).addClass('saving');
        var data = {
            'action': 'follow',
            'follow_id': $(this).data('id')
        };
        jQuery.post(ajax_post_params.ajax_url, data, function(response) {
            if (response == 1) {
                $('#follow-btn').removeClass('saving');
                $('#follow-btn').addClass('inactive');
                $('#followed-btn').addClass('followed-btn-active');
            } else {
                $('#follow-btn').addClass('inactive');
                $('#followed-btn').addClass('followed-btn-active');
            }
        });
    });
    $('body').on('click', '.profile-follow-btn', function() {
        $(this).addClass('saving');
        var data = {
            'action': 'follow',
            'follow_id': $(this).data('userid'),
            'data_id': $(this).data('id')
        };
        var me = $(this);
        jQuery.post(ajax_post_params.ajax_url, data, function(response) {
            if (response == 1) {
                me.removeClass('saving');
                me.removeClass('follow-btn');
                me.addClass('following-btn');
                me.text('Following');
            }
        });
    });
    $('body').on('click', '.unfollow-btn', function() {
        $(this).addClass('saving');
        var data = {
            'action': 'unfollow',
            'follow_id': $(this).data('userid')
        };
        jQuery.post(ajax_post_params.ajax_url, data, function(response) {
            if (response == 1) {
                $('#follow-btn-' + data.follow_id).hide();
            } else {
                $('.follow-btn').removeClass('saving');
            }
        });
    });
    //Follow
    $('#like-btn').on('click', function() {
        $('span', this).text('Liked');
        var data = {
            'action': 'like',
            'recipe_id': $(this).data('id')
        };
        var me = $(this);
        jQuery.post(ajax_post_params.ajax_url, data, function(response) {
            if (response == 1) {
                me.hide();
                $('#liked-btn').show();
            } else {
                $('span', me).text('Like');
            }
        });
    });
    //Recipe Ingredients Auto load
    $('#ing-name').keyup(function(e) {
        if (e.keyCode == 8 || e.keyCode == 46) { //backspace and delete key
            $('.autoload-spinner').show();
            $('.ing-name-selection').show();
            var txt = $(this).val();
            if (txt.length == 0) {
                $('.ing-name-selection').hide();
            } else {
                var data = {
                    'action': 'autoload_ing',
                    'txt': txt
                };
                get_auto_text(data);
            }
        } else { // rest ignore
            e.preventDefault();
        }
    });
    $('#ing-name').on('keypress', function() {
        var txt = $(this).val();
        //if(txt.length > 1){
        $('.autoload-spinner').show();
        $('.ing-name-selection').show();
        var data = {
            'action': 'autoload_ing',
            'txt': txt
        };
        get_auto_text(data);
        //}
    });

    function get_auto_text(data) {
        jQuery.post(ajax_post_params.ajax_url, data, function(response) {
            $('.ing-name-selection-text').html(response);
            $('.autoload-spinner').hide();
        });
    }
    $('.ing-name-selection-text').on('click', '.ing-text', function() {
        var txt = $(this).text();
        $('#ing-name').val(txt);
        $('.ing-name-selection').hide();
        $('#sys_ing').val(1);
    });
    $("body").click(function(e) {
        if (e.target.className !== "ing-name-selection") {
            $(".ing-name-selection").hide();
        }
    });
    //Recipe Ingredients Auto load upload video
    $('#urv-ing-name').keyup(function(e) {
        if (e.keyCode == 8 || e.keyCode == 46) { //backspace and delete key
            $('.urv-autoload-spinner').show();
            $('.urv-ing-name-selection').show();
            var txt = $(this).val();
            if (txt.length == 0) {
                $('.urv-ing-name-selection').hide();
            } else {
                var data = {
                    'action': 'autoload_ing',
                    'txt': txt
                };
                urv_get_auto_text(data);
            }
        } else { // rest ignore
            e.preventDefault();
        }
    });
    $('#urv-ing-name').on('keypress', function() {
        var txt = $(this).val();
        if (txt.length > 1) {
            $('.urv-autoload-spinner').show();
            $('.urv-ing-name-selection').show();
            var data = {
                'action': 'autoload_ing',
                'txt': txt
            };
            urv_get_auto_text(data);
        }
    });

    function urv_get_auto_text(data) {
        jQuery.post(ajax_post_params.ajax_url, data, function(response) {
            $('.urv-ing-name-selection-text').html(response);
            $('.urv-autoload-spinner').hide();
        });
    }
    $('.urv-ing-name-selection-text').on('click', '.ing-text', function() {
        var txt = $(this).text();
        $('#urv-ing-name').val(txt);
        $('.urv-ing-name-selection').hide();
    });
    $("body").click(function(e) {
        if (e.target.className !== "urv-ing-name-selection") {
            $(".urv-ing-name-selection").hide();
        }
    });
});