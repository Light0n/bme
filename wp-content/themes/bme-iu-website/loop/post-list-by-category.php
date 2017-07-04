<?php
/**
 * Loop Name: List Posts by Category
 */
?>

<ul>
	<?php while( have_posts() ) : the_post(); ?>
		<li>
			<a href="<?php echo the_permalink(); ?>"><?php echo the_title(); ?></a>
			<span class="post-date"><small><?php echo get_the_date('F jS, Y'); ?></small></span>
		</li>
	<?php endwhile; ?>
</ul>


