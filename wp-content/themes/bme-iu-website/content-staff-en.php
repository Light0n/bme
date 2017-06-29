<?php
/**
 * Display staff content english
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>> 
    <div class="entry-main">
      <?php 
      // Create pod object
      //Pods way
      $staff = pods(get_post_type(),get_the_ID());
      if (!empty($staff)):?> 
         <header class="entry-header">
         	<?php if ( the_title( '', '', false ) && siteorigin_page_setting( 'page_title' ) ) : ?>    
         		<h1 class="entry-title"><?php the_title(); ?></h1>
				<?php endif; ?>               
         </header><!-- .entry-header -->
         <hr /> 
         <div class="entry-content">
         	<div class="su-row">    
					<div class="su-column su-column-size-1-4">
						<div class="su-column-inner su-clearfix">
							<?php  echo $staff->field('post_thumbnail');?>				
						</div>
					</div>
					<div class="su-column su-column-size-3-4">
						<div class="su-column-inner su-clearfix">
						<p>
							<?php
							echo do_shortcode('[icon name="user-circle-o" class="fa-1x"]').'&emsp;'.$staff->display('position'); 
							?><br>
							<?php
							echo do_shortcode('[icon name="envelope" class="fa-1x"]').'&emsp;'.$staff->display('email'); 
							?><br>	
							<?php
							echo do_shortcode('[icon name="phone" class="fa-1x"]').'&emsp;'.$staff->display('phone'); 
							?><br>
							<?php
							echo do_shortcode('[icon name="home" class="fa-1x"]').'&emsp;'.$staff->display('office_info'); 
							?><br>
							<?php
							echo do_shortcode('[icon name="search-plus" class="fa-1x"]').'&emsp;'.$staff->display('research_interest'); 
							?><br>
							<?php 
							echo do_shortcode('[su_button url="'.$staff->display('cv').'" target="blank" style="flat" background="#42a4d6" size="3" icon="icon: cloud-download" rel="lightbox"]Download CV[/su_button]' ); ?>
						</p>			
						</div>
					</div>
				</div>
				<hr />
         	<?php 
         	// echo $staff->display('post_content');
         	the_content();
            ?>
            <?php //the_content(); ?>
            </div><!-- .entry-content -->
        <?php endif; ?>
    </div> <!--entry-main-->
</article><!-- #post-<?php the_ID(); ?> -->
