<?php
/**
 * The template for displaying search forms 
 *
 */
?>
    
	<?php if(class_exists('Woocommerce')) : ?>

		<?php
		global $query_string;

		$query_args = explode("&", $query_string);
		$search_query = "";

	foreach($query_args as $key => $string) {
		$query_split = explode("=", $string);
		if(!empty($query_split[1])){
			$search_query = $query_split[1];
		}
	} // foreach

	?>
	<form action="<?php echo esc_url( home_url( '/' ) ); ?>" id="searchform" class="hide-input" method="get"> 
		<div class="form-horizontal modal-form">
			<div class="form-group row row-less-padding search-container">
				<div class="col-sm-4 col-xs-offset-1 first col-xs-10">
					<input type="text" value="<?php if(get_search_query() == ''){  esc_attr_e('Search', ETHEME_DOMAIN);} else { the_search_query(); } ?>" class="form-control" onblur="if(this.value=='')this.value='<?php _e('Search', ETHEME_DOMAIN); ?>'" onfocus="if(this.value=='<?php _e('Search', ETHEME_DOMAIN); ?>')this.value=''" name="s" id="s" />
				</div>
				<div class="col-sm-3 col-sm-offset-0 col-xs-10 col-xs-offset-1">
					<select name="post_type" class="full-width">
						<option value="product" <?php echo ($search_query == 'product' ? "selected" : ""); ?>>Products</option>
						<option value="etheme_story" <?php echo ($search_query == 'etheme_story' ? "selected" : ""); ?>>Stories</option>
						<option value="etheme_recipe" <?php echo ($search_query == 'etheme_recipe' ? "selected" : ""); ?>>Recipes</option>
					</select>
				</div>
				<div class="col-sm-3 col-sm-offset-0 col-xs-10 col-xs-offset-1 last">
					<button type="submit" class="btn medium-btn btn-black"><?php esc_attr_e( 'Search', ETHEME_DOMAIN ); ?></button>
				</div>
			</div>
		</div>
	</form>
	
<?php else: ?>
	<?php get_template_part('searchform'); ?>
<?php endif ?>