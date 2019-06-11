<?php
/**
 * TGI Ingredient Admin Setting Page
 *
 * @package TGI\Ingredient
 * @subpackage TGI\Ingredient\admin
 */
namespace TGI\Ingredient\admin;

/**
 * Class TGI_Ingredient_Admin_Setting
 */
class TGI_Ingredient_Admin_Setting {
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
		add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts_admin' ) );
	}

	/**
	 * Add options page
	 */
	public function add_plugin_page() {
		add_options_page(
			'Ingredient Settings',
			'Ingredient Settings',
			'manage_options',
			'tgi-ingredient-setting',
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Options page callback
	 */
	public function create_admin_page() {
		$this->options = get_option( 'tgi_ingredient_setting' );
		?>
		<div class="wrap">
			<h1>Ingredient Settings</h1>
			<form method="post" action="options.php">
				<?php
				// This prints out all hidden setting fields.
				settings_fields( 'tgi-ingredient-option-group' );
				do_settings_sections( 'tgi-ingredient-setting' );
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
			'tgi-ingredient-option-group',
			'tgi_ingredient_setting',
			array()
		);

		add_settings_section(
			'setting_general',
			'General Settings',
			array( $this, 'print_general_section_info' ),
			'tgi-ingredient-setting'
		);

		add_settings_field(
			'default_icon',
			'Default Icon',
			array( $this, 'print_default_icon_field' ),
			'tgi-ingredient-setting',
			'setting_general'
		);
	}

	/**
	 * Print the General Section text
	 */
	public function print_general_section_info() {
	}

	/**
	 * Print allowed post types field
	 */
	public function print_default_icon_field() {
		$this->image_uploader( 'default_icon', 50, 50 );
	}

	/**
	 * Image Uploader
	 *
	 * @param string $name image field name.
	 * @param int    $width width.
	 * @param int    $height height.
	 */
	public function image_uploader( $name, $width, $height ) {
		$options       = $this->options;
		$default_image = TGI_INGREDIENT_URL . 'assets/img/noimage.png';

		if ( ! empty( $options[ $name ] ) ) {
			$image_attributes = wp_get_attachment_image_src( $options[ $name ], array( $width, $height ) );
			$src              = $image_attributes[0];
			$value            = $options[ $name ];
		} else {
			$src   = $default_image;
			$value = '';
		}

		$text = __( 'Upload', 'tgi-ingredient' );

		// Print HTML field
		echo '
        <div class="upload">
            <img data-src="' . $default_image . '" src="' . $src . '" width="' . $width . 'px" height="' . $height . 'px" />
            <div>
                <input type="hidden" name="tgi_ingredient_setting[' . $name . ']" value="' . $value . '" />
                <button type="submit" class="upload_image_button button">' . $text . '</button>
                <button type="submit" class="remove_image_button button">&times;</button>
            </div>
        </div>
    	';
	}

	/**
	 * Load scripts and style sheet for ingredient settings page
	 *
	 * @param string $hook hook page.
	 */
	public function load_scripts_admin( $hook ) {
		if ( 'settings_page_tgi-ingredient-setting' !== $hook ) {
			return;
		}
		wp_enqueue_media();
		wp_enqueue_script( 'uploader-js', plugins_url( 'assets/js/uploader.js', __DIR__ ), [], '1.0.0', false );
	}
}

if ( is_admin() ) {
	// Initialize.
	$tgi_wpml_ingredient_admin_setting = new TGI_Ingredient_Admin_Setting();
	unset( $tgi_wpml_ingredient_admin_setting );
}
