<?php
function upload_video(){
    if(wp_verify_nonce( $_POST['security'], 'ajax_file_nonce' )){
        $post_id = $_POST['post_id'];
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );
        require_once(ABSPATH . 'wp-admin/includes/admin.php');
        $uploadedfile = $_FILES['file'];
        $upload_overrides = array('test_form' => false);
        $attachment_id = media_handle_sideload( $uploadedfile, $post_id );
        $response = array();
        if ( is_wp_error( $attachment_id ) ) {
            
           $response['error'] = 'error';
        }else {
            $attachment_url = wp_get_attachment_url($attachment_id);
            $response['url'] = $attachment_url;
            $response['id'] = $attachment_id;
        }
        echo json_encode($response, JSON_UNESCAPED_SLASHES);
    }
    die();

}
add_action( 'wp_ajax_upload_video','upload_video' );
add_action( 'wp_ajax_nopriv_upload_video','upload_video' );

function delete_video(){
    $current_user = wp_get_current_user();
    $post_id = $_POST['post_id'];
    $author = $_POST['author'];
    if($current_user->ID == $author or current_user_can('administrator')){
        if ( false === wp_delete_attachment( $post_id, true) ){
            echo "0";
        }else{
            echo "1";
        }
    }
    die();

}
add_action( 'wp_ajax_delete_video','delete_video' );
add_action( 'wp_ajax_nopriv_delete_video','delete_video' );

function upload_image(){
    $upload_dir = wp_upload_dir();
    $upload_path = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;
    $post_id = $_POST['post_id'];
    $orig_attachment_id = media_handle_upload( 'orig_image', $post_id );
    $img = $_POST['img'];
    $img = str_replace('data:image/jpeg;base64,', '', $img);
    $img = str_replace(' ', '+', $img);

    $decoded          = base64_decode($img) ;
    $filename = $_POST['fname'];

    $hashed_filename = md5( $filename . microtime() ) . '_' . $filename;
    $image_upload = file_put_contents( $upload_path . $hashed_filename, $decoded );

    //HANDLE UPLOADED FILE
    if( !function_exists( 'wp_handle_sideload' ) ) {
      require_once( ABSPATH . 'wp-admin/includes/file.php' );
    }

    // Without that I'm getting a debug error!?
    if( !function_exists( 'wp_get_current_user' ) ) {
      require_once( ABSPATH . 'wp-includes/pluggable.php' );
    }
   
    $file             = array();
    $file['error']    = '';
    $file['tmp_name'] = $upload_path . $hashed_filename;
    $file['name']     = $filename;
    $file['type']     = 'image/jpeg';
    $file['size']     = filesize( $upload_path . $hashed_filename );

    $attachment_id = media_handle_sideload( $file, $post_id );
    $attachment_url = wp_get_attachment_url($attachment_id);
     if ( is_wp_error( $attachment_id ) ) {
           $response['error'] = 'error';
        }else {
            $response['url'] = $attachment_url;
            $response['msg'] = 'Photo uploaded successfully!';
            update_field( 'thumbnail_portrait', $attachment_id, $post_id );
            update_field( 'details_image_landscape', $orig_attachment_id, $post_id );
        }
        echo json_encode($response, JSON_UNESCAPED_SLASHES);
    die();
}
add_action( 'wp_ajax_upload_image','upload_image' );

