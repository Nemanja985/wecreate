<?php
namespace TGI\UserFrontend\Includes;

class TGI_User_Frontend_Logout {

    /**
     * TGI_User_Frontend_Logout constructor.
     */
    public function __construct() {
        add_shortcode('tgi-logout', array( $this, 'logout_render' ) );
    }

    /**
     * Render the logout page
     */
    public function logout_render() {
        if ( ! is_user_logged_in() ):
            wp_redirect(home_url());
            exit;
        else: ?>
            <?php
            $user = wp_get_current_user();
            wp_logout();
            $redirect_to = $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : home_url();

            $redirect_to = apply_filters( 'logout_redirect', $redirect_to, '', $user );
            ?>
            <div class="logout-container">
                <p class="logout-msg">
                    You have logged out. You will be redirected in 5 seconds. <br />
                    Please <a href="<?php echo $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : home_url(); ?>">click here</a> if your browser does not redirect you.
                </p>
            </div>
            <script type="text/javascript">
                setTimeout(function () {
                	window.location.href = "<?php echo $redirect_to; ?>";
                }, 5000);
            </script>
        <?php endif;
    }
}

/**
 * Initialize
 */
$tgi_user_frontend_logout = new TGI_User_Frontend_Logout();
unset( $tgi_user_frontend_logout );