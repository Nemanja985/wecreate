<?php
function user_reviews() {
    ob_start();
    // include_once('vendors/review.php');
  
?>  
    <div class="user-reviews-container">
        <div class="account-heading">
            <span id="user_review_given" data-func="given" class="user-review-tab active">Given (<?php echo count_given_reviews();?>)</span>
            <span id="user_review_recieved" data-func="received" class="user-review-tab">Reviews (<?php echo count_reviews();?>)</span>
        </div>
    </div>
    <div id="review-items-container">
       <?php get_review_items(); ?>
    </div>
    <?php

  wp_reset_postdata();
  die();
  // ob_start();
}


add_action( 'wp_ajax_user_reviews', 'user_reviews');


function get_review_items(){
    $func = $_POST['func'];
    if($func == 'received'){
        $reviews = get_reviews();
        // $reviews = $reviews['results'];
    }else{
        $reviews = get_given_reviews();
    }
    
    foreach($reviews as $review): ?>
        <?php $post = get_post($review['assigned_to']); ?>
       
        <div class="review-item">
           <div class="recipe-image-container">
                <a href = "<?php echo get_permalink($post->ID); ?>">
                    <div class="review-image">
                        <?php $image = get_field('thumbnail_portrait', $review['assigned_to']); ?>
                        <?php if($image){ ?>
                           <img src ="<?php echo $image['url'] ?>">
                        <?php }else{ ?>
                            <img src ="<?php echo get_field('recipe_list_photo_placeholder', 'option'); ?>">
                        <?php } ?>
                    </div>
                </a>
            </div>
            <div class="review-content-container">
                <div class="review-img-mob">
                    <a href = "<?php echo get_permalink($post->ID); ?>">
                        <div class="review-image">
                            <?php $image = get_field('thumbnail_portrait', $review['assigned_to']); ?>
                            <?php if($image){ ?>
                               <img src ="<?php echo $image['url'] ?>">
                            <?php }else{ ?>
                                <img src ="<?php echo get_field('recipe_list_photo_placeholder', 'option'); ?>">
                            <?php } ?>
                        </div>
                    </a>
                </div>
                <div class="title-container">
                    <?php echo $post->post_title; ?>
                </div>
                <div class="recipe-author-container">
                   by: <span class="recipe-author">
                        <a href="<?php echo get_author_posts_url( $post->post_author ); ?>">
                            <?php echo get_the_author_meta('display_name', $post->post_author);?>
                        </a>
                    </span>
                    
                </div>
                <div class="review-content">
                    <div class="review-item <?php echo $class; ?>">
                            <div class="review-avatar">
                                <div class="review-avatar-img">
                                    <?php echo get_avatar( $review['email'], 80); ?>
                                    
                                </div>
                            </div>
                            <div class="review-content">
                                <div class="review-name">
                                    <?php echo $review['author']; ?>
                                </div>  
                                <div class="review-star">

                                    <?php 
                                    for($i=0; $i<$review['rating']; $i++){ ?>
                                        <div class="stars"></div>
                                    <?php } ?>

                                </div>
                                <div class="review-date">
                                    <?php echo $review['date']; ?>
                                </div>
                                <div class="review-text">
                                    <?php echo $review['content']; ?>
                                </div>
                            </div>
                            <div class="clear-div"></div>
                        </div>
                </div>
            </div>
            <div class="clear-div"></div>
        </div>
    <?php endforeach; ?>
    
    <?php
    die();
}
add_action( 'wp_ajax_get_review_items', 'get_review_items');

function get_reviews($user_id = ""){
    if(!$user_id){
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;
    }
    
    $args = array(
        'author'  =>  $user_id,
        'post_status' => 'publish',
        'post_type' => 'recipe',
        'posts_per_page' => -1
    );

    $rec_post = get_posts($args);
    $rev = array();
    foreach($rec_post as $rec){
        $reviews = glsr_get_reviews([
            'assigned_to' => $rec->ID
        ]);
        $review = convert_object_to_array($reviews);
        if(count($review['results']) > 0){
            foreach($review['results'] as $item){
                 array_push($rev, $item);
            }
        }
    }
    return $rev;
}

function get_given_reviews(){
    $current_user = wp_get_current_user();
    $reviews = glsr_get_reviews();
    $reviews = convert_object_to_array($reviews);
    // var_dump($reviews);
    $revs = [];
    for($i=0; $i < count($reviews['results']); $i++){
        if($reviews['results'][$i]['user_id'] == $current_user->ID){
            $post_id = $reviews['results'][$i]['assigned_to'];
            $post = get_post($post_id);

            if($post){
                array_push($revs, $reviews['results'][$i]);
            }
        }
    }

    return $revs;


}
function count_reviews(){
    $reviews = get_reviews();
    return count($reviews);
}
 function count_given_reviews(){
    $reviews = get_given_reviews();
    // var_dump($reviews);
    return count($reviews);
}