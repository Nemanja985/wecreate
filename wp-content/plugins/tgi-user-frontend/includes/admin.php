<?php
namespace TGI\UserFrontend\Includes;

class TGI_User_Frontend_Admin {
    /**
     * TGI_User_Frontend_Admin constructor.
     */
    public function __construct() {
        add_action( 'admin_init', array( $this, 'disable_dashboard' ) );
        add_action( 'wp_head', array( $this, 'disable_admin_bar' ) );
    }

    /**
     * Disable Admin Dashboard for non-admin / non-editor users
     */
    public function disable_dashboard() {
        if (!is_user_logged_in()) {
            return;
        }
        if (!current_user_can('administrator') && !current_user_can('editor') && is_admin() && strpos( strtok( $_SERVER['REQUEST_URI'], '?' ), 'admin-ajax.php' ) === false) {
            wp_redirect(home_url());
            exit;
        }
    }

    /**
     * Hide the admin bar in header
     */
    public function disable_admin_bar() {
        if (!current_user_can('administrator') && !current_user_can('editor')) {
            show_admin_bar(false);
        }
    }
}

/**
 * Initialize
 */
$tgi_user_frontend_admin = new TGI_User_Frontend_Admin();
unset( $tgi_user_frontend_admin );
