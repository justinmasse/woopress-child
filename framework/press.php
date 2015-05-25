<?php

/**
*
* Press
*
*/

add_action('init', 'etheme_press_init');  

function etheme_press_init(){
	$labels = array(
		'name' => _x('Press', 'post type general name', ETHEME_DOMAIN),
		'singular_name' => _x('Press', 'post type singular name', ETHEME_DOMAIN),
		'add_new' => _x('Add New', 'Press', ETHEME_DOMAIN),
		'add_new_item' => __('Add New Press', ETHEME_DOMAIN),
		'edit_item' => __('Edit Press', ETHEME_DOMAIN),
		'new_item' => __('New Press', ETHEME_DOMAIN),
		'view_item' => __('View Press', ETHEME_DOMAIN),
		'search_items' => __('Search Presss', ETHEME_DOMAIN),
		'not_found' =>  __('No Presss found', ETHEME_DOMAIN),
		'not_found_in_trash' => __('No Presss found in Trash', ETHEME_DOMAIN),
		'parent_item_colon' => '',
		'menu_name' => 'Press'
	
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
		'rewrite' => array('slug' => 'Press')
	);
	
	register_post_type('etheme_Press',$args);
	
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
	/*register_taxonomy('tag',array('etheme_Press'), array(
		'hierarchical' => false,
		'labels' => $labels,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'tag' ),
	));*/
	
	$labels2 = array(
		'name' => _x( 'Press Categories', 'taxonomy general name', ETHEME_DOMAIN ),
		'singular_name' => _x( 'Press Category', 'taxonomy singular name', ETHEME_DOMAIN ),
		'search_items' =>  __( 'Search Press Types', ETHEME_DOMAIN ),
		'all_items' => __( 'All Press Categories', ETHEME_DOMAIN ),
		'parent_item' => __( 'Parent Category', ETHEME_DOMAIN ),
		'parent_item_colon' => __( 'Parent Category:', ETHEME_DOMAIN ),
		'edit_item' => __( 'Edit Categories', ETHEME_DOMAIN ),
		'update_item' => __( 'Update Category', ETHEME_DOMAIN ),
		'add_new_item' => __( 'Add New Category', ETHEME_DOMAIN ),
		'new_item_name' => __( 'New Category Name', ETHEME_DOMAIN ),
	);
	
	
	register_taxonomy('Press_category',array('etheme_Press'), array(
		'hierarchical' => true,
		'labels' => $labels2,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'Press_cat' ),
	));

}



add_shortcode('Press 2', 'etheme_Press_shortcode');

function etheme_Press_shortcode($atts) {
	$a = shortcode_atts( array(
       'title' => 'Recent Works',
       'limit' => 12
   ), $atts );
   
   
   return etheme_get_recent_Press($a['limit'], $a['title']);
    
}


function etheme_get_recent_Press($limit, $title = 'Recent Works', $not_in = 0) {
	$args = array(
		'post_type' => 'etheme_Press',
		'order' => 'DESC',
		'orderby' => 'date',
		'posts_per_page' => $limit,
		'post__not_in' => array( $not_in )
	);
	
	return etheme_create_Press_slider($args, $title);
}

function etheme_create_Press_slider($args,$title = false,$width = 540, $height = 340, $crop = true){
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
	                        get_template_part( 'Press', 'slide' );

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


function etheme_Press_pagination($wp_query, $paged, $pages = '', $range = 2) {  
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
         echo "<nav class='pagination-cubic Press-pagination'>";
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



add_shortcode('Press_grid', 'etheme_Press_grid_shortcode');

function etheme_Press_grid_shortcode() {
	$a = shortcode_atts( array(
       'categories' => '',
       'limit' => -1,
   		'show_pagination' => 1
   ), $atts );
   
   
   return get_etheme_Press($a['categories'], $a['limit'], $a['show_pagination']);
    
}




function get_etheme_Press($categories = false, $limit = false, $show_pagination = true) {

		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$cat = get_query_var('Press_category');
		
		$tax_query = array();

		if(!$limit) {
			$limit = etheme_get_option('Press_count');
		}

		if(is_array($categories) && !empty($categories)) {
			$tax_query = array(
				array(
					'taxonomy' => 'Press_category',
					'field' => 'id',
					'terms' => $categories,
					'operator' => 'IN'
				)
			);
		} else if(!empty($cat)) {
			$tax_query = array(
				array(
					'taxonomy' => 'Press_category',
					'field' => 'slug',
					'terms' => $cat
				)
			);
		}

		$args = array(
			'post_type' => 'etheme_Press',
			'paged' => $paged,	
			'posts_per_page' => $limit,
			'tax_query' => $tax_query
		);

		$loop = new WP_Query($args);
		
		if ( $loop->have_posts() ) : ?>
			<div>
				<div class="row Press masonry-Press">
				<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>

					<?php
						get_template_part( 'content', 'Press' );
					?>

				<?php endwhile; ?>
				</div>
			</div>

		<?php if ($show_pagination): ?>
			<?php etheme_Press_pagination($loop, $paged); ?>
		<?php endif ?>
		
		<?php wp_reset_query(); ?>
		
	<?php else: ?>

		<h3><?php _e('No recipies were found!', ETHEME_DOMAIN) ?></h3>

	<?php endif;
}
