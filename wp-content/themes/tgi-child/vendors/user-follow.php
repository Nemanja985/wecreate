<?php
function user_follow() {
	ob_start();
    // include_once('vendors/follow.php');
    $current_user = wp_get_current_user();
    $followers = get_followers($current_user->ID);
    $following = get_following($current_user->ID);
    $ff_array = [];
    foreach($following as $ff){
        array_push($ff_array, $ff->following_id);
    }
    ?>  
    <div class="user-follow-container">
        <?php foreach($followers as $f): ?>
            <!-- $user_info = get_userdata(1); -->
            <div class="row-container">
                <a href="<?php echo get_author_posts_url($f->user_id); ?>">
                    <div class="user-avatar">
                        <?php echo get_avatar($f->user_id, 80); ?>
                    </div>
                </a>
                <div class="follow-name"><?php echo get_the_author_meta('display_name', $f->user_id);?></div>
                <div id="follow-btn-<?php echo $f->id; ?>" class="follow-btn-col">
                    <?php if(in_array($f->user_id, $ff_array)){ ?>
                        <div class="following-btn">Following</div>
                    <?php }else{ ?>
                        <div class="follow-btn profile-follow-btn" data-id="<?php echo $f->id; ?>" data-userid="<?php echo $f->user_id; ?>">Follow</div>
                    <?php } ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php

    wp_reset_postdata();
    die();
  // ob_start();
}
// ob_end_clean();
add_action( 'wp_ajax_user_follow', 'user_follow');

function user_following() {
    ob_start();
    include_once('vendors/follow.php');
    $current_user = wp_get_current_user();
    $following = get_following($current_user->ID);


    ?>  
    <div class="user-follow-container">
        <?php foreach($following as $f): ?>
            <!-- $user_info = get_userdata(1); -->
            <div id="follow-btn-<?php echo $f->id; ?>" class="row-container">
                
                <div class="user-avatar">
                    <?php echo get_avatar($f->following_id, 80); ?>
                </div>
                <div class="follow-name"><?php echo get_the_author_meta('display_name', $f->following_id);?></div>
                <div class="follow-btn-col">
                    <div class="follow-btn unfollow-btn" data-userid="<?php echo $f->id; ?>">Unfollow</div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php

    wp_reset_postdata();
    die();
  // ob_start();
}
// ob_end_clean();
add_action( 'wp_ajax_user_following', 'user_following');
