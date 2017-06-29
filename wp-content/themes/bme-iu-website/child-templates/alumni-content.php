<?php  
$post_count = $query->post_count;
$count = 0;
if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
   $student = pods(get_post_type(), get_the_id());
   $count++;
        
   $term_degree = get_the_terms( $post->ID, 'degree' ); 
   $term_academic_year = get_the_terms( $post->ID, 'academic_year' ); 
   $term_graduation_year = get_the_terms( $post->ID, 'graduation_year' ); 
   $term_gender = get_the_terms( $post->ID, 'gender' ); 
   $news_links = array();
        
  	if($term_degree){
      foreach ( $term_degree as $term ) {
         $term_link = get_term_link( $term );
         $news_links[] = '<a href="' . esc_url( $term_link ) . '">'.$term->name.'</a>';
      }
  	}
  	if($term_academic_year){
      foreach ( $term_academic_year as $term ) {
         $term_link = get_term_link( $term );
         $news_links[] = '<a href="' . esc_url( $term_link ) . '">Academic Year '.$term->name.'</a>';
      }
  }
  if($term_graduation_year){
      foreach ( $term_graduation_year as $term ) {
         $term_link = get_term_link( $term );
         $news_links[] = '<a href="' . esc_url( $term_link ) . '">Graduation Year '.$term->name.'</a>';
      }
  }
  // if($term_gender){
  //     foreach ( $term_gender as $term ) {
  //        $term_link = get_term_link( $term );
  //        $news_links[] = '<a href="' . esc_url( $term_link ) . '">'.$term->name.'</a>';
  //     }
  // }
  $cate_name = join( ", ", $news_links );

  // Research Interest tags
  $terms = get_the_terms( $post->ID, 'research_interest' ); 
  $news_links = array();
        
  if($terms){
      foreach ( $terms as $term ) {
      	$term_link = get_term_link( $term );
       	$news_links[] = '<a href="' . esc_url( $term_link ) . '">'.$term->name.'</a>';
      }
  }
  $term_research_interest = join( ", ", $news_links );

  $css_class="team";
  if ( ( is_numeric( $grid ) && ( $grid > 0 ) && ( 0 == ($count - 1) % $grid ) ) || 1 == $count ) { $css_class .= ' first'; }
  if ( ( is_numeric( $grid ) && ( $grid > 0 ) && ( 0 == $count % $grid ) ) || $post_count == $count ) { $css_class .= ' last'; }
  if($showDate == 'true'){ $date_class = "has-date";}else{$date_class = "has-no-date";} ?>
    
   <div id="post-<?php the_ID(); ?>" class="news type-news news-col-<?php echo $gridcol.' '.$css_class.' '.$date_class; ?>" style="padding-right: 10px; padding-left: 0px; margin-bottom: 0px !important;">
      <div class="news-thumb">
      	<?php $student_image = ($student->display('alumni_image'))? $student->display('alumni_image'):$student->display('student_image'); 
        ?>
       	<?php if ($student_image) {
           	if($gridcol == '1'){ ?>
               <div class="grid-news-thumb">
                  <?php //the_post_thumbnail('url'); ?>
                  <img src="<?php echo $student_image; ?>" >
               </div>
           <?php } else if($gridcol > '2') { ?>
               <div class="grid-news-thumb">   
                  <?php //the_post_thumbnail('large'); ?>
                  <img src="<?php echo $student_image; ?>" >
               </div>
           <?php   } else { ?>
               <div class="grid-news-thumb">
                  <?php //the_post_thumbnail('large'); ?>
                  <img src="<?php echo $student_image; ?>" >
               </div>
           <?php } 
         }else{?>
         	<div class="grid-news-thumb">
         		<!-- No Image -->
         	</div>
         <?php } ?>
      </div>
            
      <div class="news-content">
         <div class="post-content-text">
         	
            <?php 
              if ($student->display('student_cv')){
                // the_title( sprintf( '<h6 class="news-title"><strong><a>'.$student->display('student_id').'</a></strong>'."&nbsp;&nbsp;&nbsp;".do_shortcode('[su_lightbox src="'.$student->display('student_cv').'"][su_button url="'.$student->display('student_cv').'" target="blank" style="flat" background="#004B87" size="3" icon="icon: cloud-download" rel="lightbox"]CV[/su_button][/su_lightbox]' ).'<br>', esc_url( get_permalink() ) ), '</h6>' );
                the_title( sprintf( '<h6 class="news-title"><strong><a>'.$student->display('student_id').'</a></strong>'."&nbsp;&nbsp;&nbsp;".do_shortcode('[su_button url="'.$student->display('student_cv').'" target="blank" style="flat" background="#004B87" size="3" icon="icon: cloud-download" rel="lightbox"]CV[/su_button]' ).'<br>', esc_url( get_permalink() ) ), '</h6>' );
              }
                else{
                the_title( sprintf( '<h6 class="news-title"><strong><a>'.$student->display('student_id').'</a></strong><br>', esc_url( get_permalink() ) ), '</h6>' );
              }
            ?>
            <div class="grid-date-post">
               <strong> <?php //echo $cate_name;
                if($student->display('current_job')){
                  echo $student->display('current_job');
                  echo ' - <a href="'.$student->display('employer_website').'" target="_blank">';
                  echo $student->display('employer').'</a>';
                } 
                // else
                //   echo "<br>";
                //echo "&nbsp;".do_shortcode('[su_button url="'.$student->display('cv').'" target="blank" style="flat" background="#42a4d6" size="2" icon="icon: cloud-download" rel="lightbox"]CV[/su_button]' );

               ?> </strong>
               
            </div>

				<div class="su-accordion">
					<div class="su-spoiler su-spoiler-style-fancy su-spoiler-icon-plus su-spoiler-closed">
					<div class="su-spoiler-title"><span class="su-spoiler-icon"></span>Show more</div>
					<div class="su-spoiler-content su-clearfix"> 
						<table style="table-layout: auto; border: none; font-size: 12px; border-spacing:1px;">
							<tbody>
                <tr>
                <td><strong>Degree</strong></td>
                <td><?php //echo "&nbsp;".$student->display('current_job').' at <a href="'.$student->display('employer.website').'" target="_blank">'; 
                          //echo $student->display('employer').'</a>';
                          echo $term_degree[0]->name;
                ?></td>
                </tr>
                <tr>
                <td><strong>AY – GY</strong></td>
                <td><?php //echo "&nbsp;".$student->display('current_job').' at <a href="'.$student->display('employer.website').'" target="_blank">'; 
                          //echo $student->display('employer').'</a>';
                          echo $term_academic_year[0]->name.' – '.$term_graduation_year[0]->name;
                ?></td>
                </tr>
                <?php if ($term_research_interest != ""): ?>
                <tr>
                <td colspan="2"><strong>Research Interest</strong></td>
                </tr>  
                <tr>
                  <td colspan="2">
                    <?php echo "&nbsp;".$term_research_interest;?>
                  </td>
                </tr>
                <?php endif ?>
                <tr>
                <td colspan="2"><strong>Thesis Title</strong></td>
                </tr>  
                <tr>
                  <td colspan="2">
                    <?php echo "&nbsp;".$student->display('thesis_title');?>
                  </td>
                </tr>
                <tr>
                <td><strong>DOB</strong></td>
                <td><?php echo "&nbsp;".$student->display('date_of_birth'); ?></td>
                </tr>
								<tr>
								<td><strong>Hometown</strong></td>
								<td><?php echo "&nbsp;".$student->display('hometown'); ?></td>
								</tr>
								<?php if ($student->display('high_school_or_university')): ?>
								<tr>
								<td><strong>Education</strong></td>
								<td><?php echo "&nbsp;".$student->display('high_school_or_university'); 
                ?></td>
								</tr>
								<?php endif; ?>
								<tr>
								<td><strong>Email</strong></td>
								<td><?php echo "&nbsp;".'<a>'.$student->display('email').'</a>'; ?></td>
								<tr>
								<td><strong>Phone</strong></td>
								<td><?php echo "&nbsp;".$student->display('phone'); ?></td>	
								</tr>
								</tr>
                <?php if ($student->display('facebook')): ?>
                <tr>
                <td><strong>Facebook</strong></td>
                <td><?php echo "&nbsp;<a href='".$student->display('facebook')."' target='_blank'>".do_shortcode("[icon name='facebook-official' class='fa-2x' unprefixed_class='']")."</a>"; 
                ?></td>
                </tr>             
                <?php endif ?>
								
                
							</tbody>
						</table>
 					</div> <!-- end su-spoiler-content  --> 
 					</div> <!-- end su-spoiler  --> 
				</div> <!-- end su-accordion  --> 
      	</div> <!-- end post-content-text -->
   	</div> <!--	end news-content -->
   </div><!-- #post-## -->
<?php  endwhile; endif; ?>
            
<div class="news_pagination">
  	<?php if($pagination_type == 'numeric'){ 
      echo news_pagination( array( 'paged' => $paged , 'total' => $query->max_num_pages ) );
  	}else{ ?>            
      <div class="button-news-p"><?php next_posts_link( ' Next >>', $query->max_num_pages ); ?></div>           
      <div class="button-news-n"><?php previous_posts_link( '<< Previous' ); ?> </div>
  	<?php } ?>
</div>