<?php

function hand_picked($args){
	$items = -1;
	if(!empty($args['items'])){
		$items = $args['items'];
	}
	$args = array(
		'post_type' => 'recipe',
		'numberposts' => $items,
		'meta_key' => 'hand_picked',
		'meta_value' => true
	);
	$posts = get_posts($args);

	if($posts): ?>
		<div id="hand-picked-slider" class="hand-picked">
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
	                <div class="tgi-icon-icon-fire-black"><span><?php echo get_field('nutrients_calories', $post->ID); ?></span></div>
	                </div>

	               <div class="user-recipe-action">
	                
	    
	              </div>
	          </div>
	        
	   		<?php endforeach; ?>
	   	</div>

	<?php endif; 

}
add_shortcode('hand_picked', 'hand_picked');

function hand_picked_page($args){
	$items = -1;
	if(!empty($args['items'])){
		$items = $args['items'];
	}
	$args = array(
		'post_type' => 'recipe',
		'numberposts' => $items,
		'meta_key' => 'hand_picked',
		'meta_value' => true
	);
	$posts = get_posts($args);

	if($posts): ?>
		<div class="hand-picked">
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
	                <div class="tgi-icon-icon-fire-black"><span><?php echo get_field('nutrients_calories', $post->ID); ?></span></div>
	                </div>

	               <div class="user-recipe-action">
	                
	    
	              </div>
	          </div>
	        
	   		<?php endforeach; ?>
	   	</div>

	<?php endif; 

}
add_shortcode('hand_picked_page', 'hand_picked_page');
