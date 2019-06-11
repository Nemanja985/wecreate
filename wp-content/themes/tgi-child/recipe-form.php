<?php /* Template Name: Recipe Front-end Form */ ?>
<?php acf_form_head(); ?>
<?php get_header(); ?>

<div class="container-wrap  no-sidebar">
    <div class="container main-content">

    <?php

    function check_permission($recipe_id) {
        if ( current_user_can( 'edit_others_posts', $recipe_id ) || (get_current_user_id() == get_post_field( 'post_author', $recipe_id )))  {
            return true;
        }
        return new WP_Error( 'access_denied', __( "You are not allowed to edit the recipe." ) );
    }

    $recipe_id = get_query_var('recipe_id');
    $check_permission = check_permission($recipe_id);
    if( $recipe_id && is_wp_error( check_permission($recipe_id) ) ) {
        echo $check_permission->get_error_message();
    } else {
        acf_form(array(
            'post_id' => $recipe_id ? $recipe_id : 'new_post',
            'post_title' => true,
            'post_content' => false,
            'new_post' => array(
                'post_type' => 'recipe'
            ),
            'uploader' => 'basic'
        ));
    }

    ?>

    </div>
</div>

<?php get_footer(); ?>
