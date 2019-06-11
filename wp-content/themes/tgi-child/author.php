<?php 
/*
 * Template Name: Recipe
 * Template Post Type: post, recipe
 */
get_header(); 

$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
// var_dump($curauth);

$args = array(
	'author'  =>  $curauth->ID,
	'post_type'	=> 'recipe',
	'showposts' => 20,
	'paged' => 1
);
$posts = get_posts($args);
?>
<div id="author-page-wrapper" class="author-page-wrapper" data-author="<?php echo $curauth->ID; ?>">
	<div class="author-section1">
		<div class="author-page-container">
			<div id="spinner">
				<img src="<?php echo get_site_url(); ?>/wp-content/themes/tgi-child/dist/images/spinner.svg">
			</div>
			<div class="profile-img-wrapper">
				<div class="profile-img">
					<?php echo get_avatar( $curauth->ID, 180 ); ?>
				   
				</div>
<<<<<<< HEAD
				<div class="author-name-mob"><?php echo $curauth->display_name; ?></div>
				<div class="author-desc-mob">
					<p><?php echo get_the_author_meta('description', $curauth->ID);?></p>
				</div>
=======

>>>>>>> ff0053bd3e9e327d7033aea3b1c3040872a092b6
			</div>
			<div class="author-stat">
				<div class="stat-cols active" data-func="get_author_recipe_ajax"><span class="number"><?php echo count_user_posts( $curauth->ID, "recipe"); ?></span> <span>Recipes</span></div>
				<div class="stat-cols" data-func="author_follower"><span class="number"><?php echo count_followers($curauth->ID); ?></span> <span>Followers</span></div>
				<div class="stat-cols" data-func="get_author_reviews"><span class="number"><?php echo count(get_reviews($curauth->ID)); ?></span> <span>Reviews</span></div>
				<div class="stat-follow">
					<?php if(is_user_logged_in()){ ?>
						
						<?php if(get_current_user_id() == $curauth->ID){ ?>
							<div class="follow-btn-disabled" style="display: none; data-id="<?php echo $post->post_author;?>">Follow</div>
						<?php }else{ ?>
							<?php if(check_if_following($curauth->ID)){ ?>
								<div id="followed-btn" class="followed-btn followed-btn-active" data-id="<?php echo $curauth->ID;?>">Following</div>
								
							<?php }else{ ?>
								<div id="follow-btn" class="follow-btn" data-id="<?php echo $curauth->ID;?>">Follow</div>
								<div id="followed-btn" class="followed-btn" data-id="<?php echo $curauth->ID;?>">Following</div>
							<?php } ?>
						<?php } ?>
					<?php }else{ ?>	
						<div class="follow-btn-disabled" data-id="<?php echo $post->post_author;?>">Login to Follow</div>
					<?php } ?>
				</div>
			</div>
			<div style="clear: both;"></div>
		</div>
			
			
		
	</div>
	<div class="author-section2">
		<div class="author-page-content">
			<div class="col-left">
				<div class="author-name"><?php echo $curauth->display_name; ?></div>
				<div class="author-desc">
					<p><?php echo get_the_author_meta('description', $curauth->ID);?></p>
				</div>
			</div>
			<div class="col-right">
				<div id="author-list-content">
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
					                <div class="tgi-icon-icon-clock-black"><span><?php echo get_field('total_duration', $post->ID); ?> Mins</span></div>
					                <div class="tgi-icon-icon-level-black"><span><?php echo strip_tags(get_the_term_list($post->ID, 'complexity')); ?></span></div>
					                <div class="tgi-icon-icon-fire-black"><span><?php echo get_field('nutrients_calories', $post->ID); ?></span></span></div>
					              </div>

					             <div class="user-recipe-action">
					              
					  
					            </div>
					        </div>
						      
						 <?php endforeach; ?>
					</div>
					
				</div>
				<div id="more-spinner-container" class="more-spinner-container">
					<div class="more-spinner">
						<img src="<?php echo get_site_url(); ?>/wp-content/themes/tgi-child/dist/images/spinner.svg">
					</div>
				</div>
			</div>
			<div style="clear: both;"></div>
		</div>
	</div>
</div>


	
<?php get_footer(); ?>