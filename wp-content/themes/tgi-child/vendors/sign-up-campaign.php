<?php
if(!function_exists('sign_up_campain')){
	
	function sign_up_campaign(){
		if(! is_user_logged_in()){
			$select = '<span class="select-skill"><select id="select-skill">
						<option value=""></option>
		                <option value="Beginner">Beginner</option>
		                <option value="Intermediate">Intermediate</option>
		                <option value="Professional">Professional</option>

		            </select></span>';
		    $cook_for = '<span class="select-skill "><select id="cook_for_select">
						<option value=""></option>
		                <option value="Family">Family</option>
		                <option value="Husband">Husband</option>
		                <option value="Wife">Wife</option>
		                <option value="Friends">Friends</option>
		                <option value="Work">Work</option>
		                <option value="Business">Business</option>

		            </select></span>';
			?>
			<div class="sign-up-campaign">
			<h2><?php _e("What's Cooking Today", 'sign_up_campain'); ?></h2>
			
				<p><?php _e("I am a $select cooker and I usually", 'sign-up-campaign'); ?></p>
					
				  
				<p><?php _e("cook for my $cook_for", 'sign_up_campain'); ?> </p>
			
			<div class="sign-up-btn"><?php _e("Sign Up", 'sign-up-campaign'); ?></div>
			</div>
	<?php }
	}
	
add_shortcode('sc_sign_up_campaign', 'sign_up_campaign');
}