<?php  ?>
<div class="su-column su-column-size-1-3"><div class="su-column-inner su-clearfix"> <div class="so-panel widget widget_circleicon-widget panel-first-child panel-last-child">    
        <div class="circle-icon-box circle-icon-position-top circle-icon-hide-box circle-icon-size-large">
          <div class="circle-icon-wrapper">
         	<a href="<?php the_permalink(); ?>" target="_blank">
            <div class="circle-icon icon-style-set" style="background-image: url(<?php the_post_thumbnail_url(); ?>)">
            </div>
           	</a>
          </div>

          <h4><a href="<?php the_permalink(); ?>" target="_blank"><?php the_title(); ?></a></h4> 
          <p class="text"><strong><?php echo get_post_meta(get_the_ID(),'position',true); ?></strong>
          </p> 

          <p class="text" align="left">
          	<?php
                  echo do_shortcode('[icon name="envelope" class="fa-1x"]').'&emsp;'.get_post_meta(get_the_ID(),'email',true); 
                  ?><br>  
                  <?php
                  echo do_shortcode('[icon name="phone" class="fa-1x"]').'&emsp;'.get_post_meta(get_the_ID(),'phone',true); 
                  ?><br>
                  <?php
                  echo do_shortcode('[icon name="home" class="fa-1x"]').'&emsp;'.get_post_meta(get_the_ID(),'office_info',true); 
                  ?><br>
            <?php 
              $research_interest = get_post_meta(get_the_ID(),'research_interest',true);
              if(!empty($research_interest)){
                if (get_locale() == 'en_US') { 
            echo "<strong>Research Interests: </strong>";
            } else{
              echo "<strong>Hướng nghiên cứu: </strong>";}  
                echo $research_interest; 
              } ?>
          </p>   

          <p class="text" align="left">
          </p>
          <!-- <a href="<?php //the_permalink(); ?>" class="more-button">Read more ... <i></i></a> -->
        </div>
      </div> </div></div>