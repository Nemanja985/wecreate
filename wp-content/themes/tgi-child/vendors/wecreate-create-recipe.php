<?php 
function create_recipe_intro(){
	global $post;
	$page = $post;
	$unsaved_warn = "You have unsaved data, are you sure you want to leave?";
	?>
	<script type="application/javascript">
		var somethingChanged = false;
		jQuery(document).ready(function ($) {
			$( window ).on('beforeunload', function() {
				$('#ajax-loading-screen').hide();
				if(somethingChanged){
					// $('#ajax-loading-screen').hide();
				  	return "You have unsaved data, are you sure you want to leave?";
				}
			});
		});
	</script>
	<div class="spacer"></div>
	<?php
	if(!is_user_logged_in()):
		_e("<center>You need to login to view this page</center>", 'wecreate-create-recipe');
	else:

		$recipe_id = "";
		$recipe_id = $_GET['recipe-id'];
		$rec_post = get_post($recipe_id);
		$current_user = wp_get_current_user();



		if($recip_id && $rec_post->post_author != $current_user->ID):
			_e( "<center>You cannot view this post</center>", 'wecreate-create-recipe');
		else:
			if($recipe_id):	
				if($rec_post->post_status == 'publish' && !current_user_can('administrator')):
					_e("<center>Published recipe cannot be edited.</center>", 'wecreate-create-recipe'); 
				else: 
					create_recipe_data($page, $rec_post, $recipe_id, $current_user);
				endif; 
			else:
				create_recipe_data($page, $rec_post, $recipe_id, $current_user);
			endif;
		endif; 
		// create_recipe_data($post, $recipe_id, $current_user);		
	endif; 
}
add_shortcode('create_recipe_sc', 'create_recipe_intro');

	function create_recipe_data($page, $rec_post, $recipe_id, $current_user){ ?>
		<div class="top-spacer"></div>
		<div id = "create-recipe-tabs">
			<div class="intro-col tab-col active">
				<div class="create-recipe-icon tab-icon recipe-intro-btn">
					<h5><?php _e("Recipe Introduction", 'wecreate-create-recipe'); ?></h5>
					<div class="content-id hidden" data-id="recipe-intro"></div>
				</div>
			</div>
			<div class="upload-col tab-col">
				<div class="upload-recipe-icon tab-icon upload-recipe-videos-btn">
					<h5><?php _e("Upload Recipe Videos", 'wecreate-create-recipe'); ?></h5>
					<div class="content-id hidden" data-id="upload-recipe-videos"></div>
				</div>
			</div>
			<div class="publish-col tab-col">
				<div class="preview-publish-icon tab-icon preview-publish-btn">
					<h5><?php _e("Preview Publish", 'wecreate-create-recipe'); ?></h5>
					<div class="content-id hidden" data-id="preview-publish"></div>
				</div>
			</div>
			
			<div class="chef-col <?php //if($recipe_id){echo 'success-tab';} ?>">

				<div class="status-icon tab-icon status-btn <?php //if($recipe_id){echo 'success-tab';} ?>">
					<h5>
							<?php if($recipe_id): ?>
								<?php if($rec_post->post_status !='publish'): ?>
									<?php _e("Submitted", 'wecreate-create-recipe'); ?>
								<?php else: ?>
									<?php _e("Approved", 'wecreate-create-recipe'); ?>
								<?php endif; ?>
								
							<?php else: ?>
								<?php _e("Success", 'wecreate-create-recipe'); ?>
							<?php endif; ?>
						
					</h5>
					<!-- <div class="content-id hidden" data-id="chef-endorsement"></div> -->
				</div>
			</div>
			
		</div>
		<div class="clear-div"></div>
		<div id="recipe-intro" class="recipe-tab-content">
			<div class="col1">
				<video id="vid-intro" controls>
				   <source src="<?php echo get_field('introduction_video', $page->ID); ?>">
				</video>
				<div class="recipe-intro-text">
					<?php echo get_field('introduction_text', $page->ID); ?>
				</div>
			</div>
			
			<div class="col2">
				<div class="step1-row1">
					<?php
						$device_type = get_terms( array(
						    'taxonomy' => 'device_type',
						    'hide_empty' => false,
						   ));
						// $old_cat = get_the_category($rec_post->ID);
						$old_device = strip_tags(get_the_term_list($rec_post->ID, 'device_type'));
					?>
					<div class="device-box category-select">
						
						<?php if($old_device){ ?>
							
							<span><?php echo $old_device; ?></span>	
							
						<?php }else{ ?>
							<span><?php _e("Device Type", 'wecreate-create-recipe'); ?></span>
						<?php } ?>
						<div id="device-category" class="device-category-options category-options">
							
							
							<?php if($old_device){ ?>
								<div class="cat-option-title" data-id="device-box-value"><?php echo $old_device; ?></div>
							<?php }else{ ?>
								<div class="cat-option-title" data-id="device-box-value"><?php _e("Device Type", 'wecreate-create-recipe'); ?></div>
							<?php } ?>

							<?php foreach ($device_type as $device): ?>
								<div class="cat-option" data-id="device-box-value"><?php _e($device->name, 'wecreate-create-recipe'); ?></div>
							<?php endforeach; ?>
							
						</div>
					</div>
					<input type="hidden" id="device-box-value" value="<?php echo $old_device; ?>">
					<?php
						$recipe_category = get_terms( array(
						    'taxonomy' => 'category',
						    'hide_empty' => false,
						   ));
						$old_cat = get_the_category($rec_post->ID);
					?>
					<div class="recipe-category category-select">
						
						<?php if($old_cat){ ?>
							
							<span><?php echo $old_cat[0]->name; ?></span>	
							
						<?php }else{ ?>
							<span>Category Type</span>
						<?php } ?>
						<div class="recipe-category-options category-options">
							<?php if($old_cat){ ?>
								<div id="recipe-category" class="cat-option-title" data-id="recipe-category-value"><?php echo $old_cat[0]->name; ?>
									
								</div>
							<?php }else{ ?>
								<div id="recipe-category" class="cat-option-title" data-id="recipe-category-value">Category Type</div>
							<?php } ?>
							<?php foreach ($recipe_category as $cat): ?>
								<div class="cat-option" data-id="recipe-category-value"><?php echo $cat->name; ?></div>
							<?php endforeach; ?>
						</div>

					</div>
					<input type="hidden" id="recipe-category-value" value="<?php echo $old_cat[0]->name; ?>">
				</div>
				<div class="step1-row2">
					<div class="recipe-title">
						<h5><?php _e("Title of Recipe", 'wecreate-create-recipe'); ?></h5>
					</div>
					<div class="recipe-title-input">
						<input type="text" id="recipe-title-value" placeholder="e.g. Thai Green Curry Chicken Breast" value="<?php if($recipe_id){ echo $rec_post->post_title;} ?>">
					</div>
				</div>
				<div class="step1-row2-1">
					<div class="urv-desc-title"><h5>Description</h5></div>
					<div id="urv-desc" class="urv-desc">
						<textarea type="text" id="recipe-description-value" placeholder="e.g. Maecenas sed diam eget risus varius blandit sit amet."><?php echo the_field('description', $rec_post->ID); ?></textarea>
						<div class="word-count">140 words</div>
					</div>
					
				</div>

				<?php $calories = get_field('nutrients_calories', $rec_post->ID); ?>
				<?php $protein = get_field('nutrients_protein', $rec_post->ID); ?>
				<?php $carbohydrate = get_field('nutrients_carbohydrate', $rec_post->ID); ?>
				<?php $fat = get_field('nutrients_fat', $rec_post->ID); ?>

				<div class="step1-row3">
					<div class="nutrition-title">
						<h5>Nutrition</h5>
					</div>
					<div class="nutrition-cols">
						
							<div class="nutrition-box">
								<div class="nutr-input">
									
									<input id="nutrition-calories" type="text" value="<?php if($calories){echo $calories;} ?>" placeholder="0"><div class="nutr-unit">kCal</div>
								</div>
								<div class="nutr-text">Calories</div>	
							</div>
							<div class="nutrition-box">
								<div class="nutr-input">
									<input id="nutrition-protein" type="text" value="<?php if($protein){echo $protein;}?>" placeholder="0"><div class="nutr-unit">g</div>
								</div>
								<div class="nutr-text">Protein</div>	
							</div>
							<div class="nutrition-box">
								<div class="nutr-input">
									<input id="nutrition-carbohydrate" type="text" value="<?php if($carbohydrate){echo $carbohydrate;}?>" placeholder="0"><div class="nutr-unit">g</div>
								</div>
								<div class="nutr-text">Carbohydrate</div>	
							</div>
							<div class="nutrition-box">
								<div class="nutr-input">
									<input id="nutrition-fat" type="text" value="<?php if($fat){echo $fat;}?>" placeholder="0"><div class="nutr-unit">g</div>
								</div>
								<div class="nutr-text">Fat</div>	
							</div>
						
					</div>
				</div>
				<?php 
					// $dificulty = ['Beginner', 'Intermediate', 'Expert'];
					$dificulty = get_terms([
							    'taxonomy' => 'complexity',
							    'hide_empty' => false,
							]);
					// var_dump($dificulty);
					$complexity = strip_tags(get_the_term_list($rec_post->ID, 'complexity'));
				?>
				<div class="step1-row4">
					<div class="difficulty-title">
						<h5>Dificulty</h5>
					</div>
					<?php $count = 0; ?>
					<?php foreach($dificulty as $items):?>
						<div class="difficulty-btn <?php if($items->name == $complexity){ echo 'active';} ?>">
							<span><?php echo $items->name; ?></span>
						</div>
						<?php $count++; ?>
					<?php endforeach; ?>
					<input type="hidden" id="form-difficulty-value" value="<?php if(!$complexity){ echo "Beginner"; }else{echo $complexity;} ?>">
				</div>
				<div class="step1-row5">
					<div class="ingridients-title">
						<h5>Ingridients</h5>
					</div>
					<div class="=ingridients-cols">

						
						<div class="servings-cols">
							<div class="servings-title">
								<h5>Servings</h5>
							</div>
							<div class="serve-plus">+</div>
							<div class="serve-amount" id="form-serving-value"><?php if($recipe_id){echo get_field('servings', $rec_post->ID);}else{echo "3";} ?></div>
							<div class="serve-minus">&ndash;</div>
						</div>

					</div>
				</div>
				<div class="clear"></div>
				<div class="step1-row6">
					<div class="row6-col1 row6-cols">
						<input id="ing-amount" placeholder="e.g. 300"> 
					</div>
					<?php $units = get_field('ingredient_units', 'option'); ?>
					<div class="row6-col2 row6-cols">
						<select id="ing-unit"> 
								<option value="">Unit</option>
							<?php foreach($units as $unit): ?>
								<option value="<?php echo $unit['unit_short_name']; ?>"><?php echo $unit['unit_name']; ?></option>
							<?php endforeach; ?>
						</select>
						<!-- <input id="ing-unit" placeholder="Type metric, e.g. kilo, tablespoon, gram, etc."> -->
					</div>
					<div class="row6-col3 row6-cols">
						<input id="ing-name" placeholder="e.g. Chopped Onions"> 
						<input type="hidden" id="sys_ing">
						<div class="ing-name-selection">
							<div class="autoload-spinner"></div>
							<div class="ing-name-selection-text"></div>
						</div>
					</div>
					<div id="save-entry" class="row6-col4"></div>
				</div>
				<div class="clear"></div>
				
				<ul id="sorting" class="ingridients-container">

					<?php
						$ing_array = get_field('ingredients', $rec_post->ID);
						if($recipe_id && $ing_array){
							
							foreach ($ing_array as $ing) {
								if(current_user_can('administrator')):
									$save_icon = "";
									if($ing['system_ingredient'] < 1){
										$save_icon = "<button class='save-ingredient'>Save</button><button class='saved-ingredient ing-exist' disabled>&#x2713;</button>";
									}else{
										$save_icon = "<button class='save-ingredient ing-exist'>Save</button><button class='saved-ingredient' disabled>&#x2713;</button>";
									}
								endif; 		
								echo "<li class='saved-entry'>
						       	 	<span class='ing-amount'>".$ing['amount']."</span>
						  			<span class='ing-unit'>".$ing['unit']."</span>
						  			<span class='ing-name'>".$ing['name']."</span>
						  			<span class='sys_ing'>".$ing['system_ingredient']."</span>
									".$save_icon."
						  			<div class='remove-entry'>x</div>
						  		</li>";
							}
					  	}
					?>
				</ul>
				<div class="clear"></div>
			</div>
		</div>
		<?php if($recipe_id): ?>
			<?php $attached = get_attached_media('video', $recipe_id); ?>
			<?php $attached = array_values($attached); ?>
			<?php $first_vid = $attached[0]->guid; ?>
		<?php endif; ?>
		
		<div id="steps-data-template">
			<li class="steps-list" id=""
				data-step_id =""
				data-parent =""
				data-sub_step=""
				data-seq=""
				data-attach_id=""
				data-duration=""
				data-start=""
				data-end=""
			 	data-step="" 
				data-title=""
				data-desc=""
				data-device=""
				data-reverse=""
				data-speed=""
				data-temperature=""
				data-time=""
				data-weight=""
				data-turbo=""
				data-ing="">


			</li>
		</div>
		<ul id="steps-data">
			<?php $steps = get_field('step_groups', $rec_post->ID); ?>
			
			<?php
				$step_count = 1;
				$title ="";
				$desc ="";
				$url ="";
				$count = 0;
			?>
			<?php 
				function get_ingredients($arr){
					if($arr){
						foreach ($arr as $ing) {
							echo "<li class='urv-saved-entry'>
					       	 	<span class='urv-ing-amount'>".$ing['amount']."</span>
					  			<span class='urv-ing-unit'>".$ing['unit']."</span>
					  			<span class='urv-ing-name'>".$ing['name']."</span>
					  			<div class='urv-remove-entry'>x</div>
					  		</li>";
						}
					}
				}
			?>
			<?php $step_buttons ="";?>	
			<?php //$main_count = 0; ?>
			<?php if($steps){ ?>
				
				<?php foreach($steps as $step): ?>
					<?php //var_dump($step); ?>
					<?php if($step_count == 1){ 
						// $step_no = $step['step_number'];
						// $parent = $step['step_parent'];
						$title = $step['name'];
						$desc =  $step['steps'][$count]['description'];
						$duration = $step['duration'];
						$start = $step['video_landscape_start_time'];
						$end = $step['video_landscape_end_time'];
						$setting_reverse = $step['steps'][$count]['device_setting_reverse'];
						$setting_speed = $step['steps'][$count]['device_setting_speed'];
						$setting_temperature = $step['steps'][$count]['device_setting_temperature'];
						$setting_time = $step['steps'][$count]['device_setting_time'];
						$setting_weight = $step['steps'][$count]['device_setting_weight'];
						$setting_turbo = $step['steps'][$count]['device_setting_turbo'];
						$ingr = $step['ingredients'];
						$urv_ing = "";

						

						$reverse_option = "";
						if($setting_reverse){
							$reverse_option = "<option value='1'>True</option>
												<option value='0'>False</option>";
						}else{
							$reverse_option = "<option value='0'>False</option>
												<option value='1'>True</option>";
						}
						if($ingr){
							foreach ($ingr as $ing) {
								$urv_ing .="<li class='urv-saved-entry' data-amount='".$ing['amount']."' 
									data-unit='".$ing['unit']."' data-name='".$ing['name']."'>
						       	 	<span class='urv-ing-amount'>".$ing['amount']."</span>
						  			<span class='urv-ing-unit'>".$ing['unit']."</span>
						  			<span class='urv-ing-name'>".$ing['name']."</span>
						  			<div class='urv-remove-entry'>x</div>
						  		</li>";
							}
						}
						
					} ?>
					<li class="steps-list" id="steps-step-<?php echo $step['step_number'];?>"
						data-parent="<?php echo $step['step_parent']; ?>"
						data-step_id="<?php echo $step['step_number']; ?>"
						data-sub_step="<?php echo $step['sub_step']; ?>"
						data-seq="<?php echo $step_count;?>"
						data-attach_id="<?php echo $step['video_landscape']['ID']; ?>"
						data-duration="<?php echo $step['duration']; ?>"
						data-start="<?php echo $step['video_landscape_start_time']; ?>"
						data-end="<?php echo $step['video_landscape_end_time']; ?>"
						data-title="<?php echo $step['name']; ?>"
						data-desc="<?php echo $step['steps'][0]['description']; ?>"
						data-device="<?php echo $step['device_type']; ?>"
						data-reverse="<?php echo $step['steps'][0]['device_setting_reverse']; ?>"
						data-speed="<?php echo $step['steps'][0]['device_setting_speed']; ?>"
						data-temperature="<?php echo $step['steps'][0]['device_setting_temperature']; ?>"
						data-time="<?php echo $step['steps'][0]['device_setting_time']; ?>"
						data-weight="<?php echo $step['steps'][0]['device_setting_weight']; ?>"
						data-turbo="<?php echo $step['steps'][0]['device_setting_turbo']; ?>"
						<?php $urv_ing_array = $step['ingredients']; ?>
						data-ing ="<?php get_ingredients($urv_ing_array); ?>"
					>
						
					</li>
					<?php
					$active_class="";
					if($step_count == 1){
						$active_class = " step-active";
					}
					// $duration_secs = 0;
					// if($step['duration']){
					// 	$duration_secs = $step['duration'];
					// }
					// $duration_time_format = gmdate("H:i:s", $duration_secs);
					$time1 = $start;
					$time2 = $end;
					$timelength = strtotime( $time2 ) - strtotime( $time1 );

					$hours = intval( $timelength / 3600 );
					$minutes = intval( ( $timelength % 3600 ) / 60 );
					$seconds = $timelength % 60;

					$vid_duration = str_pad( $hours, 2, '0', STR_PAD_LEFT ) . ':' . str_pad( $minutes, 2, '0', STR_PAD_LEFT ) . ':' . str_pad( $seconds, 2, '0', STR_PAD_LEFT );



					$video_step_number = "";
					$remove_step = "remove-step";
					if($step['sub_step']){
						$video_step_number = ".".$step['sub_step'];
						$remove_step = "remove-sub-step";
					}

					$str_time = $step['video_landscape_start_time'];

					$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);

					sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);

					$time_seconds = $hours * 3600 + $minutes * 60 + $seconds;
					// if($step['step_parent'] == $step['step_number']){
					// 	$main_count++;
					// }
					$step_buttons .= '<div id = "video-step-item-'.$step['step_number'].'" class="video-step-item'.$active_class.'" data-step_id="'.$step['step_number'].'" data-parent="'.$step['step_parent'].'" data-seq="'.$step_count.'" data-sub_step="'.$step['sub_step'].'">
								
								<div class="video-step-wrapper">
									<video id="video-step-'.$step['step_number'].'"  preload="metadata" data-id="'.$step['video_landscape']['ID'].'" data-start="'.$step['video_landscape_start_time'].'" data-end="'.$step['video_landscape_end_time'].'" class="video-step" src="'.$step['video_landscape']['url'].'#t='.$time_seconds.'"  type="video/mp4">
									
									</video>

									
								</div>
							<div class="video-step-number">Step ' . $step['step_parent'].$video_step_number.'</div>
							<div id="duration-'.$step['step_number'].'" class="video-step-duration">'.$vid_duration.'</div>
							<div class="'.$remove_step.'">x</div>
						</div>';

					?>
					<?php $step_count++; ?>
					<?php $count++; ?>
				<?php endforeach; ?>
				
			<?php }else{ ?>
				<li class="steps-list" id="steps-step-1"
					data-parent="1"
					data-step_id="1"
					data-sub_step=""
					data-seq=""
					data-attach_id=""
					data-duration=""
					data-start=""
					data-end=""
					data-title=""
					data-desc=""
					data-device=""
					data-reverse=""
					data-speed=""
					data-temperature=""
					data-time=""
					data-weight=""
					data-turbo=""
					data-ing="">
				>
				</li>

					
			<?php } ?>
		</ul>
		<div id = "upload-recipe-videos" class="recipe-tab-content"><!-- upload video -->
			<div class="step2-left-col">
				<div class="video-container">
					<div class="default-video-container">
						<?php  if($first_vid){ ?>
							<video id="first-video" controls>
								<source src="<?php echo $first_vid; ?>">
							</video>
						<?php }else{ ?>
							<video id="first-video" controls style="display: none;">
								<source src="">
							</video>
							<div class="upload-video-icon">
								<i class="fa fa-video-camera fa-3x" aria-hidden="true"></i>
								<div class="upload-text">Upload your video now!</div>
								
							</div>

						<?php } ?>
						
					</div>
					<div class="video-upload-form">
						<div class="x-close"><span>x</span></div>
						<div class="video-upload-message"></div>
						<!-- <div class="video-upload-progress"></div> -->
						<div class="progress">
                        <div class="progress-bar progress-bar-success myprogress" role="progressbar" style="width:0%">0%</div>
                    </div>
						<form id="file_form">
							 <?php wp_nonce_field('ajax_file_nonce', 'security'); ?>
		    				<input type="hidden" name="action" value="my_file_upload">
							<input id="video-step-input" type="file">
							<!-- <div class="upload-video-icon"> -->
							<div class="save-video-btn">Save</div>
							<!-- <input type="submit" class="save-video-btn" value="Save"> -->
						</form>
					</div>

				</div>
				<div id="vid-selection-template">
					<div class="video-selection active">
            			<video class="video-thumbnail" id="" height="60">
            				<source src ="" type='video/mp4'>
            			</video>
            			<div class="remove-video" data-author="<?php echo $current_user->ID; ?>" data-id="">x</div>
            		</div>
				</div>
				<div class="video-selection-container">
					<div class="video-upload-scroll-left">
						<i class="fa">&#xf0d9;</i>
					</div>
					<div class="video-selection-wrapper">

						<div class="video-selection-scroller" style="width:<?php echo (count($attached) + 1 ) * (68); ?>px;" data-count="<?php echo count($attached); ?>">
							<?php if($attached): ?>
								<?php foreach($attached as $vid): ?>
									<div class="video-selection">
										<video height="60" id="video-<?php echo $vid->ID; ?>" data-duration="" data-start="0" data-end="0" data-id="<?php echo $vid->ID; ?>" class="video-thumbnail" data-src="<?php echo $vid->guid; ?>" preload="metadata">
											<source src="<?php echo $vid->guid; ?>">
										</video>
										<div class="remove-video" data-author="<?php echo $vid->post_author; ?>" data-id="<?php echo $vid->ID; ?>">x</div>
									</div>

								<?php endforeach; ?>
							<?php endif; ?>
						</div>
					</div>
					
					<div class="video-upload-scroll-right">
						<i class="fa">&#xf0da;</i>
					</div>
					<div class="video-upload-btn">Add Video</div>
					
				</div>
				<div class="video-editor-container">
					<div class="video-editor-wrapper">
						<div id="stop-left"></div>
						<!-- <div id="stop-right"> -->
						<!-- <video id="video-to-trim" data-duration="" data-id="" data-start="0" data-end="0">
							<source src="">
						</video> -->
						<div id="trim-spinner">Loading video frames...</div>
						<div id="video-to-trim" class="video-to-trim" data-duration="" data-id="" data-start="0" data-end="0" data-src=""></div>
						<div class="seek-left">
							<div id="seek-ui-left">
								<div class="seek-ui-left-btn">
						    	<i style="font-size:22px" class="fa">&#xf0da;</i>
						    	</div>
						    	<div class="seek-overlay">
						    	</div>
						  	</div>
				
						</div>
						<div class="seek-right">
							
						  	<div id="seek-ui-right">
						  		<div class="seek-ui-right-btn">
						    		<i style="font-size:22px" class="fa">&#xf0d9;</i>
						    	</div>
						    	<div class="seek-overlay">
						    	</div>
						  	</div>
						</div>
					</div>

				</div>
				<div class="trim-btn-container">
					<div class="reset-btn">Reset</div>
					<div class="trim-start">Start<br/><input id="trim-start" type="text" value="00:00:00" disabled></div>
					<div class="trim-end">End<br/><input id="trim-end" type="text" value="00:00:00" disabled></div>
					<div class="trim-btn">Trim</div>
					<div class="clear"></div>
					<div class="trim-msg"></div>
				</div>
				<div class="recipe-intro-text">
					<?php echo get_field('making_recipe_video_text', $page->ID); ?>
				</div>
			</div>
			<div class="step2-right-col">
				<?php if($count == 0){$count = 1;} ?>
				<div id="step-id-container" data-id="1" data-step_count="<?php echo $count; ?>" data-sub_count="<?php echo $count; ?>" ></div>
				
				<div id="step-template-container">
					<div id="" class="video-step-item step-active <?php if(current_user_can('administrator')){echo 'sub-step'; }?> new_sub_step" data-step_id="" data-parent="">
						
						<div class="video-step-wrapper">
							<video id="" data-id="" data-duration="" data-start="0" data-end="0" class="video-step" src="">
								
							</video>
						
						</div>
						<div class="video-step-number"></div>
						<div id="" class="video-step-duration">00:00</div>
						<div id="remove-step" class="remove-step">x</div>
					</div>

				</div>
				<div class="step2-steps-row">
					<div class="step-row-wrapper">
						<div class="video-step-arrow-left"><i style="font-size:22px" class="fa">&#xf0d9;</i></div>
						<div class="video-step-arrow-right"><i style="font-size:22px" class="fa">&#xf0da;</i></div>
						<div class="video-step-items-container" style="width:<?php echo $count * 115; ?>px">
							
							<?php if($step_buttons){ ?>
								<?php echo $step_buttons; ?>
							<?php }else{ ?>
								<div id = "video-step-item-1" class="video-step-item step-active" data-step_id="1" data-seq="1" data-parent="1">
									
									<div class="video-step-wrapper">
										<video id="video-step-1" data-id="1" data-duration="" data-start="0" data-end="0" class="video-step" src="null">
											
										</video>
									</div>
								
									<div class="video-step-number">Step 1</div>
									<div id="duration-1" class="video-step-duration">00:00</div>
									<div class="remove-step">x</div>
								</div>	
							<?php } ?>
							
						</div>
						<div class="video-step-next-container">
							<!-- <div class="video-step-item"> -->
								
								<?php if(current_user_can('administrator')):?>
									<div class="video-step-next">Main Step</div>
									<!-- <div class="video-next-title">Main Step</div> -->
									<div class="video-sub-step-next">Sub Step</div>
									<!-- <div class="video-next-title">Sub Step</div> -->
								<?php else: ?>
									<div class="video-step-next">Add Step</div>
								<?php endif; ?>
							<!-- </div> -->
							
						</div>
						
					</div>
				</div>
				<div class="step2-row2">
					<div id="urv-steps" class="urv-steps">
						<span>Step 1</span>
					</div>
					
				</div>
				<!-- <div class="spacer"></div> -->
				<div class="step2-row-duration">
					<div id="urv-duration">
						<div class="urv-duration-title"><h5>Duration <span>(in minutes)</span></h5></div>
						<input type="number" placeholder="Type step duration in minutes, e.g. 30" value="<?php echo $duration; ?>">
					</div>
				</div>
				
				<div class="step2-row3">
					<div id="urv-title" class="urv-title">
						<div class="urv-title-title"><h5>Step Title</h5></div>
						<input type="text" placeholder="Type step title" value="<?php echo $title; ?>">
						<div class="word-count">140 words</div>
					</div>
					
				</div>
				<div class="step2-row4">
					<div class="urv-desc-title"><h5>Description</h5></div>
					<div id="urv-desc" class="urv-desc">
						<input type="text" placeholder="Type recipe step description" value="<?php echo $desc; ?>">
						<div class="word-count">140 words</div>
					</div>
					
				</div>
				<div class="step2-row1">
					<div class="info-title"><h5>Information Display</h5></div>
					<div id="urv-reverse-wrapper" class="urv-loop urv-options">
						<select id="urv-reverse">
							<?php if($setting_reverse){ ?>
								<?php echo $reverse_option; ?>
							<?php }else{ ?>
								<option value='1'>True</option>
								<option value='0'>False</option>
							<?php } ?>
							
						</select>
						<div class="loop-icon urv-icons">
						</div>
					</div>
					<div id="urv-speed-wrapper" class="urv-speed urv-options">
						<!-- <input type="text" value="<?php if($setting_speed){echo $setting_speed;}else{echo '0';} ?>"> -->
						
						<select id = "urv-speed">

							<option value="<?php if($setting_speed){echo $setting_speed;}else{echo '0';} ?>">
								<?php if($setting_speed){echo $setting_speed;}else{echo '0';} ?>
							</option>
							<option value="0">0</option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>

						</select>


						<div class="speed-icon urv-icons">
						</div>
					</div>
					<div id="urv-temp" class="urv-temp urv-options">
						<input type="text" value="<?php if($setting_temperature){echo $setting_temperature;}else{echo '0';} ?>">
						<div class="temp-icon urv-icons">
						</div>
					</div>
					<div id="urv-time" class="urv-timer urv-options">
						<input type="text" value="<?php if($setting_time){echo $setting_time;}else{echo '00:00';} ?>">
						<div class="timer-icon urv-icons">
						</div>
					</div>
					<div id="urv-weight" class="urv-scale urv-options">
						<input type="text" value="<?php if($setting_weight){echo $setting_weight;}else{echo '0g';} ?>">
						<div class="scale-icon urv-icons">
						</div>
					</div>
				</div>
				
				<div class="clear"></div>
				
				<div class="step2-row5">
					<div class="ingridients-title">
						<h5>Ingredients</h5>
					</div>
				</div>
				<div class="clear"></div>
				<div class="step2-row6">
					<div class="row6-col1 row6-cols">
						<input id="urv-ing-amount" placeholder="e.g. 300"> 
					</div>
					<?php $units = get_field('ingredient_units', 'option'); ?>
					<div class="row6-col2 row6-cols">
						<select id="urv-ing-unit"> 
							<option value="">Unit</option>
							<?php foreach($units as $unit): ?>
								<option value="<?php echo $unit['unit_short_name']; ?>"><?php echo $unit['unit_name']; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="row6-col3 row6-cols">
						<select id="urv-ing-name"> 
							<option value ="">Select Recipe</option>
						</select>
						<!-- <div class="urv-ing-name-selection">
							<div class="urv-autoload-spinner"></div>
							<div class="urv-ing-name-selection-text"></div>
						</div> -->
					</div>
					<div id="urv-save-entry" class="row6-col4"></div>
				</div>
				<div class="clear"></div>
				<ul id="sortable" class="urv-ingridients-container">
					<?php echo $urv_ing; ?>
				</ul>
				<div class="clear"></div>
				<!-- <div class="step1-bottom-control">
					<div class="save-btn">Save</div>
					<div class="next-btn proceed-preview">Proceed to Preview & Publish</div>
				</div> -->
			</div>

		</div><!-- end upload video -->
		<?php 
			if($recipe_id){
				$attachment = get_post_meta($rec_post->ID, 'thumbnail_portrait', true); 
				$img = wp_get_attachment_image_src($attachment, 'full');
			}
		?>

		<div id="preview-publish" class="recipe-tab-content"> <!-- PREVIEW AND PUBLISH -->
			<div class="preview-col1">
				<div class="image-editor" data-img="<?php echo $img[0]; ?>">

			      	<input type="file" class="cropit-image-input">
			      	<span id="img-filename" style="display: none" data-title=""></span>
			      	<div class="cropit-preview"></div>
					
			      	<div class="image-editor-controls">
				      	<div class="image-size-label">Resize image</div>
			      		<input type="range" class="cropit-image-zoom-input">
			      	<!-- <button class="rotate-ccw">Rotate counterclockwise</button>
			      	<button class="rotate-cw">Rotate clockwise</button> -->

				      	<div class="upload-image">
				      		<button class="export save-btn">Upload</button>
				      		<div class="upload-message"></div>
				      	</div>
			    	</div>
			    </div>
			</div>

			<div class="preview-col2">

				<div class="step3-row1">
					<div id="back-upload-recipe" class="back-upload-recipe">
						<h5>Upload Recipe Videos</h5>
					</div>
					<div class="preview-col">
						<div class="publish-msg-container"></div>
						
						<?php
							// $post_status = $rec_post->post_status; 
							// $save_type = "pending";
							// if($post_status =="publish"){
							// 	$save_type = "publish";
							// }else{	
							// 	if( current_user_can('administrator')) {
							// 	    $save_type = "publish";
							// 	}else{
							// 		$save_type = "pending";
							// 	}
							// }
							
						?>
						<?php if($_GET['task'] == "manage" && current_user_can('administrator')){ ?>

							<a href="<?php echo get_site_url();?>?post_type=recipe&p=<?php echo $recipe_id;?>" target="_blank"><div class="publish-btn">Preview</div></a>
							<div id="publish-recipe" class="publish-btn" data-task="manage" data-save_type="publish" data-message="Recipe approved!">Approve</div>
							<div class="publish-btn reject-recipe">Reject</div>
							<div class="admin-overlay-panel">
								<div class="admin-reject-input">
									<div class="reject-text">Please type the reason(s) for rejection below.</div>
									<textarea id="reject-input"></textarea>
									<div class="admin-btns">
										<div class="publish-btn admin-btn cancel-reject">Cancel</div>
										<div id="confirm-reject" class="publish-btn admin-btn">Reject</div>
									</div>
								</div>

							</div>
						<?php }else{ ?>
							<div class="preview-text"><a href="<?php echo get_site_url();?>?post_type=recipe&p=<?php echo $recipe_id;?>" target="_blank">Preview</a></div>
							<div id="publish-recipe" class="publish-btn" data-message="Recipe successfuly saved!" data-save_type="pending">Publish</div>
						<?php } ?>
					</div>
				</div>
				<div class="clear"></div>
				<div class="step3-row2">
					<h5>Steps Overview</h5>
				</div>
				<div class="clear"></div>
				<div class="step3-row3">
					<ul id="recipe-summary">
						<!-- <li>
							Maecenas1 sed diam eget risus varius blandit sit amet non magna
							<div class='timer-entry'>7:35</div>
							<div class='edit-entry'></div>
							<div class='remove-step-entry'>x</div>
						</li> -->
					</ol>
				</div>
			</div>

		</div><!-- END OF PREVIEW AND PUBLISH -->

		<div id="security" data-sec="<?php $ajax_nonce = wp_create_nonce( "Updating_post" );?>"></div>
	

		<!-- // echo do_shortcode('[chef_endorsement_sc]'); -->

		<div id="chef-endorsement" class="recipe-tab-content">
			<div class="page-content-wrapper">
				<div id="status-container">
					<div class="status-video-icon"></div>
					
							<div class="submitted-text">
								<h1>Recipe uploaded successfully</h1>
								<p class="approved-text-note">
									Thank you for uploading. You’ll be notified through your email once the admin approved your video. 
								</p>
							</div>
					
					<a href="<?php echo home_url(); ?>"><div class="go-home-btn">Go to Home</div></a>

				</div>

			</div>
		</div>
		<div class="clear"></div>
		<div id = "create-recipe-nav">
			<div class="message-container"></div>
			<input type="hidden" id="post-id-container" value="<?php echo $recipe_id; ?>">
			<div class="create-recipe-nav-container">
				<div class="recipe-nav-left">
					<div id="save-recipe-post" class="recipe-save-btn save-btn" data-save_type="" data-message="Recipe saved!">Save</div>
				</div>
				<div class="recipe-nav-right">
					<div class="recipe-save-btn back-btn back-default-btn" style="display: none">Back</div>
					<div class="recipe-save-btn back-btn proceed-upload back-upload" style="display: none;">Back</div>
					<div class="recipe-save-btn next-btn proceed-upload">Next</div>
					<div style="display:none" class="recipe-save-btn next-btn proceed-summary">Next</div>
				</div>
			</div>
		</div>
	<?php
	// echo do_shortcode('[create_recipe_nav]');
}//function create recipe data
