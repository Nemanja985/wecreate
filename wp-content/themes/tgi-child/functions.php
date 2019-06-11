<?php
define('MORDRIL_THEME_NAME', 'HNI');
define('WECREATE_CMS_NAME', 'WECREATE-CMS');

add_action('wp_enqueue_scripts', 'salient_child_enqueue_styles');
function salient_child_enqueue_styles()
{
    wp_enqueue_script('croppie-js', get_stylesheet_directory_uri() . '/assets/js/jquery.cropit.js', array('jquery'), '1.0', true);
    wp_enqueue_script('theme_js', get_stylesheet_directory_uri() . '/assets/js/main.js', array('jquery'), '1.0', true);
    wp_enqueue_script('login', get_stylesheet_directory_uri() . '/assets/js/login.js', array('jquery'), '1.0', true);
    wp_enqueue_script('videoframe_js', get_stylesheet_directory_uri() . '/assets/js/videoframe.js', array('jquery'), '1.0', true);
    wp_enqueue_script('waypoint_js', get_stylesheet_directory_uri() . '/assets/js/jquery.waypoints.min.js', array('jquery'), '1.0', true);
    wp_enqueue_script('jquery-ui', get_stylesheet_directory_uri() . '/assets/js/jquery-ui.min.js', array('jquery'), '1.0', true);
    wp_enqueue_script('jquery-simulate', get_stylesheet_directory_uri() . '/assets/js/jquery.simulate.js', array('jquery'), '1.0', true);
    wp_enqueue_script('slick-ui', get_stylesheet_directory_uri() . '/assets/js/slick.min.js', array('jquery'), '1.0', true);

    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css', array('font-awesome'));

    wp_register_script('admin-ajax-js', get_stylesheet_directory_uri() . '/assets/js/admin-ajax.js', array('jquery'), '', true);
    wp_enqueue_script('admin-ajax-js');
    wp_localize_script('admin-ajax-js', 'ajax_admin_params', array('admin_ajax_url' => admin_url('admin-ajax.php')));
    wp_register_script('video-ajax-js', get_stylesheet_directory_uri() . '/assets/js/video.js', array('jquery'), '', true);
    wp_enqueue_script('video-ajax-js');

    wp_localize_script('video-ajax-js', 'video_admin_params', array('video_ajax_url' => admin_url('admin-ajax.php')));

    wp_register_script('post-ajax-js', get_stylesheet_directory_uri() . '/assets/js/post.js', array('jquery'), '', true);
    wp_enqueue_script('post-ajax-js');

    wp_localize_script('post-ajax-js', 'ajax_post_params', array('ajax_url' => admin_url('admin-ajax.php')));

    if (is_rtl()) {
        wp_enqueue_style('salient-rtl', get_template_directory_uri() . '/rtl.css', array(), '1', 'screen');
    }

    wp_register_style('child-style', trailingslashit(get_stylesheet_directory_uri()) . 'dist/styles/main.css', null, '20180917.001');
    wp_enqueue_style('child-style');
    
}

include_once trailingslashit(get_stylesheet_directory()) . 'vendors/sign-up-campaign.php';
include_once trailingslashit(get_stylesheet_directory()) . 'vendors/wecreate-login-box.php';
include_once trailingslashit(get_stylesheet_directory()) . 'vendors/wecreate-create-recipe.php';
include_once trailingslashit(get_stylesheet_directory()) . 'vendors/recipe-list.php';
include_once trailingslashit(get_stylesheet_directory()) . 'vendors/product.php';
include_once trailingslashit(get_stylesheet_directory()) . 'vendors/hand-picked.php';
include_once trailingslashit(get_stylesheet_directory()) . 'vendors/chef-endorsement.php';
include_once trailingslashit(get_stylesheet_directory()) . 'vendors/user-profile.php';
include_once trailingslashit(get_stylesheet_directory()) . 'vendors/user-profile-edit.php';
include_once trailingslashit(get_stylesheet_directory()) . 'vendors/user-device.php';
include_once trailingslashit(get_stylesheet_directory()) . 'vendors/user-recipes.php';
include_once trailingslashit(get_stylesheet_directory()) . 'vendors/post-recipe.php';
include_once trailingslashit(get_stylesheet_directory()) . 'vendors/user-reviews.php';
include_once trailingslashit(get_stylesheet_directory()) . 'vendors/follow.php';
include_once trailingslashit(get_stylesheet_directory()) . 'vendors/user-follow.php';
include_once trailingslashit(get_stylesheet_directory()) . 'vendors/like.php';
include_once trailingslashit(get_stylesheet_directory()) . 'vendors/admin-recipe-list.php';
include_once trailingslashit(get_stylesheet_directory()) . 'vendors/upload-video.php';
include_once trailingslashit(get_stylesheet_directory()) . 'vendors/author-recipe-ajax.php';
include_once trailingslashit(get_stylesheet_directory()) . 'vendors/frontpage.php';



// add_action('wp_footer', function () {
//     echo <<<end
// <style>
// @media (min-width: 690px) {
// .span_5 {
//     width: 18.4% !important;
// }
// </style>
// end;
// });

//OPTION in Admin Page
if (function_exists('acf_add_options_page')) {

    acf_add_options_page();
    // acf_add_options_sub_page('General');
}
add_filter('show_admin_bar', '__return_false');

function recipe_post_redirect()
{
    if (is_post_type_archive('recipe')) {
        wp_redirect(home_url() . '/recipe-list/', 301); 
        exit();
    }
}
add_action('template_redirect', 'recipe_post_redirect');

//Page Slug Body Class
function add_slug_body_class($classes)
{
    global $post;
    if (isset($post)) {
        $categories = get_the_category($post->ID);
        foreach ($categories as $cat) {
            $category_slug = $cat->slug;
        }
        $classes[] = $post->post_type . '-' . $post->post_name;
        $classes[] = $post->post_type . '-' . $category_slug;
    }
    return $classes;
}
add_filter('body_class', 'add_slug_body_class');

// This theme uses wp_nav_menu() in two locations.
register_nav_menus(array(
    'footer_menu' => __('footer Navigation', 'twentyten'),
));
