<?php
function homepage_video_header(){
global $post;
$video = get_field('video_header', $post->ID);
// var_dump($video);
?>
<div class="video-header-container">
	<div class="main-video-container">
		<div class="video-wrapper">
			<video id="main-video" src="<?php echo $video[0]['video']; ?>"></video>
			<div class="video-title"><h2><?php echo $video[0]['title']; ?></h2></div>
			<div class="home-video-play-icon"></div>
		</div>
		<div class="video-title2"><h2><?php echo $video[0]['title']; ?></h2></div>
		<div class="video-header-selection">
			<?php 
			$count = 1;
			$class = 'video-item';
			// $video = array_shift($video);
			// var_dump($video);
			?>
			<?php foreach ($video as $vid): ?>
				<?php if($count == 1): ?>
					<?php $class = 'video-item active'; ?>
				<?php else: ?>
					<?php $class = 'video-item'; ?>

				<?php endif; ?>
				
				<div class="<?php echo $class; ?>" data-src="<?php echo $vid['video']; ?>" data-title="<?php echo $vid['title']; ?>">
					<video src="<?php echo $vid['video']; ?>"></video>
					<div class="selection-title"><?php echo $vid['title']; ?></div>
				</div>
			<?php $count++; ?>
			<?php endforeach; ?>
		</div>
		
	</div>
</div>
<div class="clear-div"></div>
<?php
wp_reset_query();

}
add_shortcode( 'homepage_video_header', 'homepage_video_header');

function homepage_latest_recipe(){
global $post;
$showposts = get_field('number_of_latest_recipe', $post->ID);
$args = array(
		'post_type' => 'recipe',
		'showposts' =>	$showposts
	);
$posts = get_posts($args);
?>
<div id="latest-recipe-slider" class="latest-recipe-slider">
	<?php foreach($posts as $post): ?>
		<?php $image = get_post_meta($post->ID, 'thumbnail_portrait', true); ?>
		<div class="latest-recipe-item-wrapper">
	        <div class="latest-recipe-item">
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
	    </div>
	<?php endforeach; ?>
</div>


<?php
wp_reset_query();

}
add_shortcode( 'homepage_latest_recipe', 'homepage_latest_recipe');

function pick_of_the_week(){
global $post;
$image = get_field('week_photo', $post->ID);
$post_id = get_field('pick_of_the_week', $post->ID);
$p = get_post($post_id);
?>

<div class="pick-recipe-wrapper" style="background: url('<?php echo $image; ?>')">
	
	<div class="pick-text-col">
		<h4>Pick of the week</h4>
		<h1><?php echo $p->post_title; ?></h1>
		<p><?php echo get_post_meta($p->ID, 'description', true); ?></p>
		<div class="recipe-details">
	        <div class="tgi-icon-icon-clock-black"><span><?php echo get_field('total_duration', $p->ID); ?> Mins</span></div>
            <div class="tgi-icon-icon-level-black"><span><?php echo strip_tags(get_the_term_list($p->ID, 'complexity')); ?></span></div>
            <div class="tgi-icon-icon-fire-black"><span><?php echo get_field('nutrients_calories', $p->ID); ?></span></span></div>
      	</div>
      	<a href="<?php echo get_permalink($p->ID);?>"><div class="try-recipe-btn">Try Recipe</div></a>
	</div>

</div>


<?php
wp_reset_query();

}
add_shortcode( 'pick_of_the_week', 'pick_of_the_week');
?>