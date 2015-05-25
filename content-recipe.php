<?php
/**
*	Template for standard Recipie Posts
*/
$postId = get_the_ID();

$categories = wp_get_post_terms($postId, 'recipe_category');
$catsClass = '';
foreach($categories as $category) {
	$catsClass .= ' sort-'.$category->slug;
}

$columns = etheme_get_option('recipe_columns');
$lightbox = etheme_get_option('recipe_lightbox');


if(isset($_GET['col'])) {
	$columns = $_GET['col'];
}

switch($columns) {
	case 2:
		$span = 'col-md-6';
	break;
	case 3:
		$span = 'col-md-4';
	break;
	case 4:
		$span = 'col-md-3';
	break;
	default:
		$span = 'col-md-4';
	break;
}
	
	$width = etheme_get_option('recipe_image_width');
	$height = etheme_get_option('recipe_image_height');
	$crop = etheme_get_option('recipe_image_cropping');

?>
<div class="recipe-item columns-count-<?php echo $columns; ?> <?php echo $span; ?> <?php echo $catsClass; ?>">       
		<?php if (has_post_thumbnail( $postId ) ): ?>
			<a href="<?php the_permalink(); ?>">
				<div class="recipe-image">
					<?php $imgSrc = etheme_get_image(get_post_thumbnail_id($postId), $width, $height, $crop) ?>
					<img src="<?php echo $imgSrc; ?>" alt="<?php the_title(); ?>">
		    	</div>
		    </a>
		<?php endif; ?>
	    <div class="recipe-descr">
	    		<?php if(etheme_get_option('project_byline')): ?>
					<span class="posted-in"><?php print_item_cats($postId); ?></span> 
			    <?php endif; ?>
			    
	    		<?php if(etheme_get_option('project_name')): ?>
			    	<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			    <?php endif; ?>

    		<?php if(etheme_get_option('project_excerpt')): ?>
				<p><?php echo content(25);  ?></p>
		    <?php endif; ?>
		    <div class="btn_group">
				<a href="<?php the_permalink(); ?>" class="btn btn-black xmedium-btn"><span><?php _e('More details', ETHEME_DOMAIN); ?></span></a>
			</div>
	    </div>    

</div>