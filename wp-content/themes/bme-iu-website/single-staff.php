<?php
/**
 * The Template for displaying all single posts.
 *
 * @package vantage
 * @since vantage 1.0
 * @license GPL 2.0
 */

get_header(); ?>



<div id="primary" class="content-area">
	<div id="content" class="site-content" role="main">
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
									if ($staff->display('research_interest')) {
										if (get_locale() == 'en_US') { 
											echo "<strong>Research Interests: </strong>";
										} else
										{
											echo "<strong>Hướng nghiên cứu: </strong>";
										}
										echo $staff->display('research_interest');
									} 
									?><br>
									<?php 
									if ($staff->display('cv')) {
										echo do_shortcode('[su_button url="'.$staff->display('cv').'" target="blank" style="flat" background="#42a4d6" size="3" icon="icon: cloud-download" rel="lightbox"]Download CV[/su_button]' ); 
									}
									?>
								</p>			
								</div>
							</div>
						</div>
		         	<?php 
		         	// echo $staff->display('post_content');
		         	if (get_the_content()) {
		         		echo "<hr/>";
		         		the_content();
		         	}
		            ?>
		            <?php //the_content(); ?>
		            </div><!-- .entry-content -->
		        <?php endif; ?>
		    </div> <!--entry-main-->
		    <hr/>
			<div class="so-panel widget widget_siteorigin-panels-postloop panel-first-child panel-last-child"  >
			<h3 class="widget-title"><span class="vantage-carousel-title"><span class="vantage-carousel-title-text"></span><a href="#" class="next" title="Next"><span class="vantage-icon-arrow-right"></span></a><a href="#" class="previous" title="Previous"><span class="vantage-icon-arrow-left"></span></a></span></h3>
			<div class="vantage-carousel-wrapper">

				<?php $args = array(
				  		'post_type'   => 'staff',
				  		'post_status' => 'publish',
				  		'posts_per_page ' => 30,
				  		'post__not_in' => array(get_the_ID())
				  		);
					// Query all other staffs
			 		$other_staffs = new WP_Query( $args ); ?>

				<ul class="vantage-carousel" data-query="<?php echo esc_attr(json_encode( $args )) ?>" data-ajax-url="<?php echo esc_url( admin_url('admin-ajax.php') ) ?>">
					<?php while( $other_staffs->have_posts() ) : $other_staffs->the_post(); ?>
						<li class="carousel-entry">
						<?php 
						$image = wp_get_attachment_image_src( get_post_thumbnail_id() );
						//get staff object for current item
						$staff = pods(get_post_type(), get_the_ID());
						// $image =  array($staff->field('post_thumbnail_url'));
						$text = '<strong>'.$staff->display('position').'</strong><br>Email: <a>'.$staff->display('email').'</a>';
						the_widget(
							'Vantage_CircleIcon_Widget',
							array(
								'image' => !empty($image[0]) ? $image[0] : false,
								'title' => get_the_title(),
								'text' => $text,
								// 'more' => siteorigin_setting( 'blog_read_more' ) ? esc_html( siteorigin_setting( 'blog_read_more' ) ) : __( 'Continue reading', 'vantage' ),
								'more_url' => get_permalink(),
								'all_linkable' => true,
								'icon_position' => 'top',
								'icon_size' => 'large',
								'title_color' => '',
								'text_color' => '',
							)
						);
						?>	
						</li>
					<?php endwhile; ?>
				</ul>
			</div>
		</div>		    
		
		</article><!-- #post-<?php the_ID(); ?> -->
	</div><!-- #content .site-content -->
</div><!-- #primary .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>


