<?php 
	global $etheme_responsive; 
	$fd = etheme_get_option('footer_demo'); 	
	$fbg = etheme_get_option('footer_bg');
	$fcolor = etheme_get_option('footer_text_color');
	$ft = ''; $ft = apply_filters('custom_footer_filter',$ft);
	$custom_footer = etheme_get_custom_field('custom_footer'); 
?>
    </div>
    <?php if($custom_footer != 'without'): ?>
		<div class="footer-top footer-top-<?php echo esc_attr($ft); ?>">
			<div class="container">
                <div class="row">
                    <div class="col-md-3">
                        <?php dynamic_sidebar( 'footer-column-one' ); ?>
                    </div>
                    <div class="col-md-3">
                        <?php dynamic_sidebar( 'footer-column-two' ); ?>
                    </div>
                    <div class="col-md-3">
                    	<?php dynamic_sidebar( 'footer-column-three' ); ?>
                    </div>
                    <div class="col-md-3">
                    	<?php dynamic_sidebar( 'footer-column-four' ); ?>
                    </div>
                </div>
			</div>
		</div>
	
		<?php if((is_active_sidebar('footer9') || is_active_sidebar('footer10') || $fd) && empty($custom_footer)): ?>
		<div class="copyright copyright-<?php echo esc_attr($ft); ?> text-color-<?php echo $fcolor; ?>" <?php if($fbg != ''): ?>style="background-color:<?php echo $fbg; ?>"<?php endif; ?>>
			<div class="container">
				<div class="row-copyrights">
					<div class="pull-left">
						<?php if(is_active_sidebar('footer9')): ?> 
							<?php dynamic_sidebar('footer9'); ?>	
						<?php else: ?>
							<?php if($fd) etheme_footer_demo('footer9'); ?>
						<?php endif; ?>
					</div>
					<div class="clearfix visible-xs"></div>
					<!--<div class="copyright-payment pull-right">
						<?php if(is_active_sidebar('footer10')): ?> 
							<?php dynamic_sidebar('footer10'); ?>	
						<?php else: ?>
							<?php if($fd) etheme_footer_demo('footer10'); ?>
						<?php endif; ?>
					</div>-->
				</div>
			</div>
		</div>
	    <?php endif; ?>
    <?php endif; ?>
	
	</div> <!-- page wrapper -->
	</div> <!-- st-content-inner -->
	</div>
	</div>
	<?php do_action('after_page_wrapper'); ?>
	</div> <!-- st-container -->
	

    <?php if (etheme_get_option('loader')): ?>
    	<script type="text/javascript">
    		if(jQuery(window).width() > 1200) {
		        jQuery("body").queryLoader2({
		            barColor: "#111",
		            backgroundColor: "#fff",
		            percentage: true,
		            barHeight: 2,
		            completeAnimation: "grow",
		            minimumTime: 500,
		            onLoadComplete: function() {
			            jQuery('body').addClass('page-loaded');
		            }
		        });
    		}
        </script>
	<?php endif; ?>
	
	<?php if (etheme_get_option('to_top')): ?>
		<div id="back-top" class="back-top <?php if(!etheme_get_option('to_top_mobile')): ?>visible-lg<?php endif; ?> bounceOut">
			<a href="#top">
				<span></span>
			</a>
		</div>
	<?php endif ?>


	<?php
		/* Always have wp_footer() just before the closing </body>
		 * tag of your theme, or you will break many plugins, which
		 * generally use this hook to reference JavaScript files.
		 */

		wp_footer();
	?>
</body>

</html>