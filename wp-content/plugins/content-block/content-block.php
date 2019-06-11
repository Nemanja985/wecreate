<?php
/**
 * Plugin Name: Content Block
 * Description: Content block with shortcode, you can recall the content in difference page.
 * Version: 1.0.0
 * Author: Wecreate
 */


/**
 * Custom post for content block 
 */
add_action('init', 'create_custom_post_type', 1000);
function create_custom_post_type(){
  $contentblock_labels = array(
      'name' => 'Content Block',
      'singular_name' => 'Content Block',
      'add_new' => 'Add New',
      'add_new_item' => 'Add New Content Block',
      'edit_item' => 'Edit Content Block',
      'new_item' => 'New Content Block',
      'view_item' => 'View Content Block',
      'search_items' => 'Search Content Block',
      'not_found' =>  'No Content Block found',
      'not_found_in_trash' => 'No Content Block in Trash',
      'parent_item_colon' => ''
  );
  $contentblock_supports = array(
      'title',
      'editor',
      'thumbnail'
  );
  $contentblock_args = array(
      'labels' => $contentblock_labels,
      'public' => true,
      'publicly_queryable' => true,
      'show_ui' => true,
      'show_in_menu' => true,
      'query_var' => true,
      'rewrite' => array( 'slug' => 'contentblock' ),
      'capability_type' => 'post',
      'has_archive' => true,
      'hierarchical' => false,
      'menu_position' => 5,
      'supports' => $contentblock_supports
  );
  register_post_type('contentblock', $contentblock_args);

  function wc_contentblock_table_head( $defaults ) {
    $defaults['shortcode'] = 'Shortcode';
    $defaults['author'] = 'Author';
    return $defaults;
  }
  add_filter('manage_contentblock_posts_columns', 'wc_contentblock_table_head');

  function wc_contentblock_table_content($column_name, $post_id) {
    if ($column_name == 'shortcode') {
      $pattern = "[wecreate_contentblock name='%s']";
      printf($pattern, get_post_field('post_name', $post_id));
    }
  }
  add_action('manage_contentblock_posts_custom_column', 'wc_contentblock_table_content', 10, 2);
}


/**
 * Get Content Block
 */
function wecreate_get_content_block($atts = null) {
  extract(shortcode_atts(array( 'name' => null, ), $atts));
  $args = array(
    'name'        => $name,
    'post_type'   => 'contentblock',
    'post_status' => 'publish',
    'numberposts' => 1
  );
  $_posts = get_posts($args);
  $post_content = '';
  if(!empty($_posts)) {
    $post_content = wpautop($_posts[0]->post_content);
  }
  return do_shortcode($post_content);
}
add_shortcode('wecreate_contentblock','wecreate_get_content_block');

?>