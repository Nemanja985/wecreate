<?php
function follow() {

	$follow_id = $_POST['follow_id'];
  	if (is_user_logged_in()) {

	    global $wpdb;
	    $user_id = get_current_user_id();
	    if($follow_id == $user_id){
	    	echo "0";
	    	die();
	    }
	    if(!check_if_following($follow_id)){

		    $wpdb->query($wpdb->prepare(
		      "INSERT INTO {$wpdb->prefix}follow
		        ( user_id, following_id )
		        VALUES ( %d, %d)",
		        array(
		          $user_id,
		          $follow_id,
		          
		        )
		    ));

		    if($wpdb->last_error){
		      echo "0";
		    }else{
		      echo "1";
		    }
		}else{
			echo "0";
		}
  	}

  die();
}
add_action( 'wp_ajax_follow', 'follow');

function unfollow() {
	global $wpdb;
	$follow_id = $_POST['follow_id'];
	$user_id = get_current_user_id();

  	if(is_user_logged_in()){
	    $wpdb->get_results ( "
	      DELETE 
	          FROM  {$wpdb->prefix}follow
	              WHERE id = ' " . $follow_id . " ' AND user_id = ' " . $user_id . " '
	      " );
	    if($wpdb->last_error){
	      echo "0";
	    }else{
	      echo "1";
	    }
 	}

  die();
}


function delete_follow($post_id) {
   global $wpdb;
	// $follow_id = $_POST['follow_id'];
	// $user_id = get_current_user_id();

  	if(is_user_logged_in()){
	    $wpdb->get_results ( "
	      DELETE 
	          FROM  {$wpdb->prefix}follow
	              WHERE following_id = ' " . $post_id . " '
	      " );
	    if($wpdb->last_error){
	      echo "0";
	    }else{
	      echo "1";
	    }
 	}

  die();
  // ob_start();
}
// ob_end_clean();
add_action( 'wp_ajax_delete_follow', 'delete_follow');


// ob_end_clean();
add_action( 'wp_ajax_unfollow', 'unfollow');

function check_if_following($following_id){
	 global $wpdb;
	 $result = $wpdb->get_results ( "
      SELECT id
          FROM  {$wpdb->prefix}follow
              WHERE user_id = ' " . get_current_user_id() . " ' AND following_id = ' " . $following_id . " '
      " );
	if($result){
		return true;
	}else{
		return false;
	}
}

function count_followers($author_id){
	 global $wpdb;
	 $result = $wpdb->get_results ( "
      SELECT *
          FROM  {$wpdb->prefix}follow
              WHERE following_id = ' " . $author_id . " '
      " );
	return count($result);
}
function count_following($author_id){
	 global $wpdb;
	 $result = $wpdb->get_results ( "
      SELECT *
          FROM  {$wpdb->prefix}follow
              WHERE user_id = ' " . $author_id . " '
      " );
	return count($result);
}
function get_followers($user_id){
	 global $wpdb;
	 $result = $wpdb->get_results ( "
      SELECT *
          FROM  {$wpdb->prefix}follow
              WHERE following_id = ' " . $user_id . " '
      " );
	return $result;
}
function get_following($user_id){
	 global $wpdb;
	 $result = $wpdb->get_results ( "
      SELECT *
          FROM  {$wpdb->prefix}follow
              WHERE user_id = ' " . $user_id . " '
      " );
	// return object_to_array($result);
	 return $result;
}

function object_to_array($data) {

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
