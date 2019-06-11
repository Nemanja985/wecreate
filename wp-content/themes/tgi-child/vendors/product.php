<?php 

function our_products(){
	$args = array(
		'post_type' => 'product',
		'numberposts' => -1,
		'meta_key' => 'accessory',
		'meta_value' => 0
	);
	$posts = get_posts($args);
	
	if($posts):
		$position = 'left';
		foreach($posts as $post): ?>
			<?php if($position == "left"): ?>
			
				<div class="product-row">
					<div class="product-row-left">
						
							<div class="product-image">
								<a href="<?php echo get_permalink($post->ID);?>">
		                  			 <?php echo get_the_post_thumbnail($post->ID); ?>
		                  		</a>
				              </div>
					
					</div>
					<div class="product-row-right">
						
							<div class="product-title"><h2><?php echo $post->post_title; ?></h2></div>
							<div class="product-text"><?php echo get_the_excerpt($post->ID); ?></div>
							<a href="<?php echo get_permalink($post->ID);?>">
								<div class="product-btn">View Details</div>
							</a>
						
					</div>
				</div>
			<?php else: ?>	
				<div class="product-row mob-reverse">
					<div class="product-row-left">
						
							<div class="product-title"><h2><?php echo $post->post_title; ?></h2></div>
							<div class="product-text"><?php echo get_the_excerpt($post->ID); ?></div>
							<a href="<?php echo get_permalink($post->ID);?>">
								<div class="product-btn">View Details</div>
							</a>
					
					</div>
					<div class="product-row-right">
						
							<div class="product-image">
								 <?php echo get_the_post_thumbnail($post->ID); ?>
							</div>
						
					</div>
				</div>
				<?php endif; ?>
			
		
			<?php if($position == "left"){
				$position = "right";
			}else{
				$position = "left";
			}?>

		<?php endforeach; ?>
		<div class="clear-div"></div>
		<?php
	endif;
}

add_shortcode('our_products', 'our_products');

function product_accessory($atts){
	$items = -1;
	if(!empty($args['items'])){
		$items = $args['items'];
	}
	$args = array(
		'post_type' => 'product',
		'numberposts' => $items,
		'meta_key' => 'accessory',
		'meta_value' => true
	);
	$posts = get_posts($args);
	// var_dump($posts);
	if($posts):
		$position = 'left';
		?>
		<div class="product-ac-row">

		<?php foreach($posts as $post): ?>
	
			<div class="product-ac-col">
				<div class="product-ac-image">
					<a href="<?php echo get_permalink($post->ID);?>">
              			 <?php echo get_the_post_thumbnail($post->ID); ?>
              		</a>
				</div>
				<div class="product-ac-title"><h2><?php echo $post->post_title; ?></h2></div>

			</div>
		<?php endforeach; ?>
		</div>
		<div class="clear-div"></div>
		<?php
	endif; 
}
add_shortcode('product_accessory', 'product_accessory');


