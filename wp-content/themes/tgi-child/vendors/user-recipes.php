<?php

function user_recipes()
{
    ob_start();
    global $current_user;
    // $status = $_POST['status'];
    $args = array(
        'author'         => $current_user->ID,
        'post_type'      => 'recipe',
        'post_status'    => 'any',
        'posts_per_page' => -1,

    );

    $posts    = get_posts($args);
    $publish  = 0;
    $pending  = 0;
    $rejected = 0;
    $draft    = 0;
    $liked    = count(get_liked_post_array());
    $item     = "";
    $count    = 0;
    ?>
    <?php foreach ($posts as $post):
        if ($post->post_status == "publish") {
            $publish++;
        } else if ($post->post_status == "pending") {
        $pending++;
    } else if ($post->post_status == "draft") {
        $draft++;
    } else if ($post->post_status == "rejected") {
        $rejected++;
    }
    if ($count < 20) {
        $image = get_post_meta($post->ID, 'thumbnail_portrait', true);
        $item .= '<div class="user-recipe-item">
            <a href="' . get_permalink($post->ID) . '">
              <div class="recipe-image">';
        if ($image) {
            $item .= wp_get_attachment_image($image, 'full');
        } else {
            $item .= '<img src ="' . get_field('recipe_list_photo_placeholder', 'option') . '">';
        }
        $item .= '</div>
            </a>
              <div class="recipe-details">';
        $item .= '<div class="user-recipe-item-title">' . $post->post_title . '</div>';
        $item .= '<div class="tgi-icon-icon-clock-black"><span>' . get_field('total_duration', $post->ID) . ' Min</span></div>';
        $item .= '<div class="tgi-icon-icon-level-black"><span>' . strip_tags(get_the_term_list($post->ID, 'complexity')) . '</span></div>';
        $item .= '<div class="tgi-icon-icon-fire-black"><span>' . get_field('nutrients_calories', $post->ID) . '</span></div>';
        $item .= '</div>';
        if ($post->post_status != "publish") {
            $item .= '<div class="user-recipe-action">';

            $item .= '<div id="user-edit"  class="user-action"><a href="' . site_url() . '/create-recipe/?recipe-id=' . $post->ID . '" target="_blank">Edit</a></div>';
            $item .= '<div id="user-delete" class="user-action user-delete user-delete-publish" data-post_id="' . $post->ID . '">Delete</div>';
            /*}else{
            $item .='<div id="user-delete" class="user-action user-delete delete-publish" data-post_id="'.$post->ID.'">Delete</div>';
            }*/
            $item .= '</div>';
        }

        $item .= '</div>';
    }
    $count++;
    endforeach;?>


 <div class="user-recipes-container">
        <div class="user-recipes-header">
          <div class="user-recipes-title">
            <!-- <span class="active">Recipes (20)</span> -->
            <span data-func="user_recipe_tabs" data-status="publish" class="profile-menu-tab-item active">Published (<?php echo $publish; ?>)</span>

            <span data-func="user_recipe_tabs" data-status="pending" class="profile-menu-tab-item">Pending (<?php echo $pending; ?>)</span>
            <span data-func="user_recipe_tabs" data-status="draft" class="profile-menu-tab-item">Draft (<?php echo $draft; ?>)</span>
            <span data-func="user_recipe_tabs" data-status="rejected" class="profile-menu-tab-item">Rejected (<?php echo $rejected; ?>)</span>
            <span data-func="user_recipe_tabs" data-status="liked" class="profile-menu-tab-item">Liked (<?php echo $liked; ?>)</span>
          </div>
          <div class="clear"></div>
          <div class="user-recipes-search">
            <input id="profile-recipe-search" type="text" placeholder="Search">
          </div>
          <div class="clear"></div>
        </div>
       <!--  <div class="sort">
          <select>
            <option value="latest">Sort by Latest Added</option>
            <option value="difficulty">Sort by Difficulty</option>
          </select>

        </div> -->
        <div class="clear"></div>
        <div id="recipe-item-wrapper" class="recipe-item-container">
          <div class="recipe-item-wrapper">
            <?php echo $item; ?>
          </div>
          <div class="clear"></div>
          <div id="more-spinner-container" class="more-spinner-container">
            <div class="more-spinner">
              <img src="<?php echo get_site_url(); ?>/wp-content/themes/tgi-child/dist/images/spinner.svg">
            </div>
          </div>
          <script type="text/javascript">
            jQuery(function($){
                var waypoint = new Waypoint({
                    element: $('#more-spinner-container'),
                    offset: '100%',
                    handler: function(direction) {
                      if (direction === 'down') {
                        profile_auto_scroll();
                      }
                    }
                });
            });
          </script>
        </div>

  </div>

  <?php

    wp_reset_postdata();
    die();
    // ob_start();
}
// ob_end_clean();
add_action('wp_ajax_user_recipes', 'user_recipes');

function user_recipe_tabs()
{
    ob_start();
    global $current_user;
    $status = $_POST['status'];
    $s      = $_POST['s'];
    if ($status == "liked") {
        // include_once('like.php');
        $liked_recipes = get_liked_post_array();
        $args          = array(

            'post_type'      => 'recipe',
            'post__in'       => $liked_recipes,
            'posts_per_page' => 20,
        );
        // $posts = get_posts($args);
    } else {
        $args = array(
            's'              => $s,
            'author'         => $current_user->ID,
            'post_type'      => 'recipe',
            'post_status'    => $status,
            'posts_per_page' => 20,

        );
    }

    $posts = get_posts($args);

    ?>
    <?php foreach ($posts as $post):

        $notes = get_field('admin_notes', $post->ID);
        // if($post->post_status == $status) {
        $image = get_post_meta($post->ID, 'thumbnail_portrait', true);
        $item .= '<div class="user-recipe-item">
              <a href="' . get_permalink($post->ID) . '">
                <div class="recipe-image">';
        if ($image) {
            $item .= wp_get_attachment_image($image, 'full');
        } else {
            $item .= '<img src ="' . get_field('recipe_list_photo_placeholder', 'option') . '">';
        }
        $item .= '</div>
              </a>
                <div class="recipe-details">';
        $item .= '<div class="user-recipe-item-title">' . $post->post_title . '</div>';
        $item .= '<div class="tgi-icon-icon-clock-black"><span>' . get_field('total_duration', $post->ID) . ' Min</span></div>';
        $item .= '<div class="tgi-icon-icon-level-black"><span>' . strip_tags(get_the_term_list($post->ID, 'complexity')) . '</span></div>';
        $item .= '<div class="tgi-icon-icon-fire-black"><span>' . get_field('nutrients_calories', $post->ID) . '</span></div>';
        $item .= '</div>';

        $item .= '<div class="user-recipe-action">';

        if ($post->post_status != "publish") {
            $item .= '<div id="user-edit"  class="user-action"><a href="' . site_url() . '/create-recipe/?recipe-id=' . $post->ID . '" target="_blank">Edit</a></div>';
            $item .= '<div id="user-delete" class="user-action user-delete user-delete-publish" data-post_id="' . $post->ID . '">Delete</div>';
        } /*else{
        $item .='<div id="user-delete" class="user-action user-delete delete-publish" data-post_id="'.$post->ID.'">Delete</div>';
        }*/
        $item .= '<div class="clear-div"></div>';
        if ($post->post_status == "rejected") {
            $item .= '<div class="rejection-text">' . $notes . '<div class="rejection-box-close">x</div></div>';
            $item .= '<div class="rejection-note">Click to see rejection message</div>';
        }
        $item .= '</div>
          </div>';
        // }

    endforeach;?>

    <?php echo $item; ?>


  <?php

    wp_reset_postdata();
    die();
    // ob_start();
}
// ob_end_clean();
add_action('wp_ajax_user_recipe_tabs', 'user_recipe_tabs');

function get_profile_recipe()
{
    global $current_user;
    $author_id = $current_user->ID;
    // var_dump($curauth);

    $args = array(
        'author'    => $author_id,
        'post_type' => 'recipe',
        'showposts' => 20,
        'paged'     => $_POST['paged'],
    );
    $posts = get_posts($args);
    ?>
  <?php foreach ($posts as $post): ?>
          <?php $image = get_post_meta($post->ID, 'thumbnail_portrait', true);?>
          <div class="user-recipe-item">
              <a href="<?php echo get_permalink($post->ID); ?>">
                <div class="recipe-image">
                  <?php if ($image) {?>
                     <?php echo wp_get_attachment_image($image, 'full'); ?>
                  <?php } else {?>

                    <img src ="<?php echo get_field('recipe_list_photo_placeholder', 'option'); ?>">
                  <?php }?>
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

   <?php endforeach;?>
   <?php
die();
}

add_action('wp_ajax_get_profile_recipe', 'get_profile_recipe');
add_action('wp_ajax_nopriv_get_profile_recipe', 'get_profile_recipe');