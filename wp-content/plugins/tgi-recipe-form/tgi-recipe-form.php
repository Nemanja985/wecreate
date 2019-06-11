<?php
/**
 * Plugin Name: TGI Frontend Recipe Form
 * Plugin URI: http://tgitech.net/
 * Description: Customize the Frontend Post Form for the custom post type - Recipe based on ACF
 * Version: 1.0.0
 * Author: Jason Yip
 * Author URI: http://tgitech.net/
 * License: GPLv2
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

global $TGI_RECIPE_FORM_VERSION;
$TGI_RECIPE_FORM_VERSION = "1.0.0";

function recipe_form_file_fields( $field ) {
    $hiddenFields = [
        'thumbnail_portrait',
        'thumbnail_landscape',
        'video_portrait',
        'video_landscape',
        'total_duration'
    ];

    if ( ! is_admin() ) {
        if ( in_array($field['_name'], $hiddenFields) ) {
            return false;
        }
    }

    return $field;
}

add_filter('acf/prepare_field', 'recipe_form_file_fields');

function add_query_vars_filter( $vars ){
    $vars[] = "recipe_id";
    return $vars;
}
add_filter( 'query_vars', 'add_query_vars_filter' );

function create_recipe_template_redirect()
{
    if( is_page( 'create-recipe' ) && ! is_user_logged_in() ) {
        wp_redirect( home_url() );
        exit;
    }
}
add_action( 'template_redirect', 'create_recipe_template_redirect' );

function edit_recipe_template_redirect()
{
    if( is_page( 'edit-recipe' ) ) {
        if ( ! is_user_logged_in() ) {
            wp_redirect(home_url());
            exit;
        }

        $recipe_id = get_query_var('recipe_id');
        if ( ! $recipe_id || ! is_integer_c($recipe_id) || ! get_post_status ( $recipe_id ) ) {
            wp_redirect( '/create-recipe' );
            exit;
        }
    }
}
add_action( 'template_redirect', 'edit_recipe_template_redirect' );

function is_integer_c($input){
    return(ctype_digit(strval($input)));
}