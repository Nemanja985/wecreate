<?php
namespace TGI\UserFrontend\Includes;

class TGI_User_Frontend_Register {
    /**
     * @var TGI_User_Frontend_Helper
     */
    protected $_helper;

    /**
     * TGI_User_Frontend_Register constructor.
     */
    public function __construct() {
        $this->_helper = new TGI_User_Frontend_Helper();
        add_shortcode('tgi-register-form', array( $this, 'form_creation' ) );
        add_action( 'user_register', array( $this, 'post_register' ) );
    }

    /**
     * Create the register form and show error message if needed
     */
    public function form_creation()
    {
        if (is_user_logged_in() || !get_option('users_can_register')):
            wp_redirect(home_url());
            exit;
        else: ?>
            <?php
            $http_post = ('POST' == $_SERVER['REQUEST_METHOD']);

            $user_login = '';
            $user_email = '';
            $errors = null;

            if ($http_post) {
                if (isset($_POST['user_email']) && is_string($_POST['user_email'])) {
                    $user_email = wp_unslash($_POST['user_email']);
                    $user_login = strstr($user_email, '@', true);
                }
                $errors = register_new_user($user_login, $user_email);
                if (!is_wp_error($errors)) {
                    $redirect_to = !empty($_POST['redirect_to']) ? $_POST['redirect_to'] : home_url();
                    wp_safe_redirect($redirect_to);
                    exit();
                }
            }

            $registration_redirect = !empty($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : '';
            $redirect_to = apply_filters('tgi_custom_registration_redirect', $registration_redirect);
            ?>
            <section class="register-form">
                <?php if ($errors && is_wp_error($errors)): ?>
                    <div id="message" class="error">
                    <?php
                        foreach ($errors->get_error_messages() as $error_message) {
                            echo $error_message . "<br />\n";
                        }
                    ?>
                    </div>
                <?php endif; ?>
                <form name="registerform" id="registerform" action="" method="post" novalidate="novalidate">
                    <p>
                        <label for="user_email"><?php _e('Email') ?></label>
                            <input type="email" name="user_email" id="user_email" class="input" value="<?php echo esc_attr( wp_unslash( $user_email ) ); ?>" size="25" />
                    </p>
                    <p>
                        <label for="user_password"><?php _e('Password') ?></label>
                        <input type="password" name="user_password" id="user_password" class="input" size="20" />
                    </p>
                    <?php do_action( 'register_form' ); ?>
                    <br class="clear" />
                    <input type="hidden" name="redirect_to" value="<?php echo ($redirect_to ? $redirect_to : $_SERVER['HTTP_REFERER']); ?>" />
                    <p class="submit"><input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e('Register'); ?>" /></p>
                </form>
            </section>
        <?php endif;
    }

    /**
     * Redirect User to the last page visited for the front-end register process
     * @param $redirect_to
     * @return string
     */
    public function register_redirect( $redirect_to ) {
        if ( isset($_POST['register_referrer']) ) {
            if ( $_POST['register_referrer'] === "direct" || ! wp_http_validate_url( $_POST['register_referrer'] ) ) {
                return home_url();
            } else {
                return $_POST['register_referrer'];
            }
        }
        return $redirect_to;
    }

    /**
     * Run processes after registration
     *
     * @param $user_id
     */
    public function post_register ( $user_id ) {
        $this->set_user_password( $user_id );
        $this->auto_login( $user_id );
    }

    /**
     * Set user input password as the WordPress account password
     *
     * @param $user_id
     */
    protected function set_user_password ( $user_id ) {
        if ( isset( $_POST['user_password'] ) ) {
            wp_set_password($_POST['user_password'], $user_id);
        }
    }

    /**
     * Login after registration
     *
     * @param $user_id
     */
    protected function auto_login ( $user_id ) {
        $secure_cookie = '';
        $user = wp_signon( [
            'user_login' => $_POST['user_email'],
            'user_password' => $_POST['user_password']
        ], $secure_cookie );
    }
}

/**
 * Initialize
 */
$tgi_user_frontend_register = new TGI_User_Frontend_Register();
unset( $tgi_user_frontend_register );