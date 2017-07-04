<?php 
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
        include(locate_template('content/student.php'));
    else
        include(locate_template('content/alumni.php'));
    
    
    ?>
    <?php
    wp_reset_query(); 
    return ob_get_clean();
}
add_shortcode( 'bme_students', 'bme_student_shortcode' );
 ?>