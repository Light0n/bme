<?php 
/*
Template Name: Full-width layout
Template Post Type: post, staff, page
 */
get_header();?>


<div id="primary" class="content-area">
    <div id="content" class="site-content" role="main">

<?php
// Get 'team' posts
$pods = pods( 'staff',array(
  'limit' => 2, 
) );
// var_dump($pods);
 
if ( $pods->total() > 0 ):
?>
<section class="row profiles">
  <div class="intro">
    <h2>Meet The Team</h2>
    <p class="lead">&ldquo;Individuals can and do make a difference, but it takes a team<br>to really mess things up.&rdquo;</p>
  </div>
  
  <?php 
  while ( $pods->fetch() ): 
  
  // Resize and CDNize thumbnails using Automattic Photon service
  $thumb_src = $pods->field('image');
    // run the value of the field through pods_image() and set params using the "ID" of $pic
  $thumb_src = pods_image ( $pic['ID'], $size = 'thumbnail', $default = 0, $attributes = '', $force = false, $class = 'img-circle' );
  ?>
  <article class="col-sm-6 profile">
    <div class="profile-header">
      
      <?php $picture = $pods->field('image');
           //pass ID of image to a WordPress image function and output it
           echo wp_get_attachment_image( $picture['ID'] );
 ?>
    </div>
    
    <div class="profile-content">
      <h3><?php echo $pods->display('post_title'); ?></h3>
      <p class="lead position"><?php echo $pods->display('position');  ?></p>
      <?php echo $pods->display('bio');  ?>
    </div>
    
    <div class="profile-footer">
      <a href="tel:<?php echo $pods->display('mobile'); ?>">
        <?php echo $pods->display('phone'); ?>
      </a>
      <a href="mailto:<?php echo $pods->display('email'); ?>"><i class="icon-envelope"></i></a>
    </div> 

  </article><!-- /.profile -->
  <?php endwhile; ?>
  <?php vantage_content_nav( 'nav-below' ); ?>
  <?php echo $pods->pagination( array( 'type' => 'paginate' ) );  ?>

<?php echo do_shortcode('

[su_row]
  [su_column size="1/2"] Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis quam nibh, euismod eget nulla a, tempor scelerisque lorem. Proin dignissim arcu tristique fermentum ullamcorper. Integer lacinia scelerisque enim eu pretium. Nam elementum turpis orci, ac porttitor diam suscipit sit amet. [/su_column]
  [su_column size="1/2"] Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis quam nibh, euismod eget nulla a, tempor scelerisque lorem. Proin dignissim arcu tristique fermentum ullamcorper. Integer lacinia scelerisque enim eu pretium. Nam elementum turpis orci, ac porttitor diam suscipit sit amet. [/su_column]
[/su_row]


'); ?>



</section><!-- /.row -->
<?php endif; ?>
</div><!-- #content -->
          </div><!-- #primary -->
 
<?php get_sidebar(); ?>
<?php get_footer(); ?>
 ?>