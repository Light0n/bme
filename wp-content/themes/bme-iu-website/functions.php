<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_parent_css' ) ):
    function chld_thm_cfg_parent_css() {
        wp_enqueue_style( 'chld_thm_cfg_parent', trailingslashit( get_template_directory_uri() ) . 'style.css', array(  ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'chld_thm_cfg_parent_css', 10 );

// END ENQUEUE PARENT ACTION

// DISABLE PARENT MENU ITEMS
function jqueryscript_in_head(){ ?>
<script type="text/javascript">
var $j = jQuery.noConflict();
$j(document).ready(function () {
$j("li:has(ul)").children("a").click(function () {
    return false;
});  });
</script>
<?php }
add_action('wp_head', 'jqueryscript_in_head');
// Include Tags and Categories in CPT
function wpa_cpt_tags( $query ) {
    if ( $query->is_category() || $query->is_tag() && $query->is_main_query() ) {
        $query->set( 'post_type', array( 'post', 'staff' ) );
    }
}
add_action( 'pre_get_posts', 'wpa_cpt_tags' );

/**
 * Render the slider.
 */
function vantage_render_slider(){

    if( is_front_page() && !in_array( siteorigin_setting( 'home_slider' ), array( '', 'none' ) ) ) {
        $settings_slider = siteorigin_setting( 'home_slider' );
        $slider_stretch = siteorigin_setting( 'home_slider_stretch' );

        if( ! empty( $settings_slider ) ) {
            $slider = $settings_slider;
        }
    }
    else {
        $page_id = get_the_ID();
        $is_wc_shop = vantage_is_woocommerce_active() && is_woocommerce() && is_shop();
        if ( $is_wc_shop ) {
            $page_id = wc_get_page_id( 'shop' );
        }
        if( ( is_page() || $is_wc_shop ) && get_post_meta($page_id, 'vantage_metaslider_slider', true) != 'none' ) {
            $page_slider = get_post_meta($page_id, 'vantage_metaslider_slider', true);
            if( !empty($page_slider) ) {
                $slider = $page_slider;
            }
            $slider_stretch = get_post_meta($page_id, 'vantage_metaslider_slider_stretch', true);
        }
    }

    if( empty($slider) ) return;

    global $vantage_is_main_slider;
    $vantage_is_main_slider = true;

    ?><div id="main-slider" <?php if( $slider_stretch ) echo 'data-stretch="true"' ?>><?php


    if($slider == 'demo') get_template_part('slider/demo');
    elseif (get_bloginfo('language') == "vi") {
        echo do_shortcode( "[metaslider id=338]" );
    }
    elseif( substr($slider, 0, 5) == 'meta:' ) {
        list($null, $slider_id) = explode(':', $slider);

        echo do_shortcode( "[metaslider id=" . intval($slider_id) . "]" );
    }

    ?></div><?php
    $vantage_is_main_slider = false;
}

add_filter('wpseo_breadcrumb_single_link' ,'timersys_remove_companies', 10 ,2);
function timersys_remove_companies($link_output, $link ){
 
    if( $link['text'] == 'Staffs') {
        if (get_locale() == 'en_US' ) {
            $link_output = '<a>About</a> » <a>Faculty</a>';
        }else
            $link_output = '<a>Giới thiệu</a> » <a>Nhân sự</a>';   
    }elseif ($link['text'] == 'Events') {
        // echo $link['text'];
        // echo $link_output;
        if (get_locale() == 'en_US' ) {
            $link_output = '<a>News & Events</a> » <a>Events</a>';
        }else
            $link_output = '<a>Thông tin & Sự kiện </a> » <a>Sự Kiện</a>';
    }
    return $link_output;
}

/**
 * Recent Scrolling News widget.
 *
 * @since 2.8.0
 *
 * @see WP_Widget
 */
class WP_Widget_Recent_News extends WP_Widget {

    /**
     * Sets up a new Recent News widget instance.
     *
     * @since 2.8.0
     * @access public
     */
    public function __construct() {
        $widget_ops = array(
            'classname' => 'widget_recent_entries',
            'description' => __( 'Your site&#8217;s most recent News.' ),
            'customize_selective_refresh' => true,
        );
        parent::__construct( 'recent-news', __( 'News' ), $widget_ops );
        $this->alt_option_name = 'widget_recent_entries';
    }

    /**
     * Outputs the content for the current Recent News widget instance.
     *
     * @since 2.8.0
     * @access public
     *
     * @param array $args     Display arguments including 'before_title', 'after_title',
     *                        'before_widget', and 'after_widget'.
     * @param array $instance Settings for the current Recent News widget instance.
     */
    public function widget( $args, $instance ) {
        if ( ! isset( $args['widget_id'] ) ) {
            $args['widget_id'] = $this->id;
        }

        $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'News' );

        /** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

        $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
        if ( ! $number )
            $number = 5;
        $show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;

        /**
         * Filters the arguments for the Recent News widget.
         *
         * @since 3.4.0
         *
         * @see WP_Query::get_posts()
         *
         * @param array $args An array of arguments used to retrieve the recent news.
         */
        $r = new WP_Query( apply_filters( 'widget_posts_args', array(
            'posts_per_page'      => $number,
            'category_name'          => 'news',
            'no_found_rows'       => true,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true
        ) ) );

        if ($r->have_posts()) :
        ?>
        <?php echo $args['before_widget']; ?>
        <?php if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        } ?>
        
        <?php
            //$no_p = '';
        //if($date == "false" && $show_category == "false"){ 
          //      $no_p = "no_p";}
        ?>
        <div class="recent-news-items-scroll <?php //echo $no_p;?>">
        <div class="newsticker-jcarousellite">
        
        <ul>
        <?php while ( $r->have_posts() ) : $r->the_post(); ?>
        	<?php 
        		$terms = get_the_terms( $post->ID, 'category' ); 
	        	$news_links = array();	        
	        	if($terms){
	            foreach ( $terms as $term ) {
	               $term_link = get_term_link( $term );
	               $news_links[] = '<a href="' . esc_url( $term_link ) . '">'.'#'.$term->name.'</a>';
	            }
	        	}	        
	        	$cate_name = join( ", ", $news_links );?>
            <li class="news_li">
            <?php echo get_the_date();?><br>
               <strong><a class="newspost-title" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"> <?php get_the_title() ? the_title() : the_ID(); ?></a></strong>
               <?php if (has_tag('Hot')): ?>
               	<img src="http://bme.hcmiu.edu.vn/wp-content/uploads/2017/04/icon_hot.gif">
               <?php endif ?>
            <?php if ( $show_date ) : ?>
                <div class="widget-date-post">
                <strong><?php echo $cate_name ?></strong>
                </div>
            <?php endif; ?>
            </li>
            <!-- Content inside loop -->
        <?php endwhile; ?>
        </ul>
        </div>
        </div>
        <script>
            jQuery(function() {
                jQuery('.newsticker-jcarousellite').vTicker(
                {
                    speed: 1000,
                    height: 400,
                    padding:10,
                    pause: 1000
                });
            });
        </script>
        <?php echo $args['after_widget']; ?>
        <?php
        // Reset the global $the_post as this query will have stomped on it
        wp_reset_postdata();

        endif;
    }

    /**
     * Handles updating the settings for the current Recent Newss widget instance.
     *
     * @since 2.8.0
     * @access public
     *
     * @param array $new_instance New settings for this instance as input by the user via
     *                            WP_Widget::form().
     * @param array $old_instance Old settings for this instance.
     * @return array Updated settings to save.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        $instance['number'] = (int) $new_instance['number'];
        $instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
        return $instance;
    }

    /**
     * Outputs the settings form for the Recent News widget.
     *
     * @since 2.8.0
     * @access public
     *
     * @param array $instance Current settings.
     */
    public function form( $instance ) {
        $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
?>
        <p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

        <p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of news to show:' ); ?></label>
        <input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" /></p>

        <p><input class="checkbox" type="checkbox"<?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
        <label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display news date?' ); ?></label></p>
<?php
    }
}
function register_list_news_widgets() {
    register_widget( 'WP_Widget_Recent_News' );
}
add_action( 'widgets_init', 'register_list_news_widgets' );

/**
 * Core class used to implement a Recent Events widget.
 *
 * @since 2.8.0
 *
 * @see WP_Widget
 */
class WP_Widget_Recent_Events extends WP_Widget {

	/**
	 * Sets up a new Recent Events widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'widget_recent_entries',
			'description' => __( 'Your site&#8217;s most recent Events.' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'recent-events', __( 'Events' ), $widget_ops );
		$this->alt_option_name = 'widget_recent_entries';
	}

	/**
	 * Outputs the content for the current Recent Events widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Recent Events widget instance.
	 */
	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Events' );

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number )
			$number = 5;
		$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;

		/**
		 * Filters the arguments for the Recent Events widget.
		 *
		 * @since 3.4.0
		 *
		 * @see WP_Query::get_posts()
		 *
		 * @param array $args An array of arguments used to retrieve the recent events.
		 */
		$r = new WP_Query( apply_filters( 'widget_posts_args', array(
			'post_type' 			=> 'event',
			'meta_key'       		=> 'start_date',
         'orderby'   			=> 'meta_value',
         'order' 				 	=> 'ASC',
			'posts_per_page'     => $number,
			'no_found_rows'      => true,
			'post_status'        => 'publish',
			'meta_query' => array(
              array(
                'key' => 'start_date',
                'value' => date('Y-m-d-H-i'),
                'compare' => '>=', 
                'type' => 'DATE',
              ),
        ),
		) ) );

		if ($r->have_posts()) :
		?>
		<?php echo $args['before_widget']; ?>
		<?php if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		} ?>
		<ul>
		<?php while ( $r->have_posts() ) : $r->the_post(); ?>
			<?php $event = pods(get_post_type(), get_the_ID()); 
				$terms = get_the_terms( $post->ID, 'event_category' ); 
	        	$news_links = array();	        
	        	if($terms){
	            foreach ( $terms as $term ) {
	               $term_link = get_term_link( $term );
	               $news_links[] = '<a href="' . esc_url( $term_link ) . '">'.'#'.$term->name.'</a>';
	            }
	        	}	        
	        	$cate_name = join( ", ", $news_links );?>
	      <li class="news_li">
            <strong><a class="newspost-title" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></strong>
				<div class="widget-date-post">
					
					<?php echo date_i18n( 'M j, Y, h:i A', strtotime( $event->display( 'start_date' ) ) );?><br>
					<?php 
						$first_line_end = strpos($event->display('location'),"<br />");
						if ($first_line_end) {
							echo substr($event->display('location'), 3, $first_line_end);
						}else{
							echo substr($event->display('location'), 3,-3).'<br>';
						}
					?>
					<br>
					<?php 	if($terms): ?>
						<strong><?php echo $cate_name; ?></strong>
					<?php endif; ?>
				</div>
         </li>
		<?php endwhile; ?>
		</ul>
		<?php echo $args['after_widget']; ?>
		<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;
	}

	/**
	 * Handles updating the settings for the current Recent Events widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['number'] = (int) $new_instance['number'];
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
		return $instance;
	}

	/**
	 * Outputs the settings form for the Recent Events widget.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of events to show:' ); ?></label>
		<input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" /></p>

		<p><input class="checkbox" type="checkbox"<?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display events date?' ); ?></label></p>
<?php
	}
}
function register_list_events_widgets() {
    register_widget( 'WP_Widget_Recent_Events' );
}
add_action( 'widgets_init', 'register_list_events_widgets' );

/*
-------------------------------------------------------------------------------------------------------------------------
 */
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

/*

 */
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

function bme_student_shortcode( $atts, $content = null  ) {

 // setup the query
            extract(shortcode_atts(array(
        "type"                  => '',
        "degree"                => '',
        "academic_year"         => '',
        "graduation_year"       => '',
        "limit"                 => '',  
        "research_interest"     => '',
        "employer"              => '',
        "tag_logic"             => '',
        "grid"                  => '',
        // "show_category_name"    => '',
        // "show_content"          => '',
        // "show_full_content"     => '',
        // "content_words_limit"   => '',
        "pagination_type"       => 'next-prev',
    ), $atts));
    
    // Define limit
    
    // Start Addition
    if ($type) {
        $post_type = $type;
    }else{
        $post_type = 'student';
    }

    // End Addition
    
    if( $limit ) { 
        $posts_per_page = $limit; 
    } else {
        $posts_per_page = '-1';
    }
    
    if( $employer ) { 
        $employer_ids = explode(',', $employer); 
    } 

    if( $research_interest) { 
        $interest = explode(',', $research_interest); 
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

    if($pagination_type == 'numeric'){

       $pagination_type = 'numeric';
    }else{

        $pagination_type = 'next-prev';
    }

    //If alumni, eliminate all students not in graduation years taxonomy
    if ($post_type == 'alumni' && $graduation_year == 0) {
        $graduation_years = get_terms(
            array(
                'taxonomy'      => 'graduation_year', 
                'hide_empty'    => false,
            )
        ); 
        $graduation_year = [];
        if ( ! empty( $graduation_years ) && ! is_wp_error( $graduation_years ) ){
            foreach ( $graduation_years as $year ) {
                $graduation_year[] = $year->term_id;
            }
        }
    }
    // End filter alumni
    ob_start();
    
    global $paged;
    
    if(is_home() || is_front_page()) {
          $paged = get_query_var('page');
    } else {
         $paged = get_query_var('paged');
    }
    
    // $orderby        = 'student_id';
    // $order          = 'DESC';
    $args = array ( 
        'post_type'         => 'student', 
        'meta_key'          => 'student_id',
        'orderby'           => 'meta_value',
        'order'             => 'ASC',
        'posts_per_page'    => $posts_per_page,   
        'paged'             => $paged,
    );          
    
        $args['tax_query'] = array( 
            'relation'      => 'AND',
            ($degree? array( // for Degree
                'taxonomy'  => 'degree',
                'field'     => 'id', 
                'terms'     => $degree,
            ): ''),
            ($academic_year? array( // for Academic Year
                'taxonomy'  => 'academic_year',
                'field'     => 'id', 
                'terms'     => $academic_year,
            ): ''),
            ($graduation_year? array( // for Graduation Year
                'taxonomy'  => 'graduation_year',
                'field'     => 'id', 
                'terms'     => $graduation_year,
            ): ''),
            ($interest? array( // for Research Interest
                'taxonomy'  => 'research_interest',
                'field'     => 'id', 
                'terms'     => $interest,
            ): ''),
            ($employer_ids? array( // for Employer
                'taxonomy'  => 'employer',
                'field'     => 'id', 
                'terms'     => $employer_ids,
            ): ''),
        );
    $query = new WP_Query($args);
   
    global $post;
    if ($post_type == 'student') 
        include(locate_template('child-templates/undergraduate-student-content.php'));
    else
        include(locate_template('child-templates/alumni-content.php'));
    
    
    ?>
    <?php
    wp_reset_query(); 
    return ob_get_clean();
}
add_shortcode( 'bme_student', 'bme_student_shortcode' );

function add_custom_footer(){
   ?>
    <a href="#" id="scroll-to-top" class="scroll-to-top" title="Back To Top"><span class="vantage-icon-arrow-up"></span></a><link rel='stylesheet' id='su-box-shortcodes-css'  href='http://bme.hcmiu.edu.vn/wp-content/plugins/shortcodes-ultimate/assets/css/box-shortcodes.css?ver=4.9.9' type='text/css' media='all' />
    <link rel='stylesheet' id='su-media-shortcodes-css'  href='http://bme.hcmiu.edu.vn/wp-content/plugins/shortcodes-ultimate/assets/css/media-shortcodes.css?ver=4.9.9' type='text/css' media='all' />
    <script type='text/javascript' src='http://bme.hcmiu.edu.vn/wp-includes/js/wp-embed.min.js?ver=4.7.5'></script>
    <script type='text/javascript'>
    /* <![CDATA[ */
    var su_other_shortcodes = {"no_preview":"This shortcode doesn't work in live preview. Please insert it into editor and preview on the site."};
    /* ]]> */
    </script>
    <script type='text/javascript' src='http://bme.hcmiu.edu.vn/wp-content/plugins/shortcodes-ultimate/assets/js/other-shortcodes.js?ver=4.9.9'></script>
<?php } 
add_action('wp_footer', 'add_custom_footer');

function my_custom_js() {
    echo '<script src="http://bme.hcmiu.edu.vn/wp-content/themes/bme-iu-website/js/Chart.min.js"></script>';
}
// Add hook for front-end <head></head>
add_action('wp_head', 'my_custom_js');