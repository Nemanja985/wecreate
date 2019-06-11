<?php
/**
 * TGI WPML Bookmark Admin Setting Page
 *
 * @package TGI\WpmlBookmark
 * @subpackage TGI\WpmlBookmark\admin
 */
namespace TGI\WpmlBookmark\admin;

/**
 * Class TGI_WPML_Bookmark_Admin_Setting
 */
class TGI_WPML_Bookmark_Admin_Setting {
	/**
	 * Holds the values to be used in the fields callbacks
	 *
	 * @var $options
	 */
	private $options;

	/**
	 * Start up
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	/**
	 * Add options page
	 */
	public function add_plugin_page() {
		add_options_page(
			'Bookmark Settings',
			'Bookmark Settings',
			'manage_options',
			'tgi-bookmark-setting',
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Options page callback
	 */
	public function create_admin_page() {
		$this->options = get_option( 'tgi_bookmark_setting' );
		?>
		<div class="wrap">
			<h1>Bookmark Settings</h1>
			<form method="post" action="options.php">
				<?php
				// This prints out all hidden setting fields.
				settings_fields( 'tgi-bookmark-option-group' );
				do_settings_sections( 'tgi-bookmark-setting' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register and add settings
	 */
	public function page_init() {
		register_setting(
			'tgi-bookmark-option-group',
			'tgi_bookmark_setting',
			array()
		);

		add_settings_section(
			'setting_general',
			'General Settings',
			array( $this, 'print_general_section_info' ),
			'tgi-bookmark-setting'
		);

		add_settings_field(
			'post_types',
			'Allowed Post Types',
			array( $this, 'print_post_types_field' ),
			'tgi-bookmark-setting',
			'setting_general'
		);

		add_settings_section(
			'setting_display',
			'Display Settings',
			array( $this, 'print_display_section_info' ),
			'tgi-bookmark-setting'
		);

		add_settings_field(
			'post_types',
			'Show Bookmark button if not logged in',
			array( $this, 'print_guest_display_bookmark_btn_field' ),
			'tgi-bookmark-setting',
			'setting_display'
		);
	}

	/**
	 * Print the General Section text
	 */
	public function print_general_section_info() {
	}

	/**
	 * Print the Display Section text
	 */
	public function print_display_section_info() {
	}

	/**
	 * Print allowed post types field
	 */
	public function print_post_types_field() {
		$post_types = get_post_types(
			[ 'public' => true ],
			'objects'
		);
		foreach ( $post_types as $post_type ) {
			?>
			<div class="tgi-setting-checkbox-container">
				<?php if ( isset( $this->options['allowed_post_types'] ) && is_array( $this->options['allowed_post_types'] ) && in_array( $post_type->name, $this->options['allowed_post_types'], true ) ) { ?>
					<input type="checkbox" name="tgi_bookmark_setting[allowed_post_types][]" value="<?php echo $post_type->name; // WPCS: XSS ok. ?>" checked />
				<?php } else { ?>
					<input type="checkbox" name="tgi_bookmark_setting[allowed_post_types][]" value="<?php echo $post_type->name; // WPCS: XSS ok. ?>" />
				<?php } ?>
				<label><?php echo esc_html( $post_type->label ); ?></label>
			</div>
			<?php
		}
	}

	/**
	 * Print Guest display bookmark button field
	 */
	public function print_guest_display_bookmark_btn_field() {
		echo '<select name="tgi_bookmark_setting[guest_display_bookmark_btn]">';
		if ( isset( $this->options['guest_display_bookmark_btn'] ) && '1' === $this->options['guest_display_bookmark_btn'] ) {
			echo '<option value="1" selected>' . esc_html__( 'Yes', 'tgi-wpml-bookmark' ) . '</option>';
			echo '<option value="0">' . esc_html__( 'No', 'tgi-wpml-bookmark' ) . '</option>';
		} else {
			echo '<option value="1">' . esc_html__( 'Yes', 'tgi-wpml-bookmark' ) . '</option>';
			echo '<option value="0" selected>' . esc_html__( 'No', 'tgi-wpml-bookmark' ) . '</option>';
		}
		echo '</select>';
	}
}

if ( is_admin() ) {
	// Initialize.
	$tgi_wpml_bookmark_admin_setting = new TGI_WPML_Bookmark_Admin_Setting();
	unset( $tgi_wpml_bookmark_admin_setting );
}
