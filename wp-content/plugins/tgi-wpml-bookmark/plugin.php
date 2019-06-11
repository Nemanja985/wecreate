<?php
/**
 * TGI Multilingual Bookmark
 *
 * @package TGI\WpmlBookmark
 */

/**
 * Plugin Name: TGI Multilingual Bookmark
 * Plugin URI: http://tgitech.net/
 * Description: Allow user to bookmark any posts using WPML translation ID as key to sustain aligned bookmark across multiple WPML languages
 * Version: 1.0.0
 * Author: Jason Yip
 * Author URI: http://tgitech.net/
 * License: GPLv2
 */

namespace TGI\WpmlBookmark;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'TGI_WPML_BOOKMARK_VERSION', '1.0.0' );
define( 'TGI_WPML_BOOKMARK_TEXT_DOMAIN', 'tgi-wpml-bookmark' );
define( 'TGI_WPML_BOOKMARK_DIR', dirname( __FILE__ ) );
define( 'TGI_WPML_BOOKMARK_URL', plugin_dir_url( __FILE__ ) );

if ( function_exists( 'icl_object_id' ) ) {
	require_once TGI_WPML_BOOKMARK_DIR . '/admin/class-tgi-wpml-bookmark-admin-setting.php';
	require_once TGI_WPML_BOOKMARK_DIR . '/views/class-tgi-wpml-bookmark-views-listing.php';
}

/**
 * Class TGI_WPML_Bookmark
 */
class TGI_WPML_Bookmark {
	/**
	 * TGI_WPML_Bookmark constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Initialize the plugin
	 */
	public function init() {
		// Check if WPML module is installed.
		if ( function_exists( 'icl_object_id' ) ) {
			// Display Bookmark button.
			add_filter( 'the_content', array( $this, 'display_bookmark_button' ) );
			add_action( 'wp_footer', array( $this, 'insert_bookmark_javascript' ) );
			add_action( 'wp_ajax_update_bookmark', array( $this, 'update_user_bookmark' ) );
		} else {
			add_action( 'admin_notices', array( $this, 'notice_no_wpml' ) );
		}
	}

	/**
	 * Display bookmark button in a post page
	 *
	 * @param  string $content post content.
	 * @return string
	 */
	public function display_bookmark_button( $content ) {
		if ( $this->frontend_validation() ) {
			do_action( 'before_print_post_bookmark_btn' );
			$content .= '<span class="post-bookmark">';
			if ( $this->is_bookmarked( get_current_user_id(), get_the_ID() ) ) {
				$content .= '<a href="#" id="add-bookmark">' . esc_html__( 'Bookmarked', 'tgi-wpml-bookmark' ) . '</a>';
			} else {
				$content .= '<a href="#" id="add-bookmark">' . esc_html__( 'Bookmark', 'tgi-wpml-bookmark' ) . '</a>';
			}
			$content .= '</span>';
			do_action( 'after_print_post_bookmark_btn' );
		}
		return $content;
	}

	/**
	 * Insert JS Script for ajax bookmark
	 */
	public function insert_bookmark_javascript() {
		if ( $this->frontend_validation() ) {
			if ( is_user_logged_in() ) {
				$action     = 'update_bookmark';
				$post_id    = get_the_ID();
				$ajax_nonce = wp_create_nonce( $action . '_' . get_current_user_id() . '_' . $post_id );
				?>
				<script type="text/javascript">
					var ajaxurl = "<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>";
					jQuery(document).ready(function () {
						jQuery('#add-bookmark').click(function () {
							var data = {
								'action': '<?php echo $action; // WPCS: XSS ok. ?>',
								'post_id':  <?php echo $post_id; // WPCS: XSS ok. ?>,
								'_ajax_nonce': '<?php echo $ajax_nonce; // WPCS: XSS ok. ?>'
							};

							// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
							jQuery.post(ajaxurl, data, function (response) {
								var resObj = JSON.parse(response);
								if (resObj.bookmarked) {
									jQuery('#add-bookmark').text('<?php esc_html_e( 'Bookmarked', 'tgi-wpml-bookmark' ); ?>');
								} else {
									jQuery('#add-bookmark').text('<?php esc_html_e( 'Bookmark', 'tgi-wpml-bookmark' ); ?>');
								}
							});
						});
					});
				</script>
				<?php
			} else {
				?>
				<script type="text/javascript">
					jQuery(document).ready(function () {
						jQuery('#add-bookmark').click(function () {
							alert( '<?php esc_html_e( 'Please login before adding bookmark', 'tgi-wpml-bookmark' ); ?>' );
						});
					});
				</script>
				<?php
			}
		}
	}

	/**
	 * Update User Bookmark
	 * It can be either adding post into bookmark or deleting depend on the post
	 */
	public function update_user_bookmark() {
		if ( ! isset( $_POST ) || ! isset( $_POST['post_id'] ) ) {
			echo 'Missing post ID';
			wp_die();
		}
		$post_id = intval( $_POST['post_id'] );
		$user_id = get_current_user_id();
		if ( ! $user_id ) {
			echo 'Not signed in';
			wp_die();
		}
		check_ajax_referer( 'update_bookmark_' . $user_id . '_' . $post_id );

		$bookmarks = get_user_meta( $user_id, 'bookmarks', true );
		if ( ! is_array( $bookmarks ) ) {
			$bookmarks = [];
		}
		$bookmarked   = false;
		$trid         = intval( apply_filters( 'wpml_element_trid', null, $post_id ) );
		$bookmark_key = array_search( $trid, $bookmarks, true );
		if ( false === $bookmark_key ) {
			$bookmarks[] = $trid;
			$bookmarked  = true;
		} else {
			unset( $bookmarks[ $bookmark_key ] );
		}
		update_user_meta( $user_id, 'bookmarks', $bookmarks );
		$response = (object) [
			'bookmarked' => $bookmarked,
		];
		echo wp_json_encode( $response );
		wp_die();
	}

	/**
	 * Check if front-end should display.
	 *
	 * @return bool
	 */
	private function frontend_validation() {
		$bookmark_setting  = get_option( 'tgi_bookmark_setting' );
		$allowed_post_type = isset( $bookmark_setting['allowed_post_types'] ) && is_array( $bookmark_setting['allowed_post_types'] ) ? $bookmark_setting['allowed_post_types'] : [];
		$display_for_guest = isset( $bookmark_setting['guest_display_bookmark_btn'] ) ? intval( $bookmark_setting['guest_display_bookmark_btn'] ) : 0;
		if ( ! $display_for_guest && ! is_user_logged_in() ) {
			// Not display if not logged in.
			return false;
		}
		return is_single() && in_array( get_post_type(), $allowed_post_type, true );
	}

	/**
	 * Check if the post is bookmarked for given user.
	 *
	 * @param int $user_id User ID.
	 * @param int $post_id Post ID.
	 * @return bool
	 */
	public function is_bookmarked( $user_id, $post_id ) {
		$bookmarks = get_user_meta( $user_id, 'bookmarks', true );
		if ( ! is_array( $bookmarks ) ) {
			return false;
		}
		$trid = intval( apply_filters( 'wpml_element_trid', null, $post_id ) );
		if ( array_search( $trid, $bookmarks, true ) !== false ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Display Admin Notice if the WPML plugin is not activated
	 */
	public function notice_no_wpml() {
		?>
		<div class="error wpml-admin-notice wpml-st-inactive wpml-inactive">
			<p><?php esc_html_e( 'Please activate WPML Multilingual CMS to have TGI WPML Bookmark working.', 'tgi-wpml-bookmark' ); ?></p>
		</div>
		<?php
	}
}

// Initialize.
$tgi_wpml_bookmark = new TGI_WPML_Bookmark();
unset( $tgi_wpml_bookmark );
