<?php
/**
 * Loop Name: Carousel Circle Slider
 */
?>
<h3 class="widget-title"><span class="vantage-carousel-title"><span class="vantage-carousel-title-text"></span><a href="#" class="next" title="Next"><span class="vantage-icon-arrow-right"></span></a><a href="#" class="previous" title="Previous"><span class="vantage-icon-arrow-left"></span></a></span></h3>

<div class="vantage-carousel-wrapper">

	<?php $vars = vantage_get_query_variables(); ?>

	<ul class="vantage-carousel" data-query="<?php echo esc_attr(json_encode( $vars )) ?>" data-ajax-url="<?php echo esc_url( admin_url('admin-ajax.php') ) ?>">
		<?php while( have_posts() ) : the_post(); ?>
			<li class="carousel-entry">
			<?php 
			$image = wp_get_attachment_image_src( get_post_thumbnail_id() );

			the_widget(
				'Vantage_CircleIcon_Widget',
				array(
					'image' => !empty($image[0]) ? $image[0] : false,
					'title' => get_the_title(),
					'text' => get_the_excerpt(),
					'more' => siteorigin_setting( 'blog_read_more' ) ? esc_html( siteorigin_setting( 'blog_read_more' ) ) : __( 'Continue reading', 'vantage' ),
					'more_url' => get_permalink(),
					'all_linkable' => true,
					'icon_position' => 'top',
					'icon_size' => 'large',
				)
			);
			?>	
			</li>
		<?php endwhile; ?>
	</ul>
</div>
