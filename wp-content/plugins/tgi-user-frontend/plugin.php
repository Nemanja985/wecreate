<?php
/**
 * Plugin Name: TGI User Frontend
 * Plugin URI: http://tgitech.net/
 * Description: Add Front-end User Pages such as login and register
 * Version: 1.0.0
 * Author: Jason Yip
 * Author URI: http://tgitech.net/
 * License: GPLv2
 */

namespace TGI\UserFrontend;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'TGI_USER_FRONTEND_VERSION', '1.0.0' );
define( 'TGI_USER_FRONTEND_TEXT_DOMAIN', 'tgi-user-frontend');
define( 'TGI_USER_FRONTEND_DIR', dirname( __FILE__ ) );

class TGI_User_Frontend {
    /**
     * TGI_User_Frontend constructor.
     */
    public function __construct() {
        add_action( 'init', array( $this, 'init' ) );
    }

    /**
     * Initialize the plugin
     */
    public function init() {
        include_once( TGI_USER_FRONTEND_DIR . "/includes/helper.php" );
        include_once( TGI_USER_FRONTEND_DIR . "/includes/login.php" );
        include_once( TGI_USER_FRONTEND_DIR . "/includes/logout.php" );
        include_once( TGI_USER_FRONTEND_DIR . "/includes/register.php" );
        include_once( TGI_USER_FRONTEND_DIR . "/includes/admin.php" );
    }
}

/**
 * Initialize
 */
$tgi_user_frontend = new TGI_User_Frontend();
unset( $tgi_user_frontend );
