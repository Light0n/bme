<?php
/*
Template Name: Staffs Page Template
*/
 
get_header();
$html_content = [
    'su-1-row-2-columns-centered-begin' => '<div class="su-row">      
<div class="su-column su-column-size-1-6"> &emsp; </div>',
    'su-1-row-2-columns-centered-end'   => '<div class="su-column su-column-size-1-6"> &emsp; </div>
</div>',
    'su-1-row-begin' => '<div class="su-row">',
    'su-1-row-end' => '</div>',
  ];
?>

<div id="primary" class="content-area">
  <div id="content" class="site-content" role="main">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <div class="entry-main">
        <?php do_action('vantage_entry_main_top') ?>
        <?php if ( ( the_title( '', '', false ) && siteorigin_page_setting( 'page_title' ) ) || ( has_post_thumbnail() && siteorigin_page_setting( 'featured_image' ) ) ) : ?>
          <header class="entry-header">
            <?php if ( has_post_thumbnail() && siteorigin_page_setting( 'featured_image' ) ) : ?>
              <div class="entry-thumbnail"><?php vantage_entry_thumbnail(); ?></div>
            <?php endif; ?>
            <?php if ( the_title( '', '', false ) && siteorigin_page_setting( 'page_title' ) ) : ?>
              <h1 class="entry-title"><?php the_title(); ?></h1>
            <?php endif; ?>
          </header><!-- .entry-header -->
        <?php endif; ?>
        <div class="entry-content">
        <?php 
          $args = array(
            'post_type'      => 'staff',
            'post_status'    => 'publish',
            'posts_per_page' => -1, 
            'orderby'        => 'menu_order',
            'order'          => 'ASC'
          );
          // Query all other staffs
          $staffs = new WP_Query( $args ); 
          //echo $staffs->found_posts; //number of posts found
          $col_mode    = 0;
          $col_current = 0;
          if (!empty($staffs)):
            while( $staffs->have_posts() ) : $staffs->the_post();
              //get staff object for current item
              $staff = pods(get_post_type(), get_the_ID());
              // $position = $staff->display('position');
              // $two_cols_arr = array("truong-bo-mon","chair");
              // echo $staff->display('menu_order') - 1;
              // if (in_array(sanitize_title($position),$two_cols_arr)) {
              //     echo "Here is truong-bo-mon";
              // }
              $col_current++; //has staff
              if ($staff->field('menu_order') == 0 && $col_current == 1){
                $col_mode = 2;
                echo $html_content['su-1-row-2-columns-centered-begin'];
              }else if ($staff->field('menu_order') > 1 && $col_current == 1) {
                $col_mode = 3;
                echo $html_content['su-1-row-begin'];
              } 

              get_template_part('content/staff-circle');

              if ($col_current == $col_mode) {//end of row 
                if ($col_mode == 2) {
                  echo $html_content['su-1-row-2-columns-centered-end'];
                  echo "<hr>";
                  $col_current = 0;
                }else if ($col_mode == 3) {
                  echo $html_content['su-1-row-end'];

                  $col_current = 0;
                }
              } else if (($staffs->current_post +1) == ($staffs->post_count) && $col_current == 2) { //last staff and 2 columns -> add one more column before close row
                  echo $html_content['su-1-row-2-columns-centered-end'];
              }
              ?> 
            <?php endwhile; ?>   
          <?php endif; ?>       
        </div><!-- .entry-content -->
      </div>
    </article><!-- #post-<?php the_ID(); ?> -->
  </div><!-- #content -->
</div><!-- #primary -->   
 
<?php get_sidebar(); ?>
<?php get_footer(); ?>