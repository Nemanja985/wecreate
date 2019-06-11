<?php

//Auto Load on scroll
function get_author_recipe_autoload(){
	$author_id = $_POST['author'];
	// var_dump($curauth);

	$args = array(
		'author'  =>  $author_id,
		'post_type'	=> 'recipe',
		'showposts' => 20,
		'paged' => $_POST['paged']
	);
	$posts = get_posts($args);
	?>
	<?php foreach($posts as $post): ?>
	        <?php $image = get_post_meta($post->ID, 'thumbnail_portrait', true); ?>
	        <div class="user-recipe-item">
	            <a href="<?php echo get_permalink($post->ID);?>">
	              <div class="recipe-image">
	              	<?php if($image){ ?>
	                   <?php echo wp_get_attachment_image( $image, 'full'); ?>
	                <?php }else{ ?>
	                
	                 	<img src ="<?php echo get_field('recipe_list_photo_placeholder', 'option'); ?>">
	                <?php } ?>
	              </div>
	            </a>
	              <div class="recipe-details">
	               	<div class="user-recipe-item-title"><?php echo $post->post_title; ?></div>
	                <span class="tgi-icon-icon-clock-black"></span>1 hour
	                <span class="tgi-icon-icon-level-black"></span><?php echo strip_tags(get_the_term_list($post->ID, 'complexity')); ?>
	                <span class="tgi-icon-icon-fire-black"></span>500
	              </div>

	             <div class="user-recipe-action">
	              
	  
	            </div>
	        </div>
	      
	 <?php endforeach; ?>
	 <?php
	 die();
}

add_action('wp_ajax_get_author_recipe_autoload', 'get_author_recipe_autoload');
add_action( 'wp_ajax_nopriv_get_author_recipe_autoload', 'get_author_recipe_autoload');

//Tab
function get_author_recipe_ajax(){
	$author_id = $_POST['author'];
	// var_dump($curauth);

	$args = array(
		'author'  =>  $author_id,
		'post_type'	=> 'recipe',
		'showposts' => 20,
		'paged' => $_POST['paged']
	);
	$posts = get_posts($args);
	?>
	<div id="list-container">
	<?php foreach($posts as $post): ?>
	        <?php $image = get_post_meta($post->ID, 'thumbnail_portrait', true); ?>

	        <div class="user-recipe-item">
	            <a href="<?php echo get_permalink($post->ID);?>">
	              <div class="recipe-image">
	              	<?php if($image){ ?>
	                   <?php echo wp_get_attachment_image( $image, 'full'); ?>
	                <?php }else{ ?>
	                
	                 	<img src ="<?php echo get_field('recipe_list_photo_placeholder', 'option'); ?>">
	                <?php } ?>
	              </div>
	            </a>
	              <div class="recipe-details">
	               	<div class="user-recipe-item-title"><?php echo $post->post_title; ?></div>
	                <span class="tgi-icon-icon-clock-black"></span>1 hour
	                <span class="tgi-icon-icon-level-black"></span><?php echo strip_tags(get_the_term_list($post->ID, 'complexity')); ?>
	                <span class="tgi-icon-icon-fire-black"></span>500
	              </div>

	             <div class="user-recipe-action">
	              
	  
	            </div>
	        </div>
	      
	<?php endforeach; ?>
	</div> <!-- list container -->
	<?php
	die();
}

add_action('wp_ajax_get_author_recipe_ajax', 'get_author_recipe_ajax');
add_action( 'wp_ajax_nopriv_get_author_recipe_ajax', 'get_author_recipe_ajax');


function author_follower() {
	ob_start();
    // include_once('vendors/follow.php');
    $current_user = wp_get_current_user();
    $author_id = $_POST['author'];
    $followers = get_followers($author_id);
    $following = get_following($current_user->ID);
    
    if(is_user_logged_in()){
        $ff_array = [];
        foreach($following as $ff){
            array_push($ff_array, $ff->following_id);
        }
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
                <?php if(is_user_logged_in()){ ?>
                    <div class="follow-name"><?php echo get_the_author_meta('display_name', $f->user_id);?></div>
                    <div id="follow-btn-<?php echo $f->id; ?>" class="follow-btn-col">
                        <?php if(in_array($f->user_id, $ff_array)){ ?>
                            <div class="following-btn">Following</div>
                        <?php }else{ ?>
                            <div class="follow-btn profile-follow-btn" data-id="<?php echo $f->id; ?>" data-userid="<?php echo $f->user_id; ?>">Follow</div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        <?php endforeach; ?>
    </div>

    <?php

    wp_reset_postdata();
    die();
}
// ob_end_clean();
add_action( 'wp_ajax_author_follower', 'author_follower');
add_action( 'wp_ajax_nopriv_author_follower', 'author_follower');

function get_author_reviews(){
	$author_id = $_POST['author'];
    $reviews = get_reviews($author_id);
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
<<<<<<< HEAD

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
=======
            <div class="review-content-container">
>>>>>>> ff0053bd3e9e327d7033aea3b1c3040872a092b6
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
add_action( 'wp_ajax_get_author_reviews', 'get_author_reviews');
add_action( 'wp_ajax_nopriv_get_author_reviews', 'get_author_reviews');