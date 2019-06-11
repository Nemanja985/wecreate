<?php

add_action('wp_footer', function () {
  if(!is_user_logged_in()){
    ?>
    <div id="tgi_login_box">
      <div class="tgi_login_box_content">
        <img src="<?php echo home_url(); ?>/wp-content/themes/tgi-child/dist/images/tgi_login_box_welcome.svg">
        <h2>Welcome to My Cooking!</h2>
        <?php echo do_shortcode('[miniorange_social_login shape="round" theme="custombackground"]') ?>
         <p class="tgi_login_box_signin">Have an account? <span class="sign-in-box">Sign In</span> or <span class="register-box">Register</span></p>
      </div>
      
      <div id="register-overlay" class="register-overlay">
          <div class="close-login">x</div>
          <h3>Registration Form</h3>
          <?php echo do_shortcode('[user_registration_form id="1821"]'); ?>

      </div>
      <div id="login-overlay" class="login-overlay">
        <div class="close-login">x</div>

        <h3>Login</h3>
        <?php
          $args = array(
              'echo'           => true,
              'remember'       => true,
              'redirect'       => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
              'form_id'        => 'loginform',
              'id_username'    => 'user_login',
              'id_password'    => 'user_pass',
              'id_remember'    => 'rememberme',
              'id_submit'      => 'wp-submit',
              'label_username' => __( 'Username or Email Address' ),
              'label_password' => __( 'Password' ),
              'label_remember' => __( 'Remember Me' ),
              'label_log_in'   => __( 'Log In' ),
              'value_username' => '',
              'value_remember' => false
          );
          wp_login_form($args);
        
          ?>
          <a href="<?php echo get_site_url(); ?>/wp-login.php?action=lostpassword">Forgot Your Password?</a>
  
      </div>
    </div>

  <?php }
});


if(!function_exists('registration_form')){
  function registration_form(){
    if(!is_user_logged_in()){
      echo do_shortcode('[user_registration_form id="1821"]');
    }else{
      $url = get_site_url().'/my-profile/';
      wp_redirect($url);
    }
  }
  add_shortcode('sc_registration_form', 'registration_form');
}

