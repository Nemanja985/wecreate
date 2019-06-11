<?php
function user_edit_ajax() {
  ob_start();
  // include_once('vendors/follow.php');

 ?>
 <?php $current_user = wp_get_current_user(); ?>
    <div class="user-account-edit-container">
      <div class="account-heading">
        <span id="user_edit_ajax" class="user-menu-tab active">Account Information</span>
        <span id="user_follow" class="user-menu-tab">Followers (<?php echo count_followers($current_user->ID);?>)</span>
        <span id="user_following" class="user-menu-tab">Following (<?php echo count_following($current_user->ID);?>)</span>
      </div>

      <div class="user-account-container">
        
          <div class="info-row">
            <div class="info-col info-col1">
                <label for="first-name"><?php _e('First name', 'textdomain'); ?></label>
                <input class="text-input" name="first-name" type="text" id="first-name" value="<?php the_author_meta( 'first_name', $current_user->ID ); ?>" />
            </div>
           
            <div class="info-col info-col2">
                <label for="last-name"><?php _e('Last name', 'textdomain'); ?></label>
                <input class="text-input" name="last-name" type="text" id="last-name" value="<?php the_author_meta( 'last_name', $current_user->ID ); ?>" />
            </div>
          </div>
          <div class="info-row">
            <div class="info-col info-col1">
                <label for="email"><?php _e('E-mail *', 'textdomain'); ?></label>
                <input class="text-input" name="email" type="text" id="email" value="<?php the_author_meta( 'user_email', $current_user->ID ); ?>" />
            </div>
        
            <div class="info-col info-col2">
                <label for="phone_number"><?php _e('Phone Number', 'textdomain'); ?></label>
                <input class="text-input" name="phone_number" type="text" id="phone_number" value="<?php the_author_meta( 'phone_number', $current_user->ID ); ?>" />
            </div>
            <div class="clear"></div>
          </div>
            <div class="form-selection">
                <label for="cooking_skill"><?php _e("Cooking Skill"); ?></label>
                 <select name="cooking_skill" id="cooking_skill">
                    <option value="<?php the_author_meta( 'cooking_skill', $current_user->ID ); ?>">
                      <?php the_author_meta( 'cooking_skill', $current_user->ID ); ?>
                    </option>
                    <option value="Beginner">Beginner</option>
                    <option value="Intermediate">Intermediate</option>
                    <option value="Professional">Professional</option>

                  </select>
            </div><!-- .form-textarea -->
            
             <div class="form-textarea">
                <label for="occupation"><?php _e('Occupation', 'textdomain') ?></label>
                <textarea name="occupation" id="occupation" rows="3" cols="50"><?php the_author_meta( 'occupation', $current_user->ID ); ?></textarea>
            </div><!-- .form-textarea -->



            <div class="form-textarea">
                <label for="description"><?php _e('Biograpy', 'profile') ?></label>
                <textarea name="description" id="description" rows="3" cols="50"><?php the_author_meta( 'description', $current_user->ID ); ?></textarea>
            </div><!-- .form-textarea -->


            <div id="update-user" class="post-user-update">Update Profile</div>
           
            
          <form method="post" id="adduser" action="<?php the_permalink(); ?>">
              <div class="change-password-title">Change Password</div>
              <div class="info-row">
                <div class="info-col info-col1form-password">
                    <label for="pass1"><?php _e('Password *', 'profile'); ?> </label>
                    <input class="text-input" name="pass1" type="password" id="pass1" />
                </div><!-- .form-password -->
                <div class="info-col password-btn info-col2 form-password">
                    <label for="pass2"><?php _e('Repeat password *', 'profile'); ?></label>
                    <input class="text-input" name="pass2" type="password" id="pass2" />
                </div><!-- .form-password -->
                <div class="form-submit">
                    <input name="updateuser" type="submit" id="updateuser" class="submit button" value="<?php _e('Change Password', 'textdomain'); ?>" />
                    <?php wp_nonce_field( 'update-user' ) ?>
                    <input name="honey-name" value="" type="text" style="display:none;"></input>
                    <input name="action" type="hidden" id="action" value="update-user" />
                </div><!-- .form-submit -->
              </div>
              

          </form><!-- #adduser -->
        </div><!-- user account container -->
    </div>

  
  <?php

  wp_reset_postdata();
  die();
  // ob_start();
}
// ob_end_clean();
add_action( 'wp_ajax_user_edit_ajax', 'user_edit_ajax');
add_action( 'wp_ajax_nopriv_user_edit_ajax', 'user_edit_ajax');

function save_user_info() {
  global $current_user;

  $metas = array( 
      'first_name'   => $_POST['first_name'], 
      'last_name'    => $_POST['last_name'],
      'email'        => $_POST['email'],
      'phone_number' => $_POST['phone_number'],
      'occupation'   => $_POST['occupation'],
      'cooking_skill'   => $_POST['cooking_skill'],
      'description'  => $_POST['description']
  );

  $user_id = $current_user->ID;
  foreach($metas as $key => $value) {
    update_user_meta( $user_id, $key, $value);
  }
  if (is_wp_error($user_id)) {
     echo '<div class="update-msg-error">There is a problem updating your profile!</div>';
  }
  else {
     echo '<div class="update-msg">Your profile has been successfully updated!</div>';
  }
 exit;
  // ob_start();
}
// ob_end_clean();
add_action( 'wp_ajax_save_user_info', 'save_user_info');
add_action( 'wp_ajax_nopriv_save_user_info', 'save_user_info');


add_action( 'show_user_profile', 'extra_user_profile_fields' );
add_action( 'edit_user_profile', 'extra_user_profile_fields' );
function extra_user_profile_fields( $user ) { ?>


    <h3><?php _e("Extra profile information", "blank"); ?></h3>

    <table class="form-table">
    <tr>
        <th><label for="occupation"><?php _e("Occupation"); ?></label></th>
        <td>
            <input type="text" name="occupation" id="occupation" value="<?php echo esc_attr( get_the_author_meta( 'occupation', $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e("Please enter your occupation."); ?></span>
        </td>
    </tr>
    <tr>
        <th><label for="cooking_skill"><?php _e("Cooking Skill"); ?></label></th>
        <td>
            <select name="cooking_skill" id="cooking_skill">
                <option value="<?php echo esc_attr( get_the_author_meta( 'cooking_skill', $current_user->ID ) ); ?>">
                  <?php echo esc_attr( get_the_author_meta( 'cooking_skill', $current_user->ID ) ); ?>
                </option>
                <option value="Beginner">Beginner</option>
                <option value="Intermediate">Intermediate</option>
                <option value="Professional">Professional</option>

            </select>
            <br/>
            <span class="description"><?php _e("Please enter your cooking skill."); ?></span>
        </td>
    </tr>
    </table>
<?php }