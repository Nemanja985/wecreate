<?php 
function user_profile_ui($atts){
	// include_once('vendors/follow.php');
	if(!is_user_logged_in()):
		echo '<center>You need to login to view this page</center>';
	else:
	?>
	<div id="spinner"><img src="<?php echo get_site_url(); ?>/wp-content/themes/tgi-child/dist/images/spinner.svg"></div>
	<div class="profile-image-row">
		<div class="profile-img">
			<?php
			global $current_user;
	      	get_currentuserinfo(); 
	       	echo get_avatar( $current_user->ID, 180 );
		    ?>
		   
		</div>
		<div class="avatar-wrapper">
			<div class="camera"></div>
		</div>
		<?php do_shortcode('[avatar]'); ?>
	</div>
	<?php $username = get_the_author_meta('first_name', $current_user->ID); ?>
	<?php $lastname = get_the_author_meta('last_name', $current_user->ID); ?>
	
	<div class="profile-name"><?php echo $current_user->display_name ?></div>
	<div class="profile-stats">
		<div class="profile-stats-cols">
			<span class="stat-number"><?php echo count_followers($current_user->ID); ?></span>
			<span class="stat-text">Followers</span>
		</div>
		<div class="profile-stats-cols">
			<span class="stat-number"><?php echo count_user_posts( $current_user->ID, "recipe"); ?></span>
			<span class="stat-text">Recipes</span>
		</div>
		<div class="profile-stats-cols">
			
			<span class="stat-number"><?php echo count_reviews(); ?></span>
			<span class="stat-text">Reviews</span>
		</div>
	</div>
	<div class="clear-div"></div>
	<div id="profile-menu-desk">	
		<div id="user_profile_content"  data-func="user_profile_content" class="profile-menu-tab activity-menu active">Activity Feed</div>
		<div id="user_recipes" data-func="user_recipes" data-status="publish" class="profile-menu-tab my-recipes-menu">My Recipes</div>
		<div id="user_edit_ajax"  data-func="user_edit_ajax" class="profile-menu-tab my-profile">My Profile</div>
		<div id="user_device"  data-func="user_device" class="profile-menu-tab my-devices">My Devices</div>
		<div id="user_reviews"  data-func="user_reviews" class="profile-menu-tab my-reviews">My Reviews</div>
		<?php if( current_user_can('administrator')): ?>
			<div id="admin_recipe"  data-func="admin_recipes" class="profile-menu-tab admin_recipe_list">Manage Recipes</div>
		<?php endif; ?>
		<a href="<?php echo get_site_url(); ?>/wp-login.php?action=logout"><div id="user_logout"  class="logout-menu">Logout</div></a>
	</div>
	<div id="profile-menu-mob">	
		<div id="user_profile_content"  data-func="user_profile_content" class="profile-menu-tab activity-menu active scroll-to-content">Activity Feed</div>
		<div id="user_recipes" data-func="user_recipes" data-status="publish" class="profile-menu-tab my-recipes-menu scroll-to-content">My Recipes</div>
		<div id="user_edit_ajax"  data-func="user_edit_ajax" class="profile-menu-tab my-profile scroll-to-content">My Profile</div>
		<div id="user_device"  data-func="user_device" class="profile-menu-tab my-devices scroll-to-content">My Devices</div>
		<div id="user_reviews"  data-func="user_reviews" class="profile-menu-tab my-reviews scroll-to-content">My Reviews</div>
		<?php if( current_user_can('administrator')): ?>
			<div id="admin_recipe"  data-func="admin_recipes" class="profile-menu-tab admin_recipe_list scroll-to-content">Manage Recipes</div>
		<?php endif; ?>
		<a href="<?php echo get_site_url(); ?>/wp-login.php?action=logout"><div id="user_logout"  class="logout-menu">Logout</div></a>
	</div>
<?php
	endif;
}
add_shortcode('user_profile_menu_sc', 'user_profile_ui');

function user_profile_content($atts){
	ob_start();
	if(is_user_logged_in()):
		
	
	?>
	<div id="spinner-mob"><img src="<?php echo get_site_url(); ?>/wp-content/themes/tgi-child/dist/images/spinner.svg"></div>
	<div id="profile-content-container">
		<div class="activity-container">
			<div class="activity-cols">
				<div class="activity-title">Your Notifications</div>
				<div class="col-container">
					<div class="activity-wrapper">
						<div class="activity-item">
							<div class="left-col">
								<img src="<?php echo get_site_url(); ?>/wp-content/themes/tgi-child/dist/images/shawn.jpg">
							</div>
							<div class="right-col">
								<span class="dark-text">Shawn Lui</span> reviewed your recipe <span class="dark-text">Creamy Vegan Banana Pudding</span>
								<div class="timestamp">Just now</div>

							</div>
						</div>
						<div class="activity-item">
							<div class="left-col">
								<img src="<?php echo get_site_url(); ?>/wp-content/themes/tgi-child/dist/images/ann.jpg">
							</div>
							<div class="right-col">
								<span class="dark-text">Anne Kosby</span> started <span class="dark-text">following</span> you
								<div class="timestamp">55 minutes ago</div>

							</div>
						</div>
						<div class="activity-item">
							<div class="left-col">
								<img src="<?php echo get_site_url(); ?>/wp-content/themes/tgi-child/dist/images/shawn.jpg">
							</div>
							<div class="right-col">
								<span class="dark-text">Shawn Lui</span> reviewed your recipe <span class="dark-text">Creamy Vegan Banana Pudding</span>
								<div class="timestamp">Just now</div>

							</div>
						</div>
						<div class="activity-item">
							<div class="left-col">
								<img src="<?php echo get_site_url(); ?>/wp-content/themes/tgi-child/dist/images/ann.jpg">
							</div>
							<div class="right-col">
								<span class="dark-text">Anne Kosby</span> started <span class="dark-text">following</span> you
								<div class="timestamp">55 minutes ago</div>

							</div>
						</div>
						<div class="activity-item">
							<div class="left-col">
								<img src="<?php echo get_site_url(); ?>/wp-content/themes/tgi-child/dist/images/shawn.jpg">
							</div>
							<div class="right-col">
								<span class="dark-text">Shawn Lui</span> reviewed your recipe <span class="dark-text">Creamy Vegan Banana Pudding</span>
								<div class="timestamp">Just now</div>

							</div>
						</div>
						<div class="activity-item">
							<div class="left-col">
								<img src="<?php echo get_site_url(); ?>/wp-content/themes/tgi-child/dist/images/ann.jpg">
							</div>
							<div class="right-col">
								<span class="dark-text">Anne Kosby</span> started <span class="dark-text">following</span> you
								<div class="timestamp">55 minutes ago</div>

							</div>
						</div>
						<div class="activity-item">
							<div class="left-col">
								<img src="<?php echo get_site_url(); ?>/wp-content/themes/tgi-child/dist/images/shawn.jpg">
							</div>
							<div class="right-col">
								<span class="dark-text">Shawn Lui</span> reviewed your recipe <span class="dark-text">Creamy Vegan Banana Pudding</span>
								<div class="timestamp">Just now</div>

							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="activity-cols">
				<div class="activity-title">Latest Updates</div>
				<div class="col-container">
					<div class="activity-wrapper">
						<div class="activity-item update-item">
							<div class="left-col">
								<img src="<?php echo get_site_url(); ?>/wp-content/themes/tgi-child/dist/images/potato.jpg">
							</div>
							<div class="right-col">
								<span class="dark-text">Christine Chuoo</span> added a new recipe <span class="dark-text">Creamy Vegan Banana Pudding</span>
								
							</div>
						</div>
						<div class="activity-item update-item">
							<div class="left-col">
								<img src="<?php echo get_site_url(); ?>/wp-content/themes/tgi-child/dist/images/abocado.jpg">
							</div>
							<div class="right-col">
								<span class="dark-text">Mike Richards</span> edited a recip that you starred <span class="dark-text">Chicken Avocado
								Quesedillas</span>
								
							</div>
						</div>
						<div class="activity-item update-item">
							<div class="left-col">
								<img src="<?php echo get_site_url(); ?>/wp-content/themes/tgi-child/dist/images/potato.jpg">
							</div>
							<div class="right-col">
								<span class="dark-text">Shawn Lui</span> reviewed your recipe <span class="dark-text">Creamy Vegan Banana Pudding</span>
								
							</div>
						</div>
						<div class="activity-item update-item">
							<div class="left-col">
								<img src="<?php echo get_site_url(); ?>/wp-content/themes/tgi-child/dist/images/abocado.jpg">
							</div>
							<div class="right-col">
								<span class="dark-text">Mike Richards</span> reviewed your recipe <span class="dark-text">Chicken Avocado
								Quesedillas</span>
								
							</div>
						</div>
						<div class="activity-item update-item">
							<div class="left-col">
								<img src="<?php echo get_site_url(); ?>/wp-content/themes/tgi-child/dist/images/potato.jpg">
							</div>
							<div class="right-col">
								<span class="dark-text">Shawn Lui</span> reviewed your recipe <span class="dark-text">Creamy Vegan Banana Pudding</span>
								
							</div>
						</div>
						<div class="activity-item update-item">
							<div class="left-col">
								<img src="<?php echo get_site_url(); ?>/wp-content/themes/tgi-child/dist/images/abocado.jpg">
							</div>
							<div class="right-col">
								<span class="dark-text">Mike Richards</span> reviewed your recipe <span class="dark-text">Chicken Avocado
								Quesedillas</span>
								
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="activity-cols">
				<div class="activity-title">Liked Recipes</div>
				<div class="col-container">
					<div class="sort">
						<select>
							<option value="latest">Sort by Latest Added</option>
							<option value="difficulty">Sort by Difficulty</option>
						</select>

					</div>
					<?php include_once('like.php'); ?>
					<?php $liked_recipes = get_liked_post_array(); ?>
					<?php
						 $args = array(
						      'post_type' => 'recipe',
						      'post__in' => $liked_recipes
						  );
						  $posts = get_posts($args);
					?>
					<div class="clear-div"></div>
					<div class="activity-wrapper">
						<?php foreach($posts as $p): ?>
						
						<div class="activity-item starred-item">
							<?php $terms = get_the_terms( $p->ID, 'complexity' ); ?>
							<?php $image = get_post_meta($p->ID, 'thumbnail_portrait', true); ?>
								<a href="<?php echo get_permalink($p->ID);?>">
									<div class="like-image">
										<?php echo wp_get_attachment_image( $image, 'full'); ?>
									</div>
									<div class="starred-title"><?php echo $p->post_title; ?></div>
									<div class="recipe-details">
										<span class="timer">1:00</span>
										<span class="dificulty"><?php echo strip_tags(get_the_term_list($p->ID, 'complexity')); ?></span>
										<span class="temperature">500</span>
									</div>
								</a>
						</div>

						<?php endforeach; ?>

					</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
endif;
 // ob_end_clean();
}
add_shortcode('user_profile_content_sc', 'user_profile_content');
add_action( 'wp_ajax_user_profile_content', 'user_profile_content');