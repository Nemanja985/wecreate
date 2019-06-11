<?php
function get_device_details($dev = ""){
    $dev_args = array(
        'taxonomy'      => 'device_type', // taxonomy name
        'hide_empty'    => true,
        'fields'        => 'all',
        'name__like'    => $dev
    ); 

    $devices = get_terms( $dev_args );
    return $devices;
}
function user_device() {
  ob_start();
  $current_user = wp_get_current_user();
  $args = array(
    'post_type' => 'recipe',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'author'     => $current_user->ID
  );
  $posts = get_posts($args);
  // var_dump($posts);
  $dev_array = [];
  $dev_title = [];

  foreach($posts as $post){
    $dev_text = strip_tags(get_the_term_list($post->ID, 'device_type'));
    // echo $dev_text;
    if (!in_array($dev_text, $dev_title)) {
        array_push($dev_title, $dev_text);
        $dev = get_device_details($dev_text);
        array_push($dev_array, $dev);
    }
    
  }
  ?>  
  <div class="user-device-header">
    <!-- <div class="user-device-add-btn">Add a Device</div> -->
  </div>
  <div class="user-device-container">
    <?php foreach ($dev_array as $device): ?>
      <div class="device-items">
        <div class="device-image">
          <img src="<?php echo get_wp_term_image($device[0]->term_taxonomy_id); ?>">
        </div>
        <div class="device-details">
            <div class="device-title"><?php echo $device[0]->name; ?></div>
            <div class="device-description"><?php echo $device[0]->description; ?></div>
        </div>

      </div>
      <div class="clear"></div>
    <?php endforeach; ?>
    </div>
  <?php

  wp_reset_postdata();
  die();
  // ob_start();
}
// ob_end_clean();
add_action( 'wp_ajax_user_device', 'user_device');