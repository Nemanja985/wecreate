<?php
/**
 * Plugin Name: TGI Social Login
 * Plugin URI: http://tgitech.net/
 * Description: Customize the logic of other installed social login plugins
 * Version: 1.0.0
 * Author: Jason Yip
 * Author URI: http://tgitech.net/
 * License: GPLv2
 */

namespace TGI\SocialLogin;

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;
define( 'TGI_SOCIAL_LOGIN_VERSION', '1.0.0' );
define( 'TGI_SOCIAL_LOGIN_TEXT_DOMAIN', 'tgi-social-login');
define( 'TGI_SOCIAL_LOGIN_DIR', dirname( __FILE__ ) );
define( 'TGI_SOCIAL_LOGIN_URL', plugin_dir_url( __FILE__ ) );

class TGI_Social_Login {
    /**
     * TGI_Social_Login constructor.
     */
    public function __construct() {
        add_action( 'init', array( $this, 'init' ) );
    }

    /**
     * Initialize the plugin
     */
    public function init() {
        if( class_exists( 'LoginWithAmazonUtility' ) ) {
            add_filter( 'login_with_amazon_create_user_is_amazon', array( $this, 'always_sign_in_existing_user' ), 10, 2 );

            // Override the frontend JS file
            add_action( 'wp_print_scripts', array( $this, 'amazon_dequeue_script' ), 100 );

            // Add new script
            add_action('wp_enqueue_scripts', array( $this, 'amazon_enqueue_script' ) );

            // Change username and nicename
            add_filter( 'login_with_amazon_new_user_userdata', array($this, 'change_social_login_new_user_data') );
        }

        if( function_exists( 'wsl_render_auth_widget_in_wp_login_form' ) ) {
            // Add social login buttons in custom login form
            add_filter( 'login_form_middle', array( $this, 'show_wsl_login_widget' ) );

            // Change username and nicename
            add_filter( 'wsl_hook_process_login_alter_wp_insert_user_data', array($this, 'change_social_login_new_user_data') );
        }
    }

    public function always_sign_in_existing_user($isAmazonUser, $user) {
        if ( !$isAmazonUser ) {
            add_user_meta($user->ID, '_login_with_amazon', true);
        }
        return true;
    }

    public function amazon_dequeue_script() {
        wp_dequeue_script( 'loginwithamazon' );
    }

    public function amazon_enqueue_script() {
        wp_enqueue_script('tgi_loginwithamazon', TGI_SOCIAL_LOGIN_URL . 'js/amazon_login.js');
    }

    public function show_wsl_login_widget() {
        ob_start();
        wsl_render_auth_widget_in_wp_login_form();
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    public function change_social_login_new_user_data($userdata) {
        $email = $userdata['user_email'];
        $username = current( explode( '@', $email ) );
        $nicename = mb_substr( $username, 0, 50 );
        $base_username = $username;
        $i = 2;
        while ( username_exists( $username ) ) {
            $username = $base_username . '-' . $i;
            $i++;
        }
        $userdata['user_login'] = $username;
        $userdata['user_nicename'] = $nicename;
        $userdata['display_name'] = $username;
        return $userdata;
    }
}

/**
 * Initialize
 */
$tgi_social_login = new TGI_Social_Login();
unset( $tgi_social_login );
