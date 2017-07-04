<?php 
function bme_display_shortcode( $atts, $content = null  ) {

 // setup the query
            extract(shortcode_atts(array(
        "type"                  => '',
        "taxonomy"              => '',
        "limit"                 => '',  
        "category"              => '',
        "tags"                  => '',
        "tag_logic"             => '',
        "grid"                  => '',
        "show_date"             => '',
        "show_category_name"    => '',
        "show_content"          => '',
        "show_full_content"     => '',
        "content_words_limit"   => '',
        "pagination_type"       => 'next-prev',
    ), $atts));
    
    // Define limit
    
    // Start Addition
    if ($type) {
        $post_type = $type;
    }else{
        $post_type = 'post';
    }

    if ($taxonomy) {
        $taxono = $taxonomy;
    }else{
        $taxono = '';
    }
    // End Addition
    
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

    if( $tags) { 
        $tag = explode(',', $tags); 
    } else {
        $tag = '';
    }
    
    if ($tag_logic) {
        $tag_operator = $tag_logic;
    }else{
        $tag_operator = 'AND';
    }

    if( $grid ) { 
        $gridcol = $grid; 
    } else {
        $gridcol = '1';
    }
    
    if( $show_date ) { 
        $showDate = $show_date; 
    } else {
        $showDate = 'true';
    }
    
    if( $show_category_name ) { 
        $showCategory = $show_category_name; 
    } else {
        $showCategory = 'true';
    }
    
    if( $show_content ) { 
        $showContent = $show_content; 
    } else {
        $showContent = 'true';
    }
    
    if( $show_full_content ) { 
        $showFullContent = $show_full_content; 
    } else {
        $showFullContent = 'false';
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
    
    $orderby        = 'post_date';
    $order          = 'DESC';
    if ($post_type == "event") {
        $args = array ( 
        'post_type'         => 'event', 
        'meta_key'          => 'start_date',
        'orderby'           => 'meta_value',
        'order'               => 'ASC',
        'posts_per_page'    => $posts_per_page,   
        'paged'             => $paged,
        'meta_query'        => array(
            array(
                'key' => 'start_date',
                'value' => date('Y-m-d-H-i'),
                'compare' => '>=', 
                'type' => 'DATE',
            ),
        ),
        );
        $gridcol = '1'; // Force Events layout = 1
    }else{
        $args = array ( 
        'post_type'      => $post_type, 
        'orderby'        => $orderby, 
        'order'          => $order,
        'posts_per_page' => $posts_per_page,   
        'paged'          => $paged,
        );
    }             
    
    if($cat != ""){
        $args['tax_query'] = array( 
            'relation'      => 'AND',
            array( 
                'taxonomy'  => $taxono,
                'field'     => 'id', 
                'terms'     => $cat
            ),
            ($tags? array( // for Tags
                'taxonomy'  => 'post_tag',
                'field'     => 'id', 
                'terms'     => $tag,
                'operator'  => $tag_operator,
            ): ''),
        );
    }        
   
    $query = new WP_Query($args);
   
    global $post;
    $post_count = $query->post_count;
    $count = 0;
    if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
        if ($post_type == "event") {
            $event = pods(get_post_type(), get_the_id());
        }
        $count++;
        // $terms = get_the_terms( $post->ID, 'news-category' ); // delete
        $terms = get_the_terms( $post->ID, $taxono ); // add
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
        if($showDate == 'true'){ $date_class = "has-date";}else{$date_class = "has-no-date";} ?>
    
        <div id="post-<?php the_ID(); ?>" class="news type-news news-col-<?php echo $gridcol.' '.$css_class.' '.$date_class; ?>">
            
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
                    
                    if($showDate == 'true'){ ?>
                        
                        <div class="date-post">
                            <?php if ($post_type == "event"): ?>
                                <h2><span><?php echo date_i18n( 'j', strtotime( $event->display( 'start_date' ) ) ); ?></span></h2>                                                      
                                <p><?php echo date_i18n( 'm / y', strtotime( $event->display( 'start_date' ) ) ); ?></p>                           
                                <strong><?php echo date_i18n( 'h:i a', strtotime( $event->display( 'start_date' ) ) );?></strong>       
                            <?php else: ?>
                                <h2><span><?php echo get_the_date('j'); ?></span></h2>
                        
                                <p><?php echo get_the_date('M y'); ?></p> 
                            <?php endif ?>
                        </div>
                    <?php }?>
                <?php } else {  ?>
                    
                    <div class="grid-date-post">
                    
                        <?php echo ($showDate == "true")? get_the_date() : "" ;?>
                    
                        <?php echo ($showDate == "true" && $showCategory == "true" && $cate_name != '') ? " / " : "";?>
                    
                        <?php echo ($showCategory == 'true' && $cate_name != '') ? $cate_name : ""?>
                    </div>
                <?php  } ?>
                
                <div class="post-content-text">
                    
                    <?php the_title( sprintf( '<h3 class="news-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );   ?>
                    
                    <?php if($showCategory == 'true' && $gridcol == '1'){ ?>
                    
                        <div class="news-cat">
                                    <strong><?php echo $cate_name; ?></strong>                            
                        </div>
                    <?php }?>

                    <?php if($showContent == 'true'){?>
                        <?php if ($post_type == "event"): ?>
                            <?php if (get_locale() == 'en_US') {
                                echo "<p><strong>Location: </strong>".substr($event->display('location'),3);
                            }else {
                                echo "<p><strong>Địa điểm: </strong>".substr($event->display('location'),3);
                            } ?>
                        <?php endif ?>
                        <div class="news-content-excerpt">
                        
                            <?php  if($showFullContent == "false" ) {
                                $excerpt = get_the_content(); ?>
                                
                                <div class="news-short-content">
                                    
                                    <?php echo string_limit_newswords( $post->ID, $excerpt, $words_limit, '...'); ?>
                                </div>
                                
                                <a href="<?php the_permalink(); ?>" class="news-more-link"><?php _e( 'Read More', 'sp-news-and-widget' ); ?></a>    
                            <?php } else { 
                            
                                the_content();
                            } ?>
                        </div><!-- .entry-content -->
                    <?php }?>
                </div>
            </div>
        </div><!-- #post-## -->
    <?php  endwhile; endif; ?>
            
    <div class="news_pagination">
        
        <?php if($pagination_type == 'numeric'){ 

            echo news_pagination( array( 'paged' => $paged , 'total' => $query->max_num_pages ) );
        }else{ ?>
            
            <div class="button-news-p"><?php next_posts_link( ' Next >>', $query->max_num_pages ); ?></div>
            
            <div class="button-news-n"><?php previous_posts_link( '<< Previous' ); ?> </div>
        <?php } ?>
    </div><?php
    
    wp_reset_query(); 
                
    return ob_get_clean();

}
add_shortcode( 'bme_display', 'bme_display_shortcode' );
 ?>