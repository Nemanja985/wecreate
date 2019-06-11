<?php
namespace TGI\UserFrontend\Includes;

class TGI_User_Frontend_Login {
    /**
     * @var TGI_User_Frontend_Helper
     */
    protected $_helper;

    /**
     * TGI_User_Frontend_Login constructor.
     */
    public function __construct() {
        $this->_helper = new TGI_User_Frontend_Helper();
        add_shortcode('tgi-login-form', array( $this, 'form_creation' ) );
        add_filter( 'login_form_bottom', array( $this, 'add_hidden_login_form_field' ) );
        add_action( 'wp_login_failed', array( $this, 'login_fail' ) );
        add_filter('login_redirect', array( $this, 'login_redirect' ) );
        add_filter( 'authenticate', array( $this, 'verify_email_password' ), 5, 3 );
    }

    /**
     * Create the login form and show error message if needed
     */
    public function form_creation() {
        if ( is_user_logged_in() ):
            wp_redirect(home_url());
            exit;
        else: ?>
            <section class="login-form">
            <?php if ( isset( $_GET['login'] ) ): ?>
                <div id="message" class="error">
                    <?php if ( $_GET['login'] == 'failed' ): ?>
                        <p><?php _e( 'Invalid email address or password', TGI_USER_FRONTEND_TEXT_DOMAIN ); ?></p>
                    <?php elseif ( $_GET['login'] == 'empty_email' ): ?>
                        <p><?php _e( 'Empty email address', TGI_USER_FRONTEND_TEXT_DOMAIN ); ?></p>
                    <?php elseif ( $_GET['login'] == 'invalid_email' ): ?>
                        <p><?php _e( 'Invalid email format', TGI_USER_FRONTEND_TEXT_DOMAIN ); ?></p>
                    <?php elseif ( $_GET['login'] == 'empty_password' ): ?>
                        <p><?php _e( 'Empty password', TGI_USER_FRONTEND_TEXT_DOMAIN ); ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php
                $args = array(
                    'echo'           => true,
                    'redirect'       => home_url(),
                    'form_id'        => 'loginform',
                    'label_username' => __( 'Email Address' ),
                    'label_password' => __( 'Password' ),
                    'label_remember' => __( 'Remember Me' ),
                    'label_log_in'   => __( 'Log In' ),
                    'id_username'    => 'user_login',
                    'id_password'    => 'user_pass',
                    'id_remember'    => 'rememberme',
                    'id_submit'      => 'wp-submit',
                    'remember'       => true,
                    'value_username' => isset( $_GET['email'] ) ? $_GET['email'] : null,
                    'value_remember' => true
                );

                // Calling the login form.
                wp_login_form( $args );
            ?>
            </section>
        <?php endif;
    }

    /**
     * Add hidden fields into the front-end login form
     * @return string
     */
    public function add_hidden_login_form_field() {
        if ( $GLOBALS['pagenow'] !== 'wp-login.php' ) {
            return '<input type="hidden" name="is_tgi_login" value="1" /><input type="hidden" name="login_referrer" value="' . ($_GET['referrer'] ? urldecode($_GET['referrer']) : $_SERVER['HTTP_REFERER']) . '" />';
        }
    }

    /**
     * @param $user
     * @param $email
     * @param $password
     */
    public function verify_email_password( $user, $email, $password ) {
        if ( isset($_POST['is_tgi_login']) ) {
            $referrer = $_SERVER['HTTP_REFERER'];
            if ($email == "") {
                wp_redirect($this->_helper->add_query_params($referrer, [
                        'login' => 'empty_email',
                        'referrer' => $this->get_referrer($_POST['login_referrer'])
                ]));
                exit();
            } elseif ( ! is_email($email) ) {
                wp_redirect($this->_helper->add_query_params($referrer, [
                    'login' => 'invalid_email',
                    'referrer' => $this->get_referrer($_POST['login_referrer'])
                ]));
                exit();
            } elseif ($password == "") {
                wp_redirect($this->_helper->add_query_params($referrer, [
                    'email' => $email,
                    'login' => 'empty_password',
                    'referrer' => $this->get_referrer($_POST['login_referrer'])
                ]));
                exit();
            }
        }
    }

    /**
     * Redirect to the front-end login form if failed to login
     * @param $username
     */
    public function login_fail( $username ) {
        if ( is_admin() ) {
            return;
        }

        $referrer = $_SERVER['HTTP_REFERER'];
        if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
            wp_redirect($this->_helper->add_query_params($referrer, [
                'email' => $username,
                'login' => 'failed',
                'referrer' => $this->get_referrer($_POST['login_referrer'])
            ]));
            exit;
        }
    }

    /**
     * Redirect User to the last page visited for the front-end login process
     * @param $redirect_to
     * @return string
     */
    public function login_redirect( $redirect_to ) {
        if ( isset($_POST['login_referrer']) ) {
            if ( $_POST['login_referrer'] === "direct" || ! wp_http_validate_url( $_POST['login_referrer'] ) ) {
                return home_url();
            } else {
                return $_POST['login_referrer'];
            }
        }
        return $redirect_to;
    }

    /**
     * Prepare the value for the frontend referrer parameter
     * @param $url
     * @return string
     */
    protected function get_referrer( $url ) {
        return $url ? urlencode($url) : "direct";
    }
}

/**
 * Initialize
 */
$tgi_user_frontend_login = new TGI_User_Frontend_Login();
unset( $tgi_user_frontend_login );
