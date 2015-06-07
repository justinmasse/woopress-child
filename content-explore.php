<?php
/**
*	Template for standard Recipie Posts
*/
$postId = get_the_ID();

$custom = get_post_custom( $postId );
$col_width =  @$custom["explore-grid"][0] * 4;
if(has_post_thumbnail( $postId ) ):
	$imgSrc = etheme_get_image(get_post_thumbnail_id($postId));
endif;
?>
<!-- <a href="<?php the_permalink(); ?>">
	<div class="explore-item somepadding>
		<div class="explore-item-container">
			<div class="bg"  style="background: url(<?php echo $imgSrc; ?>);"></div>
			<h3><?php the_title(); ?></h3>
		</div>
	</div>
</a> -->

<figure class="effect-goliath explore-item  col-sm-12 col-xs-12 col-md-<?php echo $col_width; ?>">
	<div class="bg">
		<div class="bg-image"  style="background: url(<?php echo $imgSrc; ?>);"></div>
	</div>
	<figcaption>
		<h2><?php the_title(); ?></h2>
		<p><?php the_excerpt(); ?></p>
		<a href="<?php the_permalink(); ?>">View more</a>
	</figcaption>			
</figure>
