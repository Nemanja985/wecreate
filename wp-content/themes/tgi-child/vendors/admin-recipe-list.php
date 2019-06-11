<?php

function admin_recipes() {
  ob_start();
  global $current_user;
  // $status = $_POST['status'];
  $status = "publish";
  $items = 10;
  $paged = 1;
  $args = array(
    'post_type' => 'recipe',
    'post_status' => $status,
    'posts_per_page' => $items,
    'paged' => $paged
  );
  $posts = get_posts($args);
  $posts_counts = wp_count_posts('recipe');
  $total_counts = $posts_counts->$status;
  $page_count = ceil($total_counts/$items);
 ?>
    
 <div class="admin-recipes-container">
        <div class="user-recipes-header">
          <div class="sort">
            <!-- <span>Sort by:</span> -->
            <span data-id="publish" class="admin-sort">Published</span> 

            <span  data-id="pending" class="admin-sort">Pending</span>
            <span  data-id="draft" class="admin-sort">Draft</span>
            <span  data-id="publish" class="admin-sort">Rejected</span>

            <!-- <select id="admin-sort-by" data-page="1">
              <option value="pending">Pending</option>
              <option value="publish">Publish</option>
              <option value="draft">Draft</option>
              <option value="rejected">Rejected</option>
              <option value="trash">Trash</option>
            </select> -->
    
          </div>
          <div class="user-recipes-search">
            <input id="admin-search" data-status="any" type="text" placeholder="Search">
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="clear"></div>
        <div id="recipe-item-wrapper" class="recipe-item-container">
          <div class="recipe-item-wrapper">
            <?php foreach($posts as $post): ?>
                  <?php $image = get_post_meta($post->ID, 'thumbnail_portrait', true); ?>
                  <div class="admin-recipe-item">
                      <a href="<?php echo get_permalink($post->ID);?>" target="_blank">
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
                          <div class="recipe-author">
                            By: 
                            <span>
                              <a href="<?php echo get_author_posts_url( $post->post_author ); ?>" target="_blank">
                                <?php echo get_the_author_meta('display_name', $post->post_author);?>
                              </a>
                            </span>
                            Status: 
                            <span>     
                                <?php echo $post->post_status;?>
                            </span>
                          </div>
                         
                            
                            
                         
                        </div>
                        <div class="admin-recipe-action">
                            <div id="user-delete" class="user-delete manage-btn" data-post_id="<?php echo $post->ID; ?>">X<span class="tooltiptext">Delete</span></div>
                            <div id="hand-pick" class="hand-pick manage-btn <?php if(get_field('hand_picked', $post->ID)){echo 'active';}?>" data-post_id="<?php echo $post->ID; ?>">&#9733;<span class="tooltiptext">Add to Hand Pick</span></div>
                            <a href="<?php echo site_url();?>/create-recipe?recipe-id=<?php echo $post->ID; ?>&task=manage" target="_blank">
                              <div class="admin-edit manage-btn">Manage</div>
                            </a>
                        </div>
                      <div class="clear-div"></div>
                  </div>
                
                  <?php endforeach; ?>
                   <div class="paging-container">
                    <?php for($i=1; $i < $page_count; $i++){ ?>
                       <?php $i==$paged ? $class="active": $class=""; ?>
                        <button id="paging" class="admin-prev-next-btn <?php echo $class; ?>" data-page="<?php echo $i; ?>" data-search="" data-status="<?php echo $status; ?>"><?php echo $i; ?></button>
                    <?php } ?>
                    <div class="page-number"></div>
                  </div>
                </div>
              <div class="clear"></div>
         
        </div>
          
  </div>

  <?php

  wp_reset_postdata();
  die();
  // ob_start();
}
// ob_end_clean();
add_action( 'wp_ajax_admin_recipes', 'admin_recipes');


function admin_sort_recipe() {
  ob_start();
  global $current_user;
  $items = 10;
  $status = $_POST['status'];
  $paged = $_POST['paged'];
  $s = $_POST['s'];
  $args = array(
    's' => $s,
    'post_type' => 'recipe',
    'post_status' => $status,
    'posts_per_page' => $items,
    'paged' => $paged
  );


  $posts = get_posts($args);
  if(!$s): 
    $posts_counts = wp_count_posts('recipe');
    $total_counts = $posts_counts->$status;
    $page_count = ceil($total_counts/$items);
  endif;
 ?>
  <?php foreach($posts as $post): ?>
      <?php $image = get_post_meta($post->ID, 'thumbnail_portrait', true); ?>
        <div class="admin-recipe-item">
            <a href="<?php echo get_permalink($post->ID);?>" target="_blank">
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
                <div class="recipe-author">
                  By: 
                  <span>
                    <a href="<?php echo get_author_posts_url( $post->post_author ); ?>" target="_blank">
                      <?php echo get_the_author_meta('display_name', $post->post_author);?>
                    </a>
                  </span>
                  Status: 
                  <span>     
                      <?php echo $post->post_status;?>
                  </span>
                </div>

              </div>
             <div class="admin-recipe-action">
                <div id="user-delete" class="user-delete manage-btn" data-post_id="<?php echo $post->ID; ?>">X<span class="tooltiptext">Delete</span></div>
                <div id="hand-pick" class="hand-pick manage-btn <?php if(get_field('hand_picked', $post->ID)){echo 'active';}?>" data-post_id="<?php echo $post->ID; ?>">&#9733;<span class="tooltiptext">Add to Hand Pick</span></div>
                <a href="<?php echo site_url();?>/create-recipe?recipe-id=<?php echo $post->ID; ?>&task=manage" target="_blank">
                  <div class="admin-edit manage-btn">Manage</div>
                </a>
            </div>
            <div class="clear-div"></div>

        </div>
      
  <?php endforeach; ?>
    <?php if(!$s): ?>
      <div class="paging-container">
        <?php for($i=1; $i < $page_count; $i++){ ?>
            <?php $i==$paged ? $class="active": $class=""; ?>
           <button class="admin-prev-next-btn <?php echo $class; ?>" data-page="<?php echo $i; ?>" data-search="" data-status="<?php echo $status; ?>"><?php echo $i; ?></button>
        <?php } ?>
      </div>
    <?php endif; ?>
<?php
  wp_reset_postdata();
  die();
}
// ob_end_clean();
add_action( 'wp_ajax_admin_sort_recipe', 'admin_sort_recipe');


