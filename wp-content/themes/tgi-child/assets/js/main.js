jQuery(document).ready(function ($) {

  //HOME
  $('.home-video-play-icon').click(function(){
    $('#main-video')[0].play();
    $(this).hide();
  });
  $('.main-video-container video').click(function(){
    $('#main-video')[0].pause();
    $('.home-video-play-icon').show();
  });
  $('.video-header-selection .video-item').click(function(){
    $('.video-item').removeClass('active');
    $(this).addClass('active');
    $('#main-video').attr('src', $(this).attr('data-src'));
    $('#main-video')[0].play();
    $('.home-video-play-icon').hide();
    $('.video-title h2').text($(this).attr('data-title'));
  });
  $('#latest-recipe-slider').slick({
    dots: true,
    infinite: true,
    speed: 300,
    slidesToShow: 4,
    slidesToScroll: 1,
    dots: false,
    variableWidth: true
    
  });
  $('.latest-prev').click(function(){
    $('.slick-prev').trigger('click');
  });
  $('.latest-next').click(function(){
    $('.slick-next').trigger('click');
  });
  //End hompage

  //HAND PICKED
  $('#hand-picked-slider').slick({
    dots: false,
    infinite: true,
    speed: 300,
    slidesToShow: 6,
    // slidesToScroll: 1,
    dots: false,
    variableWidth: true,
    arrows: false,
    swipeToSlide: true
  });

  //Tab col
  $('.tab-col').click(function(){
    $('.tab-col').removeClass('active');
    $(this).addClass('active');
    var tab_id = $('.content-id', this).data('id');
    $('.recipe-tab-content').hide();
    $('#'+tab_id).show();

    $('html, body').animate({scrollTop: $('.main-content').offset().top -200 }, 'slow');
  });
  //Create Recipe - Step 1
  $( "#sortable" ).sortable({helper: "clone",
     start: function(e, ui)
     {
      $(ui.helper).addClass("ui-draggable-helper");
     }
  });
  $( "#sorting").sortable({helper: "clone",
     start: function(e, ui)
     {
      $(ui.helper).addClass("ui-draggable-helper");
     }
  });
    $('#save-entry').click(function(){
      var amount = $('#ing-amount').val();
      var unit = $('#ing-unit').val();
      var name = $('#ing-name').val();
      var sys_ing = $('#sys_ing').val();

      if(!amount || !unit || !name){
        alert('Please input amount, unit and ingridient name');
        return;
      }
      var h = "<li class='saved-entry' data-amount='"+ amount +"' data-unit='"+ unit +"' data-name='"+ name +"'>" + 
        "<span class='ing-amount' data-amount='"+ amount +"'>" + amount + "</span>" +
        "<span class='ing-unit' data-amount='"+ unit +"'>" + unit + "</span>" +
        "<span class='ing-name' data-name='"+ name +"'>" + name + "</span>" +
        "<span class='sys_ing hidden' data-local='"+ sys_ing +"'>" + sys_ing + "</span>" +
        "<div class='remove-entry'>x</div></li>";
      $('.ingridients-container').prepend(h);
      somethingChanged = true;
      reset_entry();
    });
    $('.ingridients-container').on('click', '.remove-entry', function(e){
      e.preventDefault();
      $(this).parent().remove();
    });
    function reset_entry(){
      $('#ing-amount').val("");
      $('#ing-unit').val("");
      $('#ing-name').val("");
      $('#sys_ing').val(0);
    }
    $('#urv-save-entry').click(function(){
      var amount = $('#urv-ing-amount').val();
      var unit = $('#urv-ing-unit').val();
      var name = $('#urv-ing-name').val();
      if(!amount || !unit || !name){
        alert('Please input amount, unit and ingridient name');
        return;
      }
      var h = "<li class='urv-saved-entry' data-amount='"+ amount +"' data-unit='"+ unit +"' data-name='"+ name +"'>" + 
        "<span class='urv-ing-amount'>" + amount + "</span>" +
        "<span class='urv-ing-unit'>" + unit + "</span>" +
        "<span class='urv-ing-name'>" + name + "</span>" +
        "<div class='urv-remove-entry'>x</div></li>";
      $('.urv-ingridients-container').prepend(h);
      urv_reset_entry();
      somethingChanged = true;
    });
    function urv_reset_entry(){
      $('#urv-ing-amount').val("");
      $('#urv-ing-unit').val("");
      $('#urv-ing-name').val("");
    }
    $('.urv-ingridients-container').on('click', '.urv-remove-entry', function(e){
      $(this).parent().remove();
    });
    $('.remove-step-entry').click(function(e){
      e.preventDefault();
      $(this).parent().remove();
    });
    $('.category-select').click(function(e){
      e.preventDefault();
      $('.category-options', this).toggle();
    });
    $('.cat-option-title').click(function(){
      $data = $(this).data('id');
      $('#' + $data).val("");
      $(this).parent().siblings('span').text($(this).text());
    });
    $('.cat-option').click(function(){
      $data = $(this).data('id');
      $('#' + $data).val($(this).text());
      $(this).parent().siblings('span').text($(this).text());
    });
 
  $('.serve-plus').click(function(){
    var $serving = $('.serve-amount').text();
    $('.serve-amount').text(Number($serving) +1);
  });
  $('.serve-minus').click(function(){
    var $serving = $('.serve-amount').text();
    if($serving == 0){
      $('.serve-amount').text(0);
    }else{
      $('.serve-amount').text(Number($serving) -1);
    }
  });
  //FRONT END
  $('.serving-minus, .serving-plus').click(function(){
    var num = Number($(this).data('add'));
    var old_num = Number($('.serving-amount').text());
    var new_num = (old_num) + (num);
    if(new_num < 1){
      return;
    }
    $('.serving-amount').text(new_num);
    $('.recipe-ingredients-row, li').each(function(){
      var ing_amt = Number($('#ing-qty', this).text());
      var new_amount = (ing_amt/old_num) * (new_num);
      $('#ing-qty', this).text(Math.round(new_amount));
    })
    
  });
  $('#recipe-details-step-item li').each(function(){
    var parent = $(this).data('parent');
    var step =  $(this).data('step');
    if( parent == step){
      var count = count_sub_step(parent);
      $('.step-count', this).text(count + ' Step(s)');
    }
    
  });
  function count_sub_step(step_id){
    var count = 0;
    $('#recipe-details-step-item li').each(function(){
      var parent = $(this).data('parent');
      
      if( parent == step_id){
        count++;
      }
      
    });
    return count;
  }

  $('.difficulty-btn').click(function(){
    $('.difficulty-btn').removeClass('active');
    $data = $('span', this).text();
    $('#form-difficulty-value').val($data);
    $(this).addClass('active');
  }); 

  //Go to next page
  $('.recipe-intro-btn').click(function(){
    $('.status-icon').removeClass('active-status-tab')
    $('.proceed-upload').show();
    $('.proceed-summary').hide();
    $('.back-upload').hide();
    $('.back-default-btn').hide();
    $('.save-btn').show();
  });
  $('.upload-recipe-videos-btn').click(function(){
    $('.proceed-upload').hide();
    $('.status-icon').removeClass('active-status-tab')
    get_recipe_from_main()
    $('.proceed-summary').show();
    $('.back-upload').hide();
    $('.back-default-btn').show();
    $('.save-btn').show();
  });
   $('.preview-publish-btn').click(function(){
    build_preview();
    $('.status-icon').removeClass('active-status-tab')
    $('.proceed-upload').hide();
    $('.proceed-summary').hide();
    $('.back-upload').show();
    $('.back-default-btn').hide();
    $('.save-btn').show();
  });
  //   $('.chef-btn').click(function(){
  //   $('.proceed-upload').hide();
  //   $('.proceed-summary').hide();
  //   $('.back-upload').hide();
  //   $('.save-btn').hide();
  //   $('.back-default-btn').hide();
  // });
  $('.proceed-upload').click(function(){
    $('#recipe-intro').hide();
    $('.tab-col').removeClass('active');
    $('.status-icon').removeClass('active-status-tab')
    $('.upload-col').addClass('active');
    $('#upload-recipe-videos').show();
    get_recipe_from_main()
    $('#preview-publish').hide();
    $('#chef-endorsement').hide();
    $(this).hide();
    $('.proceed-summary').show();
    $('.back-upload').hide();
    $('.back-default-btn').show();
    $('.main-content').scrollTop();
    $('html, body').animate({scrollTop: $('.main-content').offset().top -200 }, 'slow');
    
  });
    $('.proceed-summary').click(function(){
      build_preview();
    $(this).hide();
    $('#recipe-intro').hide();
    $('.tab-col').removeClass('active');
    $('.publish-col').addClass('active');
    $('#upload-recipe-videos').hide();
    $('.back-upload').show();
    $('.back-default-btn').hide();
    $('#preview-publish').show();
    $('.main-content').scrollTop();
    $('html, body').animate({scrollTop: $('.main-content').offset().top -200 }, 'slow');
    
  });
  $('.proceed-preview').click(function(){
    
    $('#recipe-intro').hide();
    $('.tab-col').removeClass('active');
    $('.publish-col').addClass('active');
    $('#upload-recipe-videos').hide();
    $('#preview-publish').show();
    $('.main-content').scrollTop();
    $('html, body').animate({scrollTop: $('.main-content').offset().top -200 }, 'slow');
  });
  $('.back-upload-recipe').click(function(){
    
    $('#recipe-intro').hide();
    $('.tab-col').removeClass('active');
    $('.upload-col').addClass('active');
    $('#upload-recipe-videos').show();
    $('#preview-publish').hide();

    $('.main-content').scrollTop();
    $('html, body').animate({scrollTop: $('.main-content').offset().top -200 }, 'slow');
  });
  $('.back-default-btn').click(function(){
    $(this).hide();
    $('#recipe-intro').show();
    $('.proceed-upload').show();
    $('.proceed-summary').hide();

    $('.tab-col').removeClass('active');
    $('.intro-col').addClass('active');
    $('#upload-recipe-videos').hide();
    $('#preview-publish').hide();
    $('.main-content').scrollTop();
    $('html, body').animate({scrollTop: $('.main-content').offset().top -200 }, 'slow');
  });
  //Step recipe

  function get_recipe_from_main(){
    $('#urv-ing-name').html($('<option>', {
          value: "",
          text: "Select Ingredients"
      }));
    $('.ingridients-container li').each(function(){
      
      var ing = $('.ing-name', this).text();
      $('#urv-ing-name').append($('<option>', {
          value: ing,
          text: ing
      }));
    });
  }


  
  //Play pop up video from recipe details

  $('.step-row .step-video-play').click(function(){
    var title = $(this).data('title');
    var step_number = $(this).data('step_number');
    var desc = $(this).data('desc');
    var ings = $(this).data('ings');
    var vidSrc = $(this).data('src');
    var next = $(this).data('seq');
    $('.video-player-recipe-title').html(title);
    $('.video-player-recipe-step').html('Step '+step_number);
    $('.video-player-recipe-desc').html(desc);
    $('.video-player-recipe-ings').html(ings);
    $('#pop-video-player').attr('src', vidSrc);
    $('#pop-video-player')[0].load();
    $('#video-player-overlay').show();
    $('.video-player-step-next').attr('data-next', next);

  });

  $('.video-player-step-prev').on('click', function(){
    var next = $('.video-player-step-next').attr('data-next');
    if(next == "0"){
      return false;
    }
    var step = Number(next) -1;
    next_step_video(step);
    $('.video-player-step-next').attr('data-next', step);
  });
  $('.video-player-step-next').on('click', function(){
    
    var next = $('.video-player-step-next').attr('data-next');
    
    var step = Number(next) +1;
    var next_step = next_step_video(step);
    if(next_step == 1){
      $('.video-player-step-next').attr('data-next', step);
    }
  });

  function next_step_video(step){
    var el = $('#step-video-play-'+step);
    if(el.length){
      var title = el.data('title');
      var step_number = el.data('step_number');
      var desc = el.data('desc');
      var ings = el.data('ings');
      var vidSrc = el.data('src');
      $('.video-player-recipe-title').html(title);
      $('.video-player-recipe-step').html('Step '+step_number);
      $('.video-player-recipe-desc').html(desc);
      $('.video-player-recipe-ings').html(ings);
      $('#pop-video-player').attr('src', vidSrc);
      $('#pop-video-player')[0].load();
      $('#video-player-overlay').show();
      return "1";
    }
  }
  $('.video-expand').click(function(){
    $('.video-player-col-left').hide();
    $('.video-collapse').show();
    // $('.video-player-col-right').css('width', '100%');
    $('.video-player-col-right').addClass('fullwidth');
  });
  $('.video-collapse').click(function(){
    $('.video-player-col-left').show()
    $('.video-collapse').hide();
    // $('.video-player-col-right').css('width', '70%');
    $('.video-player-col-right').removeClass('fullwidth');
  });
  $('#video-player-close').click(function(){
    $('#video-player-overlay').hide();
  });

  $('.video-pause').click(function(){
    $('.pop-video-play-icon').show(); 
    $('#pop-video-player')[0].pause()
  });
  $('.pop-video-play-icon').click(function(){
    $(this).hide(); 
    $('#pop-video-player')[0].play()
  })
  //Monitor changes
  $('.page-create-recipe input').change(function(){
    somethingChanged = true;
  });

  //Step dropdown
  $('.step-drop-down').click(function(){
    var step_no = $(this).data('step');
    var el = $(".sub-step-" + step_no);
    el.toggle();
    
      $(this).toggleClass('open');

  });

  //STEP SORTING
  $('.video-step-items-container').sortable({
    update: function( event, ui ) {
      $('.video-step-items-container .video-step-item').each(function(i, e){
        // console.log(i);
        $(this).attr('data-seq', i + 1);
        var step_id = $(this).attr('data-step_id');
        var step_item = $('#steps-data #steps-step-' + step_id).attr('data-seq', i + 1);
      });
      // sort_steps();
    }
  });

  //Review
  $('.write-review').click(function(){
    $('.review-form').toggle();

  });
  $('.expand-review').click(function(){
    $('.inactive').toggle();
    console.log('click review');
  });
  //REMOVE Step
  $('.step2-steps-row').on('click', '.remove-step', function(){
    if(confirm("Are you sure you want to delete this step?")){
      var w = $('.video-step-items-container').width();
      $('.video-step-items-container').css('width', w - 115);

      var this_step = $(this).parents('.video-step-item').data('step_id'); //This box
      var parent = $(this).parents('.video-step-item').data('parent'); //parent
      $(this).parents('.video-step-item').remove();
      $('#steps-data #steps-step-'+this_step).remove();
      if(this_step == parent){
        $('.video-step-items-container .video-step-item').each(function(i, e){
          if($(this).data('parent') == parent){
            $(this).remove();
          }
        });
        $('#steps-data li.steps-list').each(function(i, e){
          if($(this).data('parent')== parent){
            $(this).remove();
          }
        });
      }
      
      rearrange_steps();
      rearrange_step_data();
      $('#urv-steps span').text('');

      reset_data_form();
      $('#step-id-container').attr('data-id', 0);

    }
    else{
        return false;
    }
  });

  //REMOVE SUBSTEP
  $('.step2-steps-row').on('click', '.remove-sub-step', function(){
    if(confirm("Are you sure you want to delete this step?")){
      var w = $('.video-step-items-container').width();
      $('.video-step-items-container').css('width', w - 115);

      var this_step = $(this).parents('.video-step-item').data('step_id'); //This box
      var parent_id = $(this).parents('.video-step-item').data('parent'); //parent

      $('.video-step-items-container #video-step-item-' + this_step).remove();
      $('#steps-data #steps-step-' + this_step).remove();

      

      // var sub = 0;
      // $('.video-step-items-container .video-step-item').each(function(i, e){
      //   var step_id = $(this).data('step_id');
      //   var parent = $(this).data('parent');
      //   // var sub_step = $(this).data('sub_step');

      //   if(step_id != parent && parent_id == parent){
      //     sub++;

      //     $(this).attr('data-step_id', parent_id +'-'+sub);
      //     $(this).attr('data-parent', parent_id);
      //     $(this).attr('data-sub_step', sub);
      //     $(this).attr('id', 'video-step-item-'+parent_id+'-'+sub);


      //     $('.video-step-number', this).text('Step ' + parent_id +'.'+sub );
      //     $('video', this).attr('id', 'video-step-' + parent_id +'-'+sub );
      //     $('.video-step-duration', this).attr('id', 'duration-' + parent_id +'-'+sub );
      //     $('#urv-steps span').text('');
      //   }
      // });
      $('#urv-steps span').text('');
      reset_data_form();
      $('#step-id-container').attr('data-id', 0);
       // rearrange_step_data();
     }
    else{
        return false;
    }
  });


  function rearrange_steps(){
    var parent_id = 0;
    $('.video-step-items-container .video-step-item').each(function(i, e){
      var step_id = $(this).data('step_id');
      var parent = $(this).data('parent');
      if(step_id == parent){
        parent_id++;
        $(this).attr('data-step_id', parent_id);
        $(this).attr('data-parent', parent_id);
        $(this).attr('id', 'video-step-item-'+parent_id);
        $('.video-step-number', this).text('Step ' + parent_id );
        $('video', this).attr('id', 'video-step-' + parent_id );
        $('.video-step-duration', this).attr('id', 'duration-' + parent_id );
        arrange_child(parent, parent_id);
      }
    });
    
  }
 function arrange_child(parent_id, c){
  $('.video-step-items-container .video-step-item').each(function(i, e){
      var step_id = $(this).data('step_id');
      var parent = $(this).data('parent');
      var sub_step = $(this).data('sub_step');
      if(step_id != parent && parent_id == parent){
        $(this).attr('data-step_id', c +'-'+sub_step);
        $(this).attr('data-parent', c);
        $(this).attr('id', 'video-step-item-'+ c +'-'+sub_step);

        $('.video-step-number', this).text('Step ' + c +'.'+sub_step );
        $('video', this).attr('id', 'video-step-' + c +'-'+sub_step );
        $('.video-step-duration', this).attr('id', 'duration-' + parent_id +'-'+sub_step );
        $('#urv-steps span').text('');
      }
    });
       
  }
  function rearrange_step_data(){
    var parent_id = 0;
     $('#steps-data li.steps-list').each(function(i, e){

      var step_id = $(this).attr('data-step_id');
      var parent = $(this).attr('data-parent');
      if(step_id == parent){
        parent_id++;
        $(this).attr('data-step_id', parent_id);
        $(this).attr('data-parent', parent_id);
        $(this).attr('id', 'steps-step-'+parent_id);
        arrange_child_data(parent, parent_id);
      }
    });
    
  }
  function arrange_child_data(parent_id, c){
  $('#steps-data li.steps-list').each(function(i, e){
      var step_id = $(this).data('step_id');
      var parent = $(this).data('parent');
      var sub_step = $(this).data('sub_step');
      if(step_id != parent && parent_id == parent){
        $(this).attr('data-step_id', c +'-'+sub_step);
        $(this).attr('data-parent', c);
        $(this).attr('id', 'steps-step-'+c);
      }
    });
       
  }
  // function arrange_child_data(old_parent, c){
  // $('#steps-data li.steps-list').each(function(i, e){
  //     var step_id = $(this).data('step_id');
  //     var parent = $(this).data('parent');
  //     var sub_step = $(this).data('sub_step');
  //     if(step_id != parent && old_parent == parent){
  //       $(this).attr('data-step_id', old_parent +'-'+i);
  //       $(this).attr('data-parent', old_parent);
  //       $(this).attr('data-sub_step', i);
  //       $(this).attr('id', 'steps-step-'+old_parent+'-'+i);
  //     }
  //   });
       
  // }

});