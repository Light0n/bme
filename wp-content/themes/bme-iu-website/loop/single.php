<?php
/**
 * Loop Name: Display Single Post
 */
?>
<?php if ( have_posts() ) : ?>

	<?php /* Start the Loop */ ?>
	<?php the_post(); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>

		<div class="entry-main">

			<?php do_action('vantage_entry_main_top') ?>

			<?php if ( ( the_title( '', '', false ) && siteorigin_page_setting( 'page_title' ) ) || ( has_post_thumbnail() && siteorigin_setting('blog_featured_image') ) || ( siteorigin_setting( 'blog_post_metadata' ) && get_post_type() == 'post' ) ) : ?>
				<header class="entry-header">

					<?php if( has_post_thumbnail() && siteorigin_setting('blog_featured_image') ): ?>
						<div class="entry-thumbnail"><?php vantage_entry_thumbnail(); ?></div>
					<?php endif; ?>

					<?php if ( the_title( '', '', false ) && siteorigin_page_setting( 'page_title' ) ) : ?>
						<h1 class="entry-title"><?php the_title(); ?></h1>
					<?php endif; ?>

					<?php if ( siteorigin_setting( 'blog_post_metadata' ) && get_post_type() == 'post' ) : ?>
						<div class="entry-meta">
							<?php vantage_posted_on(); ?>
						</div><!-- .entry-meta -->
					<?php endif; ?>

				</header><!-- .entry-header -->
			<?php endif; ?>

			<div class="entry-content">
				<?php the_content(); ?>
			</div><!-- .entry-content -->

			<?php if( vantage_get_post_categories() && ! is_singular( 'jetpack-testimonial' ) ) : ?>
				<div class="entry-categories">
					<?php echo vantage_get_post_categories() ?>
				</div>
			<?php endif; ?>


			<?php do_action('vantage_entry_main_bottom') ?>

		</div>

	</article><!-- #post-<?php the_ID(); ?> -->	

<?php else : ?>

	<?php get_template_part( 'no-results', 'index' ); ?>

<?php endif; ?>