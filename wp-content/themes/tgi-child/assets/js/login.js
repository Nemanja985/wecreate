jQuery(document).ready(function($){
  var modal = $('#tgi_login_box')[0];
  $('a[href="#login"]').click(function(event){
      event.preventDefault();
      $('#tgi_login_box').show();
      $('.tgi_login_box_content').show();
      $('#register-overlay').hide();
      $('#login-overlay').hide();
      scroll_to_top();
  });
  $(window).click(function(event){
    if (event.target == modal) {
        $('#tgi_login_box').hide();
    }
  })
  $('.register-box').click(function(){
      $('.tgi_login_box_content').hide();
      $('#register-overlay').show();
      $('#login-overlay').hide();
  });
  $('.sign-in-box').click(function(){
      $('.tgi_login_box_content').hide();
      $('#register-overlay').hide();
      $('#login-overlay').show();
  });
  $('.close-login').click(function(){
      $('.tgi_login_box_content').show();
      $('#register-overlay').hide();
      $('#login-overlay').hide();
  });

  function scroll_to_top(){
    $('html, body').animate({
        scrollTop: $("#tgi_login_box").offset().top - 50
    }, 800);
  }

  $('.sign-up-btn').click(function(){
    $('#tgi_login_box').show();
    $('.tgi_login_box_content').hide();
    $('#register-overlay').show();
    $('#login-overlay').hide();
    var skill = $('#select-skill').val();
    var cook_for = $('#cook_for_select').val();
    $('#cook_for').val(cook_for);
    $('#cooking_skill').val(skill);
    scroll_to_top();
  });
});