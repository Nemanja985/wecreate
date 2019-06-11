<?php
/**
 * TGI WPML Bookmark Listing Page
 *
 * @package TGI\WpmlBookmark
 * @subpackage TGI\WpmlBookmark\views
 */
namespace TGI\WpmlBookmark\views;

/**
 * Class TGI_WPML_Bookmark_Views_Listing
 */
class TGI_WPML_Bookmark_Views_Listing {
	/**
	 * Start up
	 */
	public function __construct() {
		add_shortcode( 'tgi-wpml-user-bookmark-list', array( $this, 'print_user_bookmark_list' ) );
	}

	/**
	 * Print the user bookmark list
	 */
	public function print_user_bookmark_list() {
		if ( ! is_user_logged_in() ) {
			wp_safe_redirect( home_url() );
			exit;
		} else {
			$bookmarks = get_user_meta( get_current_user_id(), 'bookmarks', true );
			do_action( 'before_print_user_bookmark_list' );
			?>
			<div class="tgi-bookmark-user-list-container">
				<?php if ( isset( $bookmarks ) && is_array( $bookmarks ) && count( $bookmarks ) > 0 ) { ?>
					<ul id="tgi-bookmark-user-list">
						<?php
						foreach ( $bookmarks as $bookmark ) {
							$translations = apply_filters( 'wpml_get_element_translations', null, $bookmark );
							if ( isset( $translations[ ICL_LANGUAGE_CODE ] ) ) {
								$translated_obj = $translations[ ICL_LANGUAGE_CODE ];
								if ( 'publish' === $translated_obj->post_status ) {
									?>
									<li>
										<a href="<?php echo get_post_permalink( $translated_obj->element_id ); // WPCS: XSS ok. ?>"><?php echo esc_html( $translated_obj->post_title ); ?></a>
									</li>
									<?php
								}
							}
						}
						?>
					</ul>
				<?php } ?>
			</div>
			<?php
			do_action( 'after_print_user_bookmark_list' );
		}
	}
}

// Initialize.
$tgi_wpml_bookmark_views_listing = new TGI_WPML_Bookmark_Views_Listing();
unset( $tgi_wpml_bookmark );
