<?php
/**
 * TGI Ingredient
 *
 * @package TGI\Ingredient
 */

/**
 * Plugin Name: TGI Ingredient
 * Plugin URI: http://tgitech.net/
 * Description: Extra ingredient setting
 * Version: 1.0.0
 * Author: Jason Yip
 * Author URI: http://tgitech.net/
 * License: GPLv2
 */

namespace TGI\Ingredient;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'TGI_INGREDIENT_VERSION', '1.0.0' );
define( 'TGI_INGREDIENT_DIR', dirname( __FILE__ ) );
define( 'TGI_INGREDIENT_URL', plugin_dir_url( __FILE__ ) );

require_once TGI_INGREDIENT_DIR . '/admin/class-tgi-ingredient-admin-setting.php';

/**
 * Class TGI_Ingredient
 */
class TGI_Ingredient
{
	/**
	 * TGI_Ingredient constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
		if ( function_exists( 'icl_object_id' ) && ! get_option( 'tgi_ingredients_version' ) ) {
			register_activation_hook( __FILE__, array( $this, 'tgi_ingredients_db_install' ) );
		}
	}

	/**
	 * Initialize the plugin
	 */
	public function init() {
		// Check if WPML module is installed.
		if ( function_exists( 'icl_object_id' ) ) {
			if ( get_option( 'tgi_ingredients_version' ) && ! get_option( 'tgi_ingredients_data_version' ) ) {
				$this->tgi_ingredients_db_install_data();
			}
			add_action( 'save_post', array( $this, 'save_last_updated_time' ), 1000 );
			add_action( 'edited_terms', array( $this, 'term_save_last_updated_time' ), 10, 2 );
		} else {
			add_action( 'admin_notices', array( $this, 'notice_no_wpml' ) );
		}
	}

	/**
	 * Install the database table
	 */
	public function tgi_ingredients_db_install() {
		global $wpdb;

		$table_name = 'tgi_ingredient_update';
		$foreign_table_name = $wpdb->prefix . 'posts';

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE `$table_name` (
			`ingredient_id` BIGINT(20) UNSIGNED NOT NULL,
			`last_updated_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
			PRIMARY KEY (`ingredient_id`),
			CONSTRAINT `TGI_INGREDIENT_UPDATE_WP_POSTS_INGREDIENT_ID` FOREIGN KEY (`ingredient_id`) REFERENCES `$foreign_table_name` (`ID`) ON UPDATE CASCADE ON DELETE CASCADE
		) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );

		add_option( 'tgi_ingredients_version', TGI_INGREDIENT_VERSION );
	}

	/**
	 * Install the initial database data
	 */
	public function tgi_ingredients_db_install_data() {
		global $wpdb;
		global $sitepress;

		$table_name = 'tgi_ingredient_update';

		// switch to the english language.
		$current_language = $sitepress->get_current_language();
		$sitepress->switch_lang( 'en' );

		$ingredients = get_posts(
			[
				'numberposts'      => -1,
				'post_type'        => 'ingredient',
				'suppress_filters' => false,
			]
		);

		$current_datetime    = gmdate( 'Y-m-d H:i:s' );
		$ingredients_values  = [];
		$query_place_holders = [];
		foreach ( $ingredients as $ingredient ) {
			array_push( $ingredients_values, $ingredient->ID, $current_datetime );
			$query_place_holders[] = "('%d', '%s')";
		}

		// switch back to current language.
		$sitepress->switch_lang( $current_language );

		$query  = "INSERT INTO $table_name VALUES ";
		$query .= implode( ', ', $query_place_holders );
		$wpdb->query( $wpdb->prepare($query, $ingredients_values) );

		add_option( 'tgi_ingredients_data_version', TGI_INGREDIENT_VERSION );
	}


	/**
	 * Update the ingredient last updated time
	 *
	 * @param int $post_id Recipe post ID.
	 */
	public function save_last_updated_time( $post_id ) {
		$post_type = get_post_type( $post_id );
		if ( 'ingredient' !== $post_type ) {
			return;
		}
		$ingredient_id            = $post_id;
		$trid                     = null;
		$ingredient_language_info = apply_filters(
			'wpml_element_language_details',
			null,
			[
				'element_id'   => $post_id,
				'element_type' => 'post_ingredient',
			]
		);

		if ( $ingredient_language_info ) {
			$trid = $ingredient_language_info->trid;
		} elseif ( isset( $_POST['trid'] ) ) {
			$trid = (int) $_POST['trid'];
		} else {
			return;
		}

		// Get the English ingredient ID.
		if ( ! $ingredient_language_info || 'en' !== $ingredient_language_info->language_code ) {
			$translations = apply_filters( 'wpml_get_element_translations', null, $trid );
			if ( isset( $translations['en'] ) ) {
				if ( $translations['en']->element_id ) {
					$ingredient_id = (int) $translations['en']->element_id;
				}
			} else {
				return;
			}
		}

		global $wpdb;
		$wpdb->replace(
			'tgi_ingredient_update',
			[
				'ingredient_id'     => $ingredient_id,
				'last_updated_time' => gmdate( 'Y-m-d H:i:s' ),
			],
			[
				'%d',
				'%s',
			]
		);

		// Update child ingredient.
		if ( 'en' === $ingredient_language_info->language_code ) {
			$child_ingredient_ids = $this->get_child_ingredients( $ingredient_id );
			if ( $child_ingredient_ids ) {
				$this->update_ingredients_last_updated_time( $child_ingredient_ids );
			}
		}
	}

	/**
	 * Get child ingredients
	 *
	 * @param int $ingredient_id Ingredient ID.
	 * @return int[]
	 */
	protected function get_child_ingredients( $ingredient_id ) {
		global $sitepress;

		// switch to the english language.
		$sitepress->switch_lang( 'en' );

		$args = [
			'post_type'      => 'ingredient',
			'fields'         => 'ids',
			'posts_per_page' => -1,
			'meta_key'       => 'parent_ingredient',
			'meta_value'     => $ingredient_id,
		];

		$the_query            = new \WP_Query( $args );
		$child_ingredient_ids = $the_query->posts;
		wp_reset_postdata();

		// switch back to current language.
		$sitepress->switch_lang( ICL_LANGUAGE_CODE );

		return $child_ingredient_ids;
	}

	/**
	 * Batch update the last updated time of given ingredients
	 *
	 * @param int[] $ingredient_ids Ingredient IDs.
	 */
	protected function update_ingredients_last_updated_time( $ingredient_ids ) {
		if ( ! $ingredient_ids ) {
			return;
		}
		$ingredient_ids_text = implode( ',', $ingredient_ids );
		global $wpdb;
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE `tgi_ingredient_update` SET `last_updated_time` = %s WHERE `ingredient_id` IN ($ingredient_ids_text)",
				gmdate( 'Y-m-d H:i:s' )
			)
		);
	}

	/**
	 * Update Ingredients' last updated time after ingredient category save.
	 * The IDs of the English versions of the ingredients belong to the category will be used.
	 * Also, the child ingredients of the above ingredients found will be updated.
	 *
	 * @param int    $term_id Taxonomy Term ID.
	 * @param string $taxonomy Taxonomy Type.
	 */
	public function term_save_last_updated_time( $term_id, $taxonomy ) {
		if ( 'ingredient_category' !== $taxonomy ) {
			return;
		}

		$args = [
			'post_type'      => 'ingredient',
			'fields'         => 'ids',
			'posts_per_page' => -1,
			'tax_query'      => [
				[
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    => $term_id,
				],
			],
		];

		$ingredient_ids = [];
		$the_query      = new \WP_Query( $args );
		if ( $the_query->posts ) {
			foreach ( $the_query->posts as $post_id ) {
				$ingredient_id = $post_id;

				$ingredient_language_info = apply_filters(
					'wpml_element_language_details',
					null,
					[
						'element_id'   => $post_id,
						'element_type' => 'post_ingredient',
					]
				);

				// Get the English ingredient ID.
				if ( 'en' === $ingredient_language_info->source_language_code ) {
					$translations = apply_filters( 'wpml_get_element_translations', null, $ingredient_language_info->trid );
					if ( isset( $translations['en'] ) ) {
						$ingredient_id = (int) $translations['en']->element_id;
					} else {
						continue;
					}
				}

				$ingredient_ids[] = $ingredient_id;

				$child_ingredient_ids = $this->get_child_ingredients( $ingredient_id );
				if ( $child_ingredient_ids ) {
					$ingredient_ids = array_merge( $ingredient_ids, $child_ingredient_ids );
				}
			}
			wp_reset_postdata();
			array_unique( $ingredient_ids );
			$this->update_ingredients_last_updated_time( $ingredient_ids );
		}
	}

	/**
	 * Display Admin Notice if the WPML plugin is not activated
	 */
	public function notice_no_wpml() {
		?>
		<div class="error wpml-admin-notice wpml-st-inactive wpml-inactive">
			<p><?php esc_html_e( 'Please activate WPML Multilingual CMS to have TGI Ingredient working.', 'tgi-ingredient' ); ?></p>
		</div>
		<?php
	}
}

// Initialize.
$tgi_ingredient = new TGI_Ingredient();
unset( $tgi_ingredient );