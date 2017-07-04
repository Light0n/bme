<?php 
/*
Upcoming Events Shortcode Lists View
 */
function bme_get_events( $atts, $content = null ){
          // setup the query
      extract(shortcode_atts(array(
     "limit"                 => '',  
     "category"              => '',
     "grid"                  => '',
     "show_date"             => '',
     "show_category_name"    => '',
     "show_content"          => '',
     "show_full_content"     => '',
     "content_words_limit"   => '',
     "pagination_type"       => 'next-prev',
 ), $atts));
 
 // define limit
 if( $limit ) { 
     $posts_per_page = $limit; 
 } else {
     $posts_per_page = '-1';
 }
 
 if( $category ) { 
     $cat = explode(',', $category); 
 } else {
     $cat = '';
 }
 
 if( $grid ) { 
     $gridcol = '1'; 
 } else {
     $gridcol = '1';
 }
 
 if( $show_date ) { 
     $showdate = $show_date; 
 } else {
     $showdate = 'true';
 }
 
 if( $show_category_name ) { 
     $showcategory = $show_category_name; 
 } else {
     $showcategory = 'true';
 }
 
 if( $show_content ) { 
     $showcontent = $show_content; 
 } else {
     $showcontent = 'true';
 }
 
 if( $show_full_content ) { 
     $showfullcontent = $show_full_content; 
 } else {
     $showfullcontent = 'false';
 }
 
 if( $content_words_limit ) { 
     $words_limit = $content_words_limit; 
 } else {
     $words_limit = '20';
 }

 if($pagination_type == 'numeric'){

    $pagination_type = 'numeric';
 }else{

     $pagination_type = 'next-prev';
 }

 ob_start();
 
 global $paged;
 
 if(is_home() || is_front_page()) {
       $paged = get_query_var('page');
 } else {
      $paged = get_query_var('paged');
 }
 
 $args = array ( 
     	'post_type'      	=> 'event', 
     	'meta_key'       	=> 'start_date',
     	'orderby'   	 	=> 'meta_value',
      'order' 			 	=> 'ASC',
     	'posts_per_page' 	=> $posts_per_page,   
     	'paged'          	=> $paged,
     	'meta_query' 		=> array(
         array(
            'key' => 'start_date',
            'value' => date('Y-m-d-H-i'),
            'compare' => '>=', 
            'type' => 'DATE',
         ),
     	),
 );
 
 if($cat != ""){
     $args['tax_query'] = array( 
         array( 
             'taxonomy'  => 'event_category',
             'field'     => 'id', 
             'terms'     => $cat
         )
     );
 }        

 $query = new wp_query($args);

 global $post;
 $post_count = $query->post_count;
 $count = 0;
 if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
     $event = pods(get_post_type(), get_the_id());
     $count++;
     $terms = get_the_terms( $post->id, 'event_category' ); 
     $news_links = array();
     
     if($terms){

         foreach ( $terms as $term ) {
             $term_link = get_term_link( $term );
             $news_links[] = '<a href="' . esc_url( $term_link ) . '">'.'#'.$term->name.'</a>';
         }
     }
     
     $cate_name = join( ", ", $news_links );
     $css_class="team";

     if ( ( is_numeric( $grid ) && ( $grid > 0 ) && ( 0 == ($count - 1) % $grid ) ) || 1 == $count ) { $css_class .= ' first'; }
     if ( ( is_numeric( $grid ) && ( $grid > 0 ) && ( 0 == $count % $grid ) ) || $post_count == $count ) { $css_class .= ' last'; }
     if($showdate == 'true'){ $date_class = "has-date";}else{$date_class = "has-no-date";} ?>
     
     <div id="post-<?php the_id(); ?>" class="news type-news news-col-<?php echo $gridcol.' '.$css_class.' '.$date_class; ?>">
         
         <div class="news-thumb">
             
             <?php if ( has_post_thumbnail()) {
                 
                 if($gridcol == '1'){ ?>
                     
                     <div class="grid-news-thumb">
                     
                         <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('url'); ?></a>
                     </div>
                 <?php } else if($gridcol > '2') { ?>
                     
                     <div class="grid-news-thumb">   
                     
                         <a href="<?php the_permalink(); ?>">    <?php the_post_thumbnail('large'); ?></a>
                     </div>
                 <?php   } else { ?>
                     
                     <div class="grid-news-thumb">
                     
                         <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('large'); ?></a>
                     </div>
                 <?php } 
             } ?>
         </div>
         
         <div class="news-content">
             
             <?php if($gridcol == '1') {
                 
                 if($showdate == 'true'){ ?>
                     
                     <div class="date-post">
                     
                         <h2><span><?php echo date_i18n( 'j', strtotime( $event->display( 'start_date' ) ) ); ?></span></h2>                                                      
                         <p><?php echo date_i18n( 'm / y', strtotime( $event->display( 'start_date' ) ) ); ?></p>                           
                         <strong><?php echo date_i18n( 'h:i a', strtotime( $event->display( 'start_date' ) ) );?></strong>
                     </div>
                 <?php }?>
             <?php } else {  ?>
                 
                 <div class="grid-date-post">
                 
                     <?php echo ($showdate == "true")? get_the_date() : "" ;?>
                 
                     <?php echo ($showdate == "true" && $showcategory == "true" && $cate_name != '') ? " / " : "";?>
                 
                     <?php echo ($showcategory == 'true' && $cate_name != '') ? $cate_name : ""?>
                 </div>
             <?php  } ?>
             
             <div class="post-content-text">
                 
                 <?php the_title( sprintf( '<h3 class="news-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );   ?>
                 
                 <?php if($showcategory == 'true' && $gridcol == '1'){ ?>
                 
                     <div class="news-cat">
                     
                         <?php echo $cate_name; ?>
                     </div>
                 <?php }?>
                 <?php if($showcontent == 'true'){?>
                     <?php if (get_locale() == 'en_US') {
                         echo "<p><strong>Location: </strong>".substr($event->display('location'),3);
                     }else {
                         echo "<p><strong>Địa điểm: </strong>".substr($event->display('location'),3);
                     } ?>    
                  
                     <div class="news-content-excerpt">
                         

                         <?php  if($showfullcontent == "false" ) {
                             $excerpt = get_the_content(); ?>
                             
                             <div class="news-short-content">
                                 
                                 <?php echo string_limit_newswords( $post->id, $excerpt, $words_limit, '...'); ?>
                             </div>
                             
                             <a href="<?php the_permalink(); ?>" class="news-more-link"><?php _e( 'read more', 'sp-news-and-widget' ); ?></a>    
                         <?php } else { 
                         
                             the_content();
                         } ?>
                     </div><!-- .entry-content -->
                 <?php }?>
             </div>
         </div>
     </div><!-- #post-## -->
 <?php  endwhile; endif; ?>
 <?php do_action('vantage_entry_main_bottom') ?>  
 <?php vantage_content_nav( 'nav-below' ); ?>        
 <div class="news_pagination">
     
     <?php if($pagination_type == 'numeric'){ 

         echo news_pagination( array( 'paged' => $paged , 'total' => $query->max_num_pages ) );
     }else{ ?>
         
         <div class="button-news-p"><?php next_posts_link( ' next >>', $query->max_num_pages ); ?></div>
         
         <div class="button-news-n"><?php previous_posts_link( '<< previous' ); ?> </div>
     <?php } ?>
 </div><?php
 
 wp_reset_query(); 
             
 return ob_get_clean();
 }
add_shortcode('bme_display_events','bme_get_events');
 ?>