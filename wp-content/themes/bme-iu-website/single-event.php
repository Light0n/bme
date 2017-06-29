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
			<?php 
		      // Create pod object
		      $event = pods(get_post_type(),get_the_ID());
		      $terms = get_the_terms( $post->ID, 'event_category' ); 
	        	$news_links = array();	        
	        	if($terms){
	            foreach ( $terms as $term ) {
	               $term_link = get_term_link( $term );
	               $news_links[] = '<a href="' . esc_url( $term_link ) . '">'.'#'.$term->name.'</a>';
	            }
	        	}	        
	        	$cate_name = join( ", ", $news_links );?>
			
		    <div class="entry-main">
		    <?php do_action('vantage_entry_main_top') ?>
		      <header class="entry-header">
		      	<?php if ( has_post_thumbnail()):?>
		            <div class="entry-thumbnail">      
		               <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('url'); ?></a>
		            </div>
		         <?php endif; ?>		            
		         <?php if ( the_title( '', '', false ) && siteorigin_page_setting( 'page_title' ) ) : ?>  
		         	<div class="entry-categories">
		         		<?php if($terms){ ?>	                    
		                  <?php echo $cate_name; ?>
		            	<?php }?>		         		
		         	</div> 
		         	<h1 class="entry-title"><?php the_title(); ?></h1>      
		         <?php endif; ?> 
		   	</header><!-- .entry-header -->
		   	
		   	<div class="entry-content">
		   		<div class="su-row">    
						<div class="su-column su-column-size-1-2">
							<div class="su-column-inner su-clearfix">
								<!-- Location -->
                        <?php if (get_locale() == 'en_US') {
                            echo "<strong>Location </strong><br>".substr($event->display('location'),3,-3);
                        }else {
                            echo "<strong>Địa điểm </strong><br>".substr($event->display('location'),3,-3);
                        } ?>
		   					<!-- .Location -->						
							</div>
						</div>
						<div class="su-column su-column-size-1-2">
							<div class="su-column-inner su-clearfix">
							<!-- Date & Time -->
				   		<?php if (get_locale() == 'en_US') {
				   			echo "<strong>Date & Time </strong><br>";
				   		}else {
				   			echo "<strong>Thời Gian </strong><br>";
				   		}?>
				   		<?php 
				   		if($event->display( 'start_date' ) <= $event->display( 'end_date' )){
				   			$start = date_create(date_i18n( 'Y/m/j', strtotime( $event->display( 'start_date' ) ) ));
				   			$end = date_create(date_i18n( 'Y/m/j', strtotime( $event->display( 'end_date' ) ) ));
				   			if ($start == $end) { // One day event
				   				echo date_i18n( 'j/m/Y, h:i A', strtotime( $event->display( 'start_date' ) ) );
				   				echo date_i18n( ' – h:i A', strtotime( $event->display( 'end_date' ) ) );	
				   			}elseif($start < $end){ // Many days event
				   				echo date_i18n( 'j/m/Y, h:i A', strtotime( $event->display( 'start_date' ) ) );
				   				echo date_i18n( ' – j/m/Y, h:i A', strtotime( $event->display( 'end_date' ) ) );
				   			}
				   		}
				   		?>	
				   		<!-- .Date & Time -->
							</div>
						</div>
					</div>
					<hr>
		         <?php the_content();	 ?>  	        
		      </div><!-- #post-## -->	
	      <?php do_action('vantage_entry_main_bottom') ?>  
		    </div> <!--entry-main-->
		</article><!-- #post-<?php the_ID(); ?> -->
	</div><!-- #content .site-content -->
</div><!-- #primary .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>


