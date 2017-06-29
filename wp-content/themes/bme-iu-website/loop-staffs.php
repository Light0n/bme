<?php
/**
 * Loop Name: Staffs
 */
?>
<?php if ( have_posts() ) : ?>

    <?php /* Start the Loop */ ?>
    <?php while ( have_posts() ) : the_post(); ?>

    

    <?php endwhile; ?>
<div class="su-row">    
<div class="su-column su-column-size-1-2"><div class="su-column-inner su-clearfix">1Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis quam nibh, euismod eget nulla a, tempor scelerisque lorem. Proin dignissim arcu tristique fermentum ullamcorper. Integer lacinia scelerisque enim eu pretium. Nam elementum turpis orci, ac porttitor diam suscipit sit amet. </div></div>

<div class="su-column su-column-size-1-2"><div class="su-column-inner su-clearfix"> 2Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis quam nibh, euismod eget nulla a, tempor scelerisque lorem. Proin dignissim arcu tristique fermentum ullamcorper. Integer lacinia scelerisque enim eu pretium. Nam elementum turpis orci, ac porttitor diam suscipit sit amet. </div></div>
</div>
    <?php vantage_content_nav( 'nav-below' ); ?>

<?php else : ?>

    <?php get_template_part( 'no-results', 'index' ); ?>

<?php endif; ?>
