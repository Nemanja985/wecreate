<?php 
function chef_endorsement(){
	// echo "test";
	if(!is_user_logged_in()):
		echo '<center>You need to login to view this page</center>';
	else:

		$user_id = get_current_user_id();

		$args=array(
		    'post_type' => 'recipe',
			'post_status' => array(                
	            'publish',                      // - a published post or page.
	            'pending',                      // - post is pending review.
	            'draft'                       // - a post in draft status.            
            ),
		    'posts_per_page' => 0,
		    'author' => $user_id
		);                       

		$posts = get_posts($args);
		
		?>

		<?php foreach ($posts as $post): ?>
			<?php $image = get_post_meta($post->ID, 'thumbnail_portrait', true); ?>
			<div class="recipe-list">
				<a href="<?php echo get_permalink($post->ID); ?>" target="_blank">
					<div class="recipe-img-col">
						<?php if($image){ ?>
			                <?php echo wp_get_attachment_image( $image, 'full'); ?>
		                <?php }else{ ?>
		                  	<img src ="<?php echo get_field('recipe_list_photo_placeholder', 'option'); ?>">
		                <?php } ?>
					</div>
				</a>
				<div class="recipe-details-col">
					<div class="recipe-title"><?php echo $post->post_title; ?></div>
					<div class="recipe-details">
						<span class="timer">1:00</span>
						<span class="dificulty"><?php echo strip_tags(get_the_term_list($post->ID, 'complexity')); ?></span>
						<span class="temperature">500</span>
					</div>
					<div class="recipe-social">
						<span class="share">Share:</span>
						<span><a href="https://instagram.com"><i class="fa fa-instagram"></i></a></span>
						<span><a href="https://facebook.com"><i class="fa fa-facebook"></i></a></span>
						<span><a href="https://twitter.com"><i class="fa fa-twitter"></i></a></span>
					</div>
				</div>
				<div class="recipe-endorsement-col">
					<?php  $pub = $post->post_status; ?>
					<?php if($pub == 'publish'){ ?>
						<div class="endorsement-btn-endorsed">Endorsed</div>
					<?php }else{ ?>
						<div class="endorsement-btn">Pending Endorsement</div>
					<?php } ?>
				</div>
			</div>
		<?php endforeach; ?>
<?php
	endif;
	die();
}
add_action( 'wp_ajax_chef_endorsement', 'chef_endorsement');
add_action('wp_ajax_nopriv_chef_endorsement', 'chef_endorsement');
