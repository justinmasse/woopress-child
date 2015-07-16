<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	
	get_header( 'shop' ); 

	extract(etheme_get_shop_sidebar());
	$sidebarname = 'shop';
	
	/**
	 * woocommerce_before_main_content hook
	 *
	 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
	 * @hooked woocommerce_breadcrumb - 20
	 */
?>
<?php if ($page_heading != 'disable' && ($page_slider == 'no_slider' || $page_slider == '')): ?>
	
	<?php do_action( 'woocommerce_before_main_content' ); ?>

<?php endif ?>

<?php if($page_slider != 'no_slider' && $page_slider != ''): ?>
	<div class="page-heading-slider">
		<?php echo do_shortcode('[rev_slider_vc alias="'.$page_slider.'"]'); ?>
	</div>
<?php endif; ?>

<div class="container">
	<div class="page-content sidebar-position-<?php echo esc_attr( $position ); ?> responsive-sidebar-<?php echo esc_attr($responsive); ?>">

		<?php etheme_category_header();?>
        
		<div class="row">
			<?php if($position != 'without' && ($position == 'left' || $responsive == 'top')): ?>
				<div class="<?php echo esc_attr($sidebar_span); ?> sidebar sidebar-left">
					<?php etheme_get_sidebar($sidebarname); ?>
				</div>
			<?php endif; ?>

			<div class="content main-products-loop <?php echo esc_attr($content_span); ?>">
				<?php if($_SERVER['REQUEST_URI'] == '/hidden/aone/store/'): ?>
					<div class="row products-loop products-grid row-count-5">
					<h4 class="meta-title"><span>Specials</span></h4>
					<?php
						$args = array(
							'post_type' => 'product',
							'posts_per_page' => 10,
							'product_cat' => 'specials'
							);
						$loop = new WP_Query( $args );

						if ( $loop->have_posts() ) {

							while ( $loop->have_posts() ) : $loop->the_post();
								wc_get_template_part( 'content', 'product' );
							endwhile;
						} else {
							echo __( 'No products found' );
						}
						wp_reset_postdata();
					?>
					
					</div>
					
					<?php wp_reset_query(); ?>
					<h4 class="meta-title"><span>Categories</span></h4>

						
						
				
						
					</div>
				<?php endif; ?>
				<?php if ( have_posts() ) : ?>
						
					<?php if (woocommerce_products_will_display()): ?>
	                    
	                    	<?php
	                    		/**
	                    		 * woocommerce_before_shop_loop hook
	                    		 *
	                    		 * @hooked woocommerce_result_count - 20
	                    		 * @hooked woocommerce_catalog_ordering - 30
	                             * @hooked etheme_grid_list_switcher - 35
	                    		 */
	                    		do_action( 'woocommerce_before_shop_loop' );
	                    	?>
	                    
				<?php endif ?>
				<?php do_action( 'woocommerce_archive_description' ); ?>
				
				
					<?php woocommerce_product_loop_start(); ?>
		
						<?php woocommerce_product_subcategories(); ?>
		
						<?php while ( have_posts() ) : the_post(); ?>
		
							<?php wc_get_template_part( 'content', 'product' ); ?>
		
						<?php endwhile; // end of the loop. ?>
		
					<?php woocommerce_product_loop_end(); ?>
		
					<?php
						/**
						 * woocommerce_after_shop_loop hook
						 *
						 * @hooked woocommerce_pagination - 10
						 */
						do_action( 'woocommerce_after_shop_loop' );
					?>
		
				<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>
		
					<?php wc_get_template( 'loop/no-products-found.php' ); ?>
		
				<?php endif; ?>
		
			<?php
				/**
				 * woocommerce_after_main_content hook
				 *
				 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
				 */
				do_action( 'woocommerce_after_main_content' );
			?>

			</div>

			<?php if($position != 'without' && ($position == 'right' || $responsive == 'bottom')): ?>
				<div class="<?php echo esc_attr($sidebar_span); ?> sidebar sidebar-right">
					<?php etheme_get_sidebar($sidebarname); ?>
				</div>
			<?php endif; ?>
			<?php if($_SERVER['REQUEST_URI'] == '/hidden/aone/store/'):
				if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
					<div id="store_home_bottom" class="primary-sidebar widget-area">
						<?php dynamic_sidebar( 'store_home_bottom' ); ?>
					</div><!-- #primary-sidebar -->
				<?php endif;
			endif; ?>

		</div>

	</div>
</div>

<?php get_footer( 'shop' ); ?>