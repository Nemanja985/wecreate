<?php
function save_post() {
    //if 1 new; if 0 update

    $current_user = wp_get_current_user();
    $action_type = $_POST['action_type'];
    $post_id = $_POST['current_id'];
    $nonce = $_POST['nonce'];

    if($post_id>0){
        if($action_type){
            $my_post = array(
                'ID'                => $post_id,
                'post_title'        => trim($_POST['title']),
                'post_status'       => $action_type,
                'post_type'         => 'recipe',
                // 'post_author'    => $current_user->ID,
                'page_template'     => 'recipe.php'
            );
        }else{
            $my_post = array(
                'ID'                => $post_id,
                'post_title'        => trim($_POST['title']),
                // 'post_status'    => 'draft',
                'post_type'         => 'recipe',
                // 'post_author'    => $current_user->ID,
                'page_template'     => 'recipe.php'
            );
        }
        wp_update_post( $my_post );                       
        echo $post_id;

       
    }else{

         $post_id = wp_insert_post( array(
            'post_title'        => trim($_POST['title']),
            'post_status'       => 'draft',
            'post_type'         => 'recipe',
            'post_author'       => $current_user->ID,
            'page_template'          => 'recipe.php'
        ) );
        echo $post_id;
       
    }

    wp_set_object_terms( $post_id, $_POST['complexity'], 'complexity' );
    wp_set_object_terms( $post_id, $_POST['category'], 'category' );
    wp_set_object_terms( $post_id, $_POST['device'], 'device_type' );
    do_action( 'acf_post' , $post_id );

    die();
    
}
add_action( 'wp_ajax_save_post', 'save_post');
add_action( 'wp_ajax_nopriv_save_post', 'save_post');


add_action('acf_post', 'save_custom_field', 10);

function save_custom_field($post_id){
    $step_data = json_decode(stripslashes($_POST['step_groups']));
    $step_groups = convert_object_to_array($step_data);
    $ing_data = json_decode(stripslashes($_POST['ingredients']));
    $ing = convert_object_to_array($ing_data);
    $source_videos_data = json_decode(stripslashes($_POST['source_videos']));
    $source_videos = convert_object_to_array($source_videos_data);

    $postdata = Array(
        // 'category' => $_POST['category'],

        'description' => $_POST['description'],
        'nutrients_calories' => $_POST['calories'],
        'nutrients_protein' => $_POST['protein'],
        'nutrients_carbohydrate' => $_POST['carbohydrate'],
        'nutrients_fat' => $_POST['fat'],
        'servings' => $_POST['servings'],
        'total_duration' => $_POST['total_duration'],
    );
    foreach( $postdata as $key => $value ) {
        update_post_meta( $post_id, $key, $value );
    }
  
    update_field( 'ingredients', $ing, $post_id );
    update_field( 'step_groups', $step_groups, $post_id );
    update_field( 'source_videos', $source_videos, $post_id );
     // $post_id;
}
function convert_object_to_array($data) {

    if (is_object($data)) {
        $data = get_object_vars($data);
    }

    if (is_array($data)) {
        return array_map(__FUNCTION__, $data);
    }
    else {
        return $data;
    }
}
function save_ingredient(){
    $post_id = $_POST['post_id'];

    if(current_user_can('administrator')){
        $ing_id = wp_insert_post( array(
            'post_title'        => trim($_POST['ing_name']),
            'post_status'       => 'publish',
            'post_type'         => 'ingredient',
            'post_author'       => $current_user->ID
        ) );
        if($ing_id){
            echo "1";
        }else{
            echo "0";
        }
        
    }else{
        echo "0";
    }
    die();
}
add_action( 'wp_ajax_save_ingredient', 'save_ingredient');

function autoload_ing(){
    $txt = $_POST['txt'];
    $args = array (
        'post_type' => 'ingredient', // your post type
        's' => $txt,
        'posts_per_page' => -1 // grab all the posts
        // 'meta_key' => 'ingredients',
    );

    $posts = get_posts($args);

    foreach ($posts as $post):
        echo '<div class="ing-text">' . $post->post_title . '</div>';
    endforeach;
    die();
}


add_action( 'wp_ajax_autoload_ing', 'autoload_ing');

function delete_post(){
    $post_id = $_POST['post_id'];
    if(current_user_can('delete_post', $post_id)){
        if(wp_delete_post( $post_id )){
            echo "1";
        }else{
            echo "0";
        }
    }else{
        echo "0";
    }
    die();
}

function hand_pick(){
    $post_id = $_POST['post_id'];
    if(current_user_can('administrator')){
       
        if(update_field( 'hand_picked', true, $post_id )){
            echo "1";
        }else{
            echo "0";
        }
    }else{
        echo "0";
    }
    die();
}

add_action( 'wp_ajax_hand_pick', 'hand_pick');

add_action( 'wp_ajax_delete_post', 'delete_post');

function reject_post(){
    $post_id = $_POST['post_id'];
    $msg =  trim($_POST['msg']);
    if(current_user_can('administrator')){
        $my_post = array(
            'ID'           => $post_id,
            'post_status'  => 'rejected'
        );
        if(wp_update_post( $my_post )){
             update_field( 'admin_notes', $msg, $post_id );
            echo "1";
        }else{
            echo "0";
        }
    }else{
        echo "0";
    }
    die();
}

add_action( 'wp_ajax_reject_post', 'reject_post');