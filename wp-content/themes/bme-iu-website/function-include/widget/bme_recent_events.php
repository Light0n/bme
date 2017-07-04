<?php 
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

 ?>