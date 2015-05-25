<?php

/**
*
* explore
*
*/

add_action('init', 'etheme_explore_init');  

function etheme_explore_init(){
	$labels = array(
		'name' => _x('Explore', 'post type general name', ETHEME_DOMAIN),
		'singular_name' => _x('Explore', 'post type singular name', ETHEME_DOMAIN),
		'add_new' => _x('Add New', 'explore', ETHEME_DOMAIN),
		'add_new_item' => __('Add New explore', ETHEME_DOMAIN),
		'edit_item' => __('Edit explore', ETHEME_DOMAIN),
		'new_item' => __('New explore', ETHEME_DOMAIN),
		'view_item' => __('View explore', ETHEME_DOMAIN),
		'search_items' => __('Search explore', ETHEME_DOMAIN),
		'not_found' =>  __('No explores found', ETHEME_DOMAIN),
		'not_found_in_trash' => __('No explores found in Trash', ETHEME_DOMAIN),
		'parent_item_colon' => '',
		'menu_name' => 'Explore'
	
	);
	
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'has_archive' => false,
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title','editor','author','thumbnail','excerpt','comments'),
		'rewrite' => array('slug' => 'explore')
	);
	
	register_post_type('etheme_explore',$args);
	
	$labels = array(
		'name' => _x( 'Tags', 'taxonomy general name', ETHEME_DOMAIN ),
		'singular_name' => _x( 'Tag', 'taxonomy singular name', ETHEME_DOMAIN ),
		'search_items' =>  __( 'Search Types', ETHEME_DOMAIN ),
		'all_items' => __( 'All Tags', ETHEME_DOMAIN ),
		'parent_item' => __( 'Parent Tag', ETHEME_DOMAIN ),
		'parent_item_colon' => __( 'Parent Tag:', ETHEME_DOMAIN ),
		'edit_item' => __( 'Edit Tags', ETHEME_DOMAIN ),
		'update_item' => __( 'Update Tag', ETHEME_DOMAIN ),
		'add_new_item' => __( 'Add New Tag', ETHEME_DOMAIN ),
		'new_item_name' => __( 'New Tag Name', ETHEME_DOMAIN ),
	);
	
	// Custom taxonomy for Project Tags
	/*register_taxonomy('tag',array('etheme_explore'), array(
		'hierarchical' => false,
		'labels' => $labels,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'tag' ),
	));*/
	
	$labels2 = array(
		'name' => _x( 'Explore Categories', 'taxonomy general name', ETHEME_DOMAIN ),
		'singular_name' => _x( 'Explore Category', 'taxonomy singular name', ETHEME_DOMAIN ),
		'search_items' =>  __( 'Search Explore Types', ETHEME_DOMAIN ),
		'all_items' => __( 'All Explore Categories', ETHEME_DOMAIN ),
		'parent_item' => __( 'Parent Category', ETHEME_DOMAIN ),
		'parent_item_colon' => __( 'Parent Category:', ETHEME_DOMAIN ),
		'edit_item' => __( 'Edit Categories', ETHEME_DOMAIN ),
		'update_item' => __( 'Update Category', ETHEME_DOMAIN ),
		'add_new_item' => __( 'Add New Category', ETHEME_DOMAIN ),
		'new_item_name' => __( 'New Category Name', ETHEME_DOMAIN ),
	);
	
	
	register_taxonomy('explore_category',array('etheme_explore'), array(
		'hierarchical' => true,
		'labels' => $labels2,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'explore_cat' ),
	));

}

add_shortcode('explore 2', 'etheme_explore_shortcode');

function etheme_explore_shortcode($atts) {
	$a = shortcode_atts( array(
       'title' => 'Recent Works',
       'limit' => 12
   ), $atts );
   
   
   return etheme_get_recent_explore($a['limit'], $a['title']);
    
}


function etheme_get_recent_explore($limit, $title = 'Recent Works', $not_in = 0) {
	$args = array(
		'post_type' => 'etheme_explore',
		'order' => 'DESC',
		'orderby' => 'date',
		'posts_per_page' => $limit,
		'post__not_in' => array( $not_in )
	);
	
	return etheme_create_explore_slider($args, $title);
}

function etheme_create_explore_slider($args,$title = false,$width = 540, $height = 340, $crop = true){
	global $wpdb;
    $box_id = rand(1000,10000);
    $multislides = new WP_Query( $args );
    $sliderHeight = etheme_get_option('default_blog_slider_height');
    $class = '';
    
	ob_start();
        if ( $multislides->have_posts() ) :
            $title_output = '';
            if ($title) {
                $title_output = '<h3 class="title"><span>'.$title.'</span></h3>';
            }   
              echo '<div class="slider-container carousel-area '.$class.'">';
	              echo $title_output;
	              echo '<div class="items-slide slider-'.$box_id.'">';
	                    echo '<div class="slider recentCarousel">';
	                    $_i=0;
	                    while ($multislides->have_posts()) : $multislides->the_post();
	                        $_i++;
	                        get_template_part( 'explore', 'slide' );

	                    endwhile; 
	                    echo '</div><!-- slider -->'; 
	              echo '</div><!-- products-slider -->';
              echo '</div><!-- slider-container -->'; 

           
            echo '
                <script type="text/javascript">
                    jQuery(".slider-'.$box_id.' .slider").owlCarousel({
                        items:4, 
                        lazyLoad : true,
                        navigation: true,
                        navigationText:false,
                        rewindNav: false,
                        itemsCustom: [[0, 1], [479,2], [619,2], [768, 2],  [1200, 3], [1600, 3]]
                    });

                </script>
            ';
        endif;
        wp_reset_query();

	$html = ob_get_contents();
	ob_end_clean();
	
	return $html;
}


function etheme_explore_pagination($wp_query, $paged, $pages = '', $range = 2) {  
     $showitems = ($range * 2)+1;  

     if(empty($paged)) $paged = 1;

     if($pages == '')
     {
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }   

     if(1 != $pages)
     {
         echo "<nav class='pagination-cubic explore-pagination'>";
	         echo '<ul class="page-numbers">';
		         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<li><a href='".get_pagenum_link(1)."' class='prev page-numbers'><i class='fa fa-angle-double-left'></i></a></li>";
		
		         for ($i=1; $i <= $pages; $i++)
		         {
		             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
		             {
		                 echo ($paged == $i)? "<li><span class='page-numbers current'>".$i."</span></li>":"<li><a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a></li>";
		             }
		         }
		
		         if ($paged < $pages && $showitems < $pages) echo "<li><a href='".get_pagenum_link($paged + 1)."' class='next page-numbers'><i class='fa fa-angle-double-right'></i></a></li>";
	         echo '</ul>';
         echo "</nav>\n";
     }
}



add_shortcode('explore_grid', 'etheme_explore_grid_shortcode');

function etheme_explore_grid_shortcode( $atts ) {
	$a = shortcode_atts( array(
       'categories' => '',
       'limit' => -1,
   	'show_pagination' => 1
   ), $atts );
   
   
   return get_etheme_explore($a['categories'], $a['limit'], $a['show_pagination']);
    
}




function get_etheme_explore($categories = false, $limit = false, $show_pagination = true) {

		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$cat = get_query_var('explore_category');
		
		$tax_query = array();

		if(!$limit) {
			$limit = etheme_get_option('explore_count');
		}

		if(is_array($categories) && !empty($categories)) {
			$tax_query = array(
				array(
					'taxonomy' => 'explore_category',
					'field' => 'id',
					'terms' => $categories,
					'operator' => 'IN'
				)
			);
		} else if(!empty($cat)) {
			$tax_query = array(
				array(
					'taxonomy' => 'explore_category',
					'field' => 'slug',
					'terms' => $cat
				)
			);
		}

		$args = array(
			'post_type' => 'etheme_explore',
			'paged' => $paged,	
			'posts_per_page' => $limit,
			'tax_query' => $tax_query,
			'orderby' 	=> 'meta_explore-grid',
			'order'		=> 'ASC'
		);

		$loop = new WP_Query($args);
		$rowTotal = 0;
		if ( $loop->have_posts() ) : ?>
			<div class="explore-content">		
				<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
					<?php
					$postId = get_the_ID();
					$custom = get_post_custom( $postId );
					if($rowTotal + @$custom["explore-grid"][0] > 3): 
						$rowTotal = 0;
					?>
						</div>
					<?php endif; ?>

					<?php if($rowTotal == 0): ?>
						<div class="row explore masonry-explore">
					<?php endif; ?>
					<?php
						$rowTotal += @$custom["explore-grid"][0];
					?>
					<?php
						get_template_part( 'content', 'explore' );
					?>
					<?php 
					if($rowTotal == 3): 
						$rowTotal = 0;
					?>
					</div>
				<?php endif; ?>

				<?php endwhile; ?>
			</div>

		<?php if ($show_pagination): ?>
			<?php etheme_explore_pagination($loop, $paged); ?>
		<?php endif ?>
		
		<?php wp_reset_query(); ?>
		
	<?php else: ?>

		<h3><?php _e('No recipies were found!', ETHEME_DOMAIN) ?></h3>

	<?php endif;
}

/// START TEST
/**
 * Add cafe custom fields
 */
function add_explore_meta_boxes() {
	add_meta_box("explore_meta", "Explore Settings", "add_explore_meta_box", "etheme_explore", "normal", "high");
}
function add_explore_meta_box()
{
	global $post;
	$custom = get_post_custom( $post->ID );
 
	?>
	<style>.width99 {width:99%;}</style>
	<p>
		<label>Grid (out of three):</label><br />
		<select name="explore-grid" class="width99" >
			<?php
			for ($i=1; $i <= 3; $i++) {
				if(@$custom["explore-grid"][0] === null && $i == 1){
					echo '<option selected value="1">1</option>';
				}else{
					if(@$custom["explore-grid"][0] == $i){
						echo '<option selected  value="'.$i.'">'.$i.'</option>';
					}else{
						echo '<option value="'.$i.'">'.$i.'</option>';
					}
				}
			}
			?>
		</select>
	</p>
	<p>
		<label>Priority: (number, 0 highest priority.)</label><br />
		<input type="text" name="priority" value="<?= @$custom["priority"][0] ?>" class="width99" />
	</p>
	<?php
}
function save_explore_custom_fields(){
  global $post;
 
  if ( $post ){
    update_post_meta($post->ID, "explore-grid", @$_POST["explore-grid"]);
    update_post_meta($post->ID, "priority", @$_POST["priority"]);
  }
}

add_action( 'admin_init', 'add_explore_meta_boxes' );
add_action( 'save_post', 'save_explore_custom_fields' );
//END TEST
