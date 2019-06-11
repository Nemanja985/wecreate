<?php
function like() {

	$recipe_id = $_POST['recipe_id'];
  	if (is_user_logged_in()) {

	    global $wpdb;
	    $user_id = get_current_user_id();
	    // if($recipe_id == $user_id){
	    // 	echo "0";
	    // 	die();
	    // }
	    // if(!check_if_following($follow_id)){

		    $wpdb->query($wpdb->prepare(
		      "INSERT INTO {$wpdb->prefix}like
		        ( user_id, recipe_id )
		        VALUES ( %d, %d)",
		        array(
		          $user_id,
		          $recipe_id,
		          
		        )
		    ));

		    if($wpdb->last_error){
		      echo "0";
		    }else{
		      echo "1";
		    }
		// }else{
		// 	echo "0";
		// }
  	}

  die();
}
add_action( 'wp_ajax_like', 'like');

function get_liked_post_array(){

	$user_id = get_current_user_id();
	global $wpdb;
	 $result = $wpdb->get_results ( "
      SELECT *
          FROM  {$wpdb->prefix}like
              WHERE user_id = ' " . $user_id . " '
      " );
	$liked_ids = [];
   
    foreach ($result as $res) {
      // if(!in_array($res->recipe_id, $liked_ids)){
      	array_push($liked_ids, $res->recipe_id);
      // }
    }
    return $liked_ids;
}
function check_if_liked($recipe_id){
	 global $wpdb;
	 $result = $wpdb->get_results ( "
      SELECT id
          FROM  {$wpdb->prefix}like
              WHERE user_id = ' " . get_current_user_id() . " ' AND recipe_id = ' " . $recipe_id . " '
      " );
	if($result){
		return true;
	}else{
		return false;
	}
}

function delete_liked($post_id){
	 global $wpdb;
	 if(is_user_logged_in()){
	    $wpdb->get_results ( "
	      DELETE 
	          FROM  {$wpdb->prefix}like
	              WHERE recipe_id = ' " . $post_id . " '
	      " );
	    if($wpdb->last_error){
	      echo "0";
	    }else{
	      echo "1";
	    }
 	}
}
