<?php 
/*
 * Template Name: Recipe
 * Template Post Type: post, recipe
 */
get_header(); 

global $post;
// include_once('vendors/follow.php');
// include_once('vendors/like.php');
?>

<div id="video-player-overlay" class="video-player-overlay">

	<div class="video-player-wrapper  col-reverse">
		<div class="video-collapse"></div>
		<div class="video-player-col-left">
			
			<div id="video-player-close" class="video-player-close"><span class="close-arrow">&#x276E;</span> Close</div>
			<div class="video-player-content">
				<div class="video-player-recipe-title"></div>
				<div class="video-player-recipe-step"></div>
				<div class="video-player-recipe-desc"></div>
				<div class="video-player-recipe-ings-title">Ingredients</div>
				<div class="video-player-recipe-ings"></div>
			</div>
			<div class="video-player-step-nav">
				<div class="video-player-step-prev" data-prev="0"><span class="prev-arrow">&#x276E;</span> Previous Step</div>
				<div class="video-player-step-next" data-next="1">Next Step <span class="prev-arrow">&#x276F;</span></div>
			</div>
		</div>
		<div class="video-player-col-right">
			<div class="video-player">
				<video id="pop-video-player" autoplay></video>
				<div class="pop-video-play-icon"></div>
			</div>
			<div class="video-player-btns">
				<div class="video-pause"></div>
				<div class="video-expand"></div>

			</div>
		</div>
	</div>
</div>
<!-- <div class="recipe-details-header">
	<video autoplay loop>
		<source src="<?php //echo the_field('recipe_video_header', 'option'); ?>">
	</video>
</div> -->
<div class="recipe-post-container" style="background: #fff;">
	<div class="recipe-col-left">
		<h3><?php echo the_title(); ?></h3>
		<div class="recipe-details">
            <div class="tgi-icon-icon-clock-black"><span><?php echo get_field('total_duration', $post->ID); ?> Mins</span></div>
            <div class="tgi-icon-icon-level-black"><span><?php echo strip_tags(get_the_term_list($post->ID, 'complexity')); ?></span></div>
            <div class="tgi-icon-icon-fire-black"><span><?php echo get_field('nutrients_calories', $post->ID); ?></span></div>
		</div>
		<div class="recipe-social-row">
			<?php if (is_user_logged_in()) { ?>
				<?php if(check_if_liked($post->ID)) { ?>
					<div class="recipe-saved"><span>Liked</span></div>
				<?php }else{ ?>
					<div id="like-btn" class="recipe-save" data-id="<?php echo $post->ID; ?>"><span>Like</span></div>
					<div id="liked-btn" class="recipe-saved" data-id="<?php echo $post->ID; ?>"><span>Liked</span></div>
				<?php } ?>
			<?php }else{ ?>
				<div class="recipe-saved" data-id="<?php echo $post->ID; ?>"><span>Like</span></div>
			<?php } ?>


			<div class="recipe-social">
				<span class="share">Share:</span>
				<span><a href="https://instagram.com"><i class="fa fa-instagram"></i></a></span>
				<span><a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" target="_blank"><i class="fa fa-facebook"></i></a></span>

				<span><a href="https://twitter.com/home?status=<?php the_permalink(); ?>" target="_blank"><i class="fa fa-twitter"></i></a></span>
			</div>
		</div>
		<div class="clear"></div>
		
		<div id="profile-container2" class="profile-container">
			<div class="profile-row1">

				<a href="<?php echo get_author_posts_url( $post->post_author ); ?>">
					<div class="profile-img">
						<?php echo get_avatar( the_author(), 90 ); ?>
					</div>
				</a>
				<div class="profile-name-col">
					<a href="<?php echo get_author_posts_url( $post->post_author ); ?>">
						<h5><?php echo get_the_author_meta('display_name', $post->post_author);?></h5>
					</a>
					<div class="profile-occupation"><?php echo get_the_author_meta('occupation', $post->post_author);?></div>
					<?php if(is_user_logged_in()){ ?>
						
						<?php if(get_current_user_id() == $post->post_author){ ?>
							<div class="follow-btn-disabled" data-id="<?php echo $post->post_author;?>">Follow</div>
						<?php }else{ ?>
							<?php if(check_if_following($post->post_author)){ ?>
								<div id="followed-btn" class="followed-btn followed-btn-active" data-id="<?php echo $post->post_author;?>">Following</div>
								
							<?php }else{ ?>
								<div id="follow-btn" class="follow-btn" data-id="<?php echo $post->post_author;?>">Follow</div>
								<div id="followed-btn" class="followed-btn" data-id="<?php echo $post->post_author;?>">Following</div>
							<?php } ?>
						<?php } ?>
					<?php }else{ ?>	
						<div class="follow-btn-disabled" data-id="<?php echo $post->post_author;?>">Follow</div>
					<?php } ?>
				</div>
			</div>
			<div class="profile-row2">
				<div class="prof-stats">
					<div class="prof-stats-count"><?php echo count_following($post->post_author);?></div>
					<div class="prof-stats-text">Followers</div>
				</div>
				<div class="prof-stats">
					<div class="prof-stats-count"><?php echo count_user_posts( $post->post_author, "recipe"); ?></div>
					<div class="prof-stats-text">Recipes</div>
				</div>
				<div class="prof-stats">
					<div class="prof-stats-count"><?php echo count(get_reviews($post->post_author)); ?></div>
					<div class="prof-stats-text">Reviews</div>
				</div>
			</div>
			<div class="profile-row3">
				<div class="prof-desc">
					<p><?php echo get_the_author_meta('description', $post->post_author);?></p>
				</div>
				
			</div>
			<div class="profile-row4">
				<div class="full-profile-text">
					<a href="<?php echo get_author_posts_url( $post->post_author ); ?>">See Full Profile</a>
				</div>
				
			</div>
			
		</div>
		<div class="recipe-about">
			<div class="recipe-headings">About</div>
			<p><?php echo the_field('description'); ?></p>
			
		</div>
		<?php $landscape_image = get_field('details_image_landscape'); ?>

		<?php if($landscape_image): ?>
			<div class="recipe-photo">
				<img src ="<?php echo $landscape_image; ?>">
			</div>
		<?php endif; ?>

		<div class="recipe-ingredients-heading">
			<div class="recipe-headings">Nutrition Per Serving</div>
			
		</div>
		
		<div class="recipe-ingredients-row">
			
		    <ul>
		        <li>
		        	<div class="ing-name">Calories</div>
		        	<div class="ing-quantity"><?php echo the_field('nutrients_calories'); ?></div>
		        	<div class="clear"></div>
		        </li>
		        <li>
		        	<div class="ing-name">Protein</div>
		        	<div class="ing-quantity"><?php echo the_field('nutrients_protein'); ?></div>
		        	<div class="clear"></div>
		        </li>
		        <li>
		        	<div class="ing-name">Carbohydrate</div>
		        	<div class="ing-quantity"><?php echo the_field('nutrients_carbohydrate'); ?></div>
		        	<div class="clear"></div>
		        </li>
		        <li>
		        	<div class="ing-name">Fat</div>
		        	<div class="ing-quantity"><?php echo the_field('nutrients_fat'); ?></div>
		        	<div class="clear"></div>
		        </li>
		    </ul>
		</div>
		<div class="recipe-ingredients-heading steps-serving">
			<div class="recipe-headings">Ingredients</div>
			<div class="recipe-sub-heading">
				<div class="serving-title">Serving</div>
				<div class="serving-wrapper">
					<span class="serving-minus" data-add="-1"></span>
					<span class="serving-amount" id="form-serving-value"><?php echo the_field('servings'); ?></span>
					<span class="serving-plus" data-add="1"></span>
				</div>
			</div>
		</div>
		<div class="recipe-ingredients-row">
			
			<?php if( have_rows('ingredients') ): ?>

			    <ul>

			    <?php while( have_rows('ingredients') ): the_row(); ?>

			        <li>
			        	<div class="ing-name"><?php the_sub_field('name'); ?></div>
			        	<div class="ing-quantity"><span id="ing-qty"><?php the_sub_field('amount'); ?></span> <?php the_sub_field('unit'); ?></div>
			        	<div class="clear"></div>
			        </li>

			    <?php endwhile; ?>

			    </ul>

			<?php endif; ?>
		
		</div>
		<div class="recipe-ingredients-heading">
			<div class="recipe-headings step-row-heading">Steps</div>
			<?php
				$total_min_duration = get_field('total_duration');
				$hours = floor($total_min_duration / 60);
				$mins = $total_min_duration % 60;
				$total_duration = str_pad($hours, 2, '0', STR_PAD_LEFT) . ':' . str_pad($mins, 2, '0', STR_PAD_LEFT) . ':00';
			?>
			<div class="recipe-sub-heading">Total durations: <?php echo $total_duration; ?></div>
		</div>
		<div class="step-row">
		
		<?php if( have_rows('step_groups') ): ?>
				
			<ul id="recipe-details-step-item">
				<?php $step_count = 0; ?>
			    <?php while( have_rows('step_groups') ): the_row(); ?>
			    	<?php $step_count++; ?>
			    	<?php $main_step = get_sub_field('step_parent'); ?>
			    	<?php $step_number = get_sub_field('step_number'); ?>
			    	<?php if($main_step == $step_number): 
						$step_class = "main-step";
					else:
						$step_class = "sub-step sub-step-".$main_step;
			    	endif; ?>
				    	<?php $duration = get_sub_field('duration'); ?>
					
				        <li class="<?php echo $step_class; ?>" data-parent = "<?php echo get_sub_field('step_parent'); ?>"
					        	data-step = "<?php echo get_sub_field('step_number'); ?>">
							<div class="step-drop-down" data-step="<?php echo $main_step; ?>"></div>
							<?php $step_title = get_sub_field('name'); ?>
							
				        	<div class="step-title">
				        		<?php if($step_title){ 
				        			echo $step_title;
				        		}else{
				        			echo "-";
				        		}

				        		 ?>

				        	</div>

				        	<div id = "step-video-play-<?php echo $step_count; ?>" class="step-video-play" 
				        		data-seq = "<?php echo $step_count; ?>"
					        	data-src="<?php echo get_sub_field('video_landscape')['url']; ?>" 	 
					        	data-title="<?php echo get_sub_field('name'); ?>"
					        	data-step_number="<?php echo str_replace('-', '.', $step_number); ?>"
					        	data-desc="
									<?php if( have_rows('steps') ): ?>
									
											<?php while( have_rows('steps') ): the_row(); ?>
												<?php echo get_sub_field('description'); ?>
												
										  	<?php endwhile; ?>
									
									<?php endif; ?>
					        	"
								data-ings="
								<?php if( have_rows('ingredients') ): ?>
									<ul>
										<?php while( have_rows('ingredients') ): the_row(); ?>

											<li class='step-ings'><?php echo get_sub_field('amount') . ' ' . get_sub_field('unit') . ' ' . get_sub_field('name'); ?></li>
									  	<?php endwhile; ?>
									</ul>
								<?php endif; ?>
								">
								
							</div>
				        	<div class="step-count"></div>	
				        	<div class="step-duration">
				        		<?php if($duration){
				        			echo gmdate('H:i:s', get_sub_field('duration'));
				        		 }else{
				        		 	echo "00:00:00";
				        		 } ?>
				        		</div>
				        	<div class="clear-div"></div>
				        </li>
					
			    <?php endwhile; ?>
				
			</ul>

			<?php endif; ?>
		

		</div>
		<div class="expand-step-wrapper">
			<div class="expand-btn">Expand all steps</div>
		</div>
		<div class="recipe-ingredients-heading">

			<div class="recipe-headings">Reviews</div>
			<div class="recipe-sub-heading">
				<div class="serving-wrapper">
					<span class="write-review">Write a review</span>
				</div>
			</div>
		</div>
		<div class="review-ingredients-row">
			<div class="review-form">		
				<?php echo do_shortcode('[site_reviews_form hide="email,terms,name,title" assign_to="'.$post->ID.'"]'); ?>

			</div>
			
			<div class="review-list">
				
				

					<?php 
					$reviews = glsr_get_reviews([
						'assigned_to' => $post->ID,
					]); ?>
					<?php //var_dump($reviews); ?>
					<?php $reviews = convert_object_to_array($reviews); ?>
					<?php $review_count = count($reviews['results']); ?>
					<?php $class = ""; ?>
					<?php $review_item_count = 1; ?>
					<?php foreach( $reviews['results'] as $review ): ?>
						<?php if($review_item_count > 3){$class="inactive";} ?>
						<div class="review-item <?php echo $class; ?>">
							<div class="review-avatar">
								<div class="review-avatar-img">
									<?php $grav = get_avatar( $review['email'], 80); ?>
									<?php echo $grav; ?>
								</div>
							</div>
							<div class="review-content">
								<div class="review-name">
									<?php echo $review['author']; ?>
								</div>	
								<div id="review-star1" class="review-star">

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
								<div id="review-star2" class="review-star">

									<?php 
									for($i=0; $i<$review['rating']; $i++){ ?>
										<div class="stars"></div>
									<?php } ?>

								</div>
							</div>
							<div class="clear-div"></div>
						</div>
						<?php $review_item_count++; ?>
					<?php endforeach; ?>
					<?php if($review_count > 3): ?>
						<div class="expand-review-wrapper">
							<div class="expand-review">Show all <?php echo $review_count; ?> reviews</div>
						</div>
					<?php endif; ?>
			</div>
	
		</div>
	</div>
	<div class="recipe-col-right">
		<div id="profile-container1" class="profile-container">
			<div class="profile-row1">

				<a href="<?php echo get_author_posts_url( $post->post_author ); ?>">
					<div class="profile-img">
						<?php echo get_avatar( the_author(), 90 ); ?>
					</div>
				</a>
				<div class="profile-name-col">
					<a href="<?php echo get_author_posts_url( $post->post_author ); ?>">
						<h5><?php echo get_the_author_meta('display_name', $post->post_author);?></h5>
					</a>
					<div class="profile-occupation"><?php echo get_the_author_meta('occupation', $post->post_author);?></div>
					<?php if(is_user_logged_in()){ ?>
						
						<?php if(get_current_user_id() == $post->post_author){ ?>
							<div class="follow-btn-disabled" data-id="<?php echo $post->post_author;?>">Follow</div>
						<?php }else{ ?>
							<?php if(check_if_following($post->post_author)){ ?>
								<div id="followed-btn" class="followed-btn followed-btn-active" data-id="<?php echo $post->post_author;?>">Following</div>
								
							<?php }else{ ?>
								<div id="follow-btn" class="follow-btn" data-id="<?php echo $post->post_author;?>">Follow</div>
								<div id="followed-btn" class="followed-btn" data-id="<?php echo $post->post_author;?>">Following</div>
							<?php } ?>
						<?php } ?>
					<?php }else{ ?>	
						<div class="follow-btn-disabled" data-id="<?php echo $post->post_author;?>">Follow</div>
					<?php } ?>
				</div>
			</div>
			<div class="profile-row2">
				<div class="prof-stats">
					<div class="prof-stats-count"><?php echo count_following($post->post_author);?></div>
					<div class="prof-stats-text">Followers</div>
				</div>
				<div class="prof-stats">
					<div class="prof-stats-count"><?php echo count_user_posts( $post->post_author, "recipe"); ?></div>
					<div class="prof-stats-text">Recipes</div>
				</div>
				<div class="prof-stats">
					<div class="prof-stats-count"><?php echo count(get_reviews($post->post_author)); ?></div>
					<div class="prof-stats-text">Reviews</div>
				</div>
			</div>
			<div class="profile-row3">
				<div class="prof-desc">
					<p><?php echo get_the_author_meta('description', $post->post_author);?></p>
				</div>
				
			</div>
			<div class="profile-row4">
				<div class="full-profile-text">
					<a href="<?php echo get_author_posts_url( $post->post_author ); ?>">See Full Profile</a>
				</div>
				
			</div>
			
		</div>
		
	</div>
	
</div>
<div id="hand-picked-recipe-section">
	<?php echo do_shortcode("[wecreate_contentblock name='hand-picked-recipes']"); ?>
</div>
<div class="what-cooking-today">
	<?php //echo do_shortcode("[wecreate_contentblock name='whats-cooking-today']"); ?>
</div>
<div id="join-us">
	<?php //echo do_shortcode("[wecreate_ig_image_list]"); ?>
</div>
<?php get_footer(); ?>