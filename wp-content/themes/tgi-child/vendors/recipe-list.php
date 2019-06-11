<?php 
function search_recipe(){
	
	$s = $_POST['q'];
	$q = "";
	if(!empty($s)){
		$q = $s;
	}
	$cat = $_POST['category'];
	$difficulty = $_POST['difficulty'];
	$device = $_POST['device'];
	$tax_query = array(
	  'relation' => 'AND'
	);

	if (!empty($cat)) {
	  $cond = array(
	    'taxonomy' => 'category',
	    'field' => 'slug',
	    'terms' => $cat,
	  );
	  $tax_query[] = $cond;
	}

	if (!empty($difficulty)) {
	  $cond = array(
	    'taxonomy' => 'complexity',
	    'field' => 'slug',
	    'terms' => $difficulty,
	  );
	  $tax_query[] = $cond;
	}
	if (!empty($device)) {
	  $cond = array(
	    'taxonomy' => 'device_type',
	    'field' => 'slug',
	    'terms' => $device,
	  );
	  $tax_query[] = $cond;
	}

	$args = array(
		's' => $q, 
		'post_type' => 'recipe',
		'post_status' => 'publish',
		'paged' => $_POST['paged'],
		'showposts' => 20,
		// 'offset' => 2,
		'tax_query' => $tax_query  

	);

	$posts = get_posts( $args );
	return $posts;
}
function count_recipe(){
	$s = $_POST['q'];
	$q = "";
	if(!empty($s)){
		$q = $s;
	}
	$cat = $_POST['category'];
	$difficulty = $_POST['difficulty'];
	$device = $_POST['device'];
	$tax_query = array(
	  'relation' => 'AND'
	);

	
	if (!empty($cat)) {
	  $cond = array(
	    'taxonomy' => 'category',
	    'field' => 'slug',
	    'terms' => $cat,
	  );
	  $tax_query[] = $cond;
	}

	if (!empty($difficulty)) {
	  $cond = array(
	    'taxonomy' => 'complexity',
	    'field' => 'slug',
	    'terms' => $difficulty,
	  );
	  $tax_query[] = $cond;
	}
	if (!empty($device)) {
	  $cond = array(
	    'taxonomy' => 'device_type',
	    'field' => 'slug',
	    'terms' => $device,
	  );
	  $tax_query[] = $cond;
	}

	// Repeat for other parameters

	$args = array(
		's' => $q, 
		'post_type' => 'recipe',
		'post_status' => 'publish',
		'paged' => $_POST['paged'],
		'showposts' => -1,
		// 'offset' => 2,
		'tax_query' => $tax_query  

	);

	$count = count(get_posts( $args ));
	return $count;
}
function get_recipe_list(){
	$posts = search_recipe();


	?>
	<div class="search-container">
		<div class="search-box">
			<input type="text" id="search-input" class="search-input" placeholder="Type recipe">
		</div>
		<div class="search-btn active search-filter">Search</div>
		<div class="filter-btn search-filter">Filter</div>
		<div id="spinner">
			<img src="<?php echo get_site_url(); ?>/wp-content/themes/tgi-child/dist/images/spinner.svg">
		</div>
	</div>
	<div id="filter">
		<div class="filter-container">
			<div class="filter-wrapper">
				<?php
					$recipe_category = get_terms( array(
				    'taxonomy' => 'category',
				    'hide_empty' => false,
				   ));
				?>
				
				<select id="recipe-cat" data-filter="filter-category" class="filter-input">
					<option value="">Category</option>
					<?php foreach($recipe_category as $cat): ?>
						<option value="<?php echo $cat->slug; ?>"><?php echo $cat->name; ?></option>
					<?php endforeach; ?>
				</select>
				
			</div>
			<div class="filter-wrapper">
				<?php
					$difficulty = get_terms([
					    'taxonomy' => 'complexity',
					    'hide_empty' => false,
					]);
				?>
				
				<select id="recipe-difficulty" data-filter="filter-difficulty" class="filter-input">
					<option value="">Difficulty</option>
					<?php foreach($difficulty as $item): ?>
						<option value="<?php echo $item->slug; ?>"><?php echo $item->name; ?></option>
					<?php endforeach; ?>
				</select>
				
			</div>
			<div class="filter-wrapper">
				<?php
					$device_type = get_terms( array(
					    'taxonomy' => 'device_type',
					    'hide_empty' => false,
					));
				?>
				
				<select id="recipe-device" data-filter="filter-device" class="filter-input">
					<option value="">Device</option>
					<?php foreach($device_type as $item): ?>
						<option value="<?php echo $item->slug; ?>"><?php echo $item->name; ?></option>
					<?php endforeach; ?>
				</select>
				
			</div>
		</div>

		<div class="filter-selection-container">
			<div class="filter-title">Active filters:</div>
			<div id="filter-category" class="filter-item"><span class="filter-text">Asian </span><span data-type="recipe-cat" class="remove-filter">x</span></div>
			<div id="filter-difficulty" class="filter-item"><span class="filter-text">Beginner </span><span data-type="recipe-difficulty" class="remove-filter">x</span></div>
			<div id="filter-device" class="filter-item"><span class="filter-text">MC 1.1 </span><span data-type="recipe-device" class="remove-filter">x</span></div>
			<div class="filter-clear">Clear All</div>

			<div class="found-items"><span class="found"><?php echo count_recipe(); ?></span> Recipes found</div>

		</div>

		
	</div>
	<div class="list-container">
		<?php recipe_item_template($posts); ?>
	</div>
	<div id="more-spinner-container" class="more-spinner-container">
		<div class="more-spinner">
			<img src="<?php echo get_site_url(); ?>/wp-content/themes/tgi-child/dist/images/spinner.svg">
		</div>
	</div>
<?php
}
add_shortcode('get_recipe_list', 'get_recipe_list');

function filter_recipe_list(){
$count_recipe = $_POST['count_recipe'];
$posts = search_recipe();
	?>
	<?php recipe_item_template($posts); ?>
 	<?php if($count_recipe == "1"){ ?>
	 	<span class="recipe-count" style="display: none"><?php echo count_recipe(); ?></span>
	<?php } ?>
 <?php
 die();
}

add_action('wp_ajax_filter_recipe_list', 'filter_recipe_list');
add_action( 'wp_ajax_nopriv_filter_recipe_list', 'filter_recipe_list');


function recipe_item_template($posts){
	
	foreach($posts as $post):

		?>
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
 	<?php
}

?>