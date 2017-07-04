<?php
/*
Template Name: Alumni Page Template
*/
 
get_header();?>

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
        
        

        
        <form id="category-select" class="category-select" action="<?php echo get_permalink($page_id); ?>" method="get">
        <!-- $page_id is the current page id -->
        <div class="su-row">    
              
          
            <?php
                $args = array(
                  'show_option_all' => __( 'All Programs' ),
                  'show_count'       => 1,
                  'orderby'          => 'name',
                  'echo'             => 0,
                  'name'             => 'degree', 
                  'taxonomy'         => 'degree',
                );
            ?>
            <?php $degree_dropbox = wp_dropdown_categories( $args ); 
                  if (get_locale() == 'en_US') {
                    echo $degree_dropbox;
                  }else{
                    $degree_dropbox = str_replace('Master', 'Thạc sĩ', $degree_dropbox);
                    $degree_dropbox = str_replace('Bachelor', 'Cử nhân', $degree_dropbox);
                    echo $degree_dropbox;
                  }
            ?>

            <?php
                $args = array(
                  'show_option_all' => __( 'All Academic Years' ),
                  'show_count'       => 1,
                  'orderby'          => 'name',
                  'echo'             => 0,
                  'name'             => 'academic_year',
                  'taxonomy'         => 'academic_year',
                );
            ?>
            <?php echo wp_dropdown_categories( $args ); ?>

            <?php
                $args = array(
                  'show_option_all' => __( 'All Graduation Years' ),
                  'show_count'       => 1,
                  'orderby'          => 'name',
                  'echo'             => 0,
                  'name'             => 'graduation_year',
                  'taxonomy'         => 'graduation_year',
                );
            ?>
            <?php echo wp_dropdown_categories( $args ); ?>

            <?php
                $args = array(
                  'show_option_all' => __( 'All Employers' ),
                  'show_count'       => 1,
                  'orderby'          => 'name',
                  'echo'             => 0,
                  'name'             => 'employer',
                  'taxonomy'         => 'employer',
                );
            ?>
            <?php echo wp_dropdown_categories( $args ); ?>

            <select name="student_per_page">
              <option value="9">Students per page</option>
              <option value="18">18 students</option>
              <option value="36">36 students</option>
              <option value="54">54 students</option>
              <option value="72">72 students</option>
              <option value="90">90 students</option>
            </select>

            <?php
                $args = array(
                  'show_option_all' => __( 'All Research Interests' ),
                  'show_count'       => 1,
                  'orderby'          => 'name',
                  'echo'             => 0,
                  'name'             => 'research_interest',
                  'taxonomy'         => 'research_interest',
                );
            ?>
            
            <?php echo wp_dropdown_categories( $args ); ?>   

            <input type="submit"  style="padding:7px 7px 7px 7px; background:#1E93D0 ; color:#fff; display:inline-block; clear:both; text-decoration:none !important;" name="submit" value="View" />
            </div>
        </form>

        <?php 
          $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
          $degree = ( isset( $_GET['degree'] ) ) ? $_GET['degree'] : 0;
          $academic_year = ( isset( $_GET['academic_year'] ) ) ? $_GET['academic_year'] : 0;
          $graduation_year = ( isset( $_GET['graduation_year'] ) ) ? $_GET['graduation_year'] : 0;
          $research_interest = ( isset( $_GET['research_interest'] ) ) ? $_GET['research_interest'] : 0;
          $employer = ( isset( $_GET['employer'] ) ) ? $_GET['employer'] : 0;
          $student_per_page = ( isset( $_GET['student_per_page'] ) ) ? $_GET['student_per_page'] : 9;

          // Name of Overview
          $degree_name = get_term( $degree, 'degree' )->name;
          $academic_year_name = get_term( $academic_year, 'academic_year' )->name;
          $graduation_year_name = get_term( $graduation_year, 'graduation_year' )->name; 
          $research_interest_name = get_term( $research_interest, 'research_interest' )->name;
          $employer_name = get_term( $employer, 'employer' )->name;

          $overview_arr = [];

          $overview_arr[] = ($degree_name == "")? "All Programs" : $degree_name." Program";
          $overview_arr[] = ($academic_year_name == "")? "All Academic Years" : "Academic Year ".$academic_year_name;
          $overview_arr[] = ($graduation_year_name == "")? "All Graduation Years" : "Graduation Year ".$graduation_year_name;
          $overview_arr[] = ($research_interest_name == "")? "All Research Interests" : $research_interest_name." Fied";
          $overview_arr[] = ($employer_name == "")? "All Employers" : "work at ".$employer_name;
          $overview = join( ", ", $overview_arr );
          // Prepare data to draw
          list($radar_data, $ri_array_by_gy_data,$graduated_data, $donut_gender_data, $donut_degree_data, $donut_ri_data, $donut_employer_data) = process_alumni_data($degree,$academic_year,$graduation_year,$research_interest, $employer);

          $donut_arrays = [];
          if($donut_gender_data){
            $donut_arrays['donut_gender'] = $donut_gender_data;
          }
          if($degree == 0 && $donut_degree_data){
            $donut_arrays['donut_degree'] = $donut_degree_data;
          }
          if($research_interest == 0 && $donut_ri_data){
            $donut_arrays['donut_ri'] = $donut_ri_data;
          }
          if($employer == 0 && $donut_employer_data){
            $donut_arrays['donut_employer'] = $donut_employer_data;
          }
          $donut_chart_id = array_keys($donut_arrays);

          $no_result = 0;
          if (count($donut_arrays) == 0) { // No students found
            $no_result = 1;
          }
         
          ?> 

          <!-- Overview parts -->
          <div class="su-accordion">
            <div class="su-spoiler su-spoiler-style-fancy su-spoiler-icon-plus su-spoiler-closed">
            <div class="su-spoiler-title"><span class="su-spoiler-icon"></span>Overview alumni in <?php echo $overview; ?></div>
            <div class="su-spoiler-content su-clearfix"> 
            <?php if($no_result){
              echo "<strong>No student found!</strong>";
              } ?>
            <?php if ($graduation_year == 0) {
              $academic_bar_chart_id = "ri_by_graduation_bar_chart";
              echo '<div class="su-row">    
              <div class="su-column su-column-size-2-3 su-column-centered">
                <div class="su-column-inner su-clearfix">
                <canvas id="'.$academic_bar_chart_id.'" width="1" height="1"></canvas> 
                </div>
              </div>
            </div> ';
            } ?>
            <div class="su-row">    
              <div class="su-column su-column-size-2-3 su-column-centered">
                <div class="su-column-inner su-clearfix">
                <canvas id="test_chart" width="1" height="1"></canvas> 
                </div>
              </div>
            </div>
            <?php 
              $chart_num = 1;
              foreach ($donut_chart_id as $id) {
                if($chart_num % 2 == 1){ //odd
                  echo '<div class="su-row">';
                }
                echo '<div class="su-column su-column-size-1-2">
                <div class="su-column-inner su-clearfix">
                <canvas id="'.$id.'" width="1" height="1"></canvas> 
                </div>
                </div>';
                if ($chart_num % 2 == 0){ //even
                  echo "</div>";
                } 
                $chart_num++;
              }
             ?>     
            </div> <!-- end su-spoiler-content  --> 
            </div> <!-- end su-spoiler  --> 
          </div> <!-- end su-accordion        -->

          <?php
          // Display students
          $content = '[bme_students type="alumni" degree="'.$degree.'" academic_year="'.$academic_year.'" graduation_year="'.$graduation_year.'" research_interest="'.$research_interest.'" employer="'.$employer.'" pagination_type="numeric" limit="'.$student_per_page.'" grid="3" show_date="false" show_content="true" show_category_name="true" content_words_limit="30"]';
          echo do_shortcode( $content );
          // end Display students
        ?>
        </div><!-- .entry-content -->
      </div>
    </article><!-- #post-<?php the_ID(); ?> -->
  </div><!-- #content -->
</div><!-- #primary -->   
 
<?php get_sidebar(); ?>
<?php get_footer(); ?>


<script>
var chart_name = <?php echo json_encode($academic_bar_chart_id); ?>;
var labels = <?php echo json_encode(array_keys($graduated_data)) ?>;
var ri_array_by_gy_data = JSON.parse( '<?php echo json_encode($ri_array_by_gy_data) ?>' );

// console.log(labels);
// console.log(Object.keys(ri_array_by_gy_data));
var ri_datasets = [];
var ri_keys = Object.keys(ri_array_by_gy_data);
var bg_color = getRandomColorArray(ri_keys.length);

for (var i=0; i<ri_keys.length; i++){
  ri_datasets[i] = {
    label: ri_keys[i],
    data: ri_array_by_gy_data[ri_keys[i]],
    backgroundColor: bg_color[i],
    hoverBackgroundColor: bg_color[i],
    hoverBorderWidth: 2,
    hoverBorderColor: 'lightgrey'
  };
}
 stacked_bar_chart(chart_name, labels, ri_datasets);
// Donut Charts
var donut_arrays_id = <?php echo json_encode(array_keys($donut_arrays)); ?>;
var donut_arrays = <?php echo json_encode(array_values($donut_arrays)); ?>;

for (var i = 0; i < donut_arrays_id.length; i++) {
  chart_name = donut_arrays_id[i];
  labels = Object.keys(donut_arrays[i]);
  var data = Object.values(donut_arrays[i]);
  var bg_color = getRandomColorArray(labels.length);
  doughnut_chart(chart_name, labels, data, bg_color);
}

function stacked_bar_chart(chart_name, labels, datasets){
    var ctx = document.getElementById(chart_name);

    var myChart = new Chart(ctx,{
      type: 'bar',
      data: {
          labels: labels,
          datasets: datasets,
      },
      options: {
          animation: false,
          tooltips: {
            mode: 'label',
           },
          scales: {
            xAxes: [{ 
              stacked: true, 
              gridLines: { display: false },
              }],
            yAxes: [{ 
              stacked: true, 
              }],
          }, // scales
          legend: {display: true}
      } // options
    });
  }

  function radar_chart(chart_name, labels, data){
    
    var ctx = document.getElementById(chart_name);

    var myChart = new Chart(ctx,{
    type: 'radar',
    data: {
      labels: labels,
      datasets: [
        {
          // label: "1950",
          fill: false,
          backgroundColor: "#004B87",
          borderColor: "#004B87",
          radius: 5,
          pointBorderColor: "#fff",
          pointBackgroundColor: "#004B87",
          data: data
        }
      ]
    },
    options: {
      animation: false,
      title: {
        display: false,
        text: 'Distribution of BME students'
      },
      legend: { display: false },
    }
    });
  }

  function doughnut_chart(chart_name, labels, data, bg_color){
    
    var ctx = document.getElementById(chart_name);

    var myChart = new Chart(ctx,{
    type: 'doughnut',
    data: {
      labels: labels,
      datasets: [
        {
          backgroundColor: bg_color,
          data: data
        }
      ]
    },
    options: {
      animation: false,
      tooltips: {
        callbacks: {
          label: function(tooltipItem, data) {
            var allData = data.datasets[tooltipItem.datasetIndex].data;
            var tooltipLabel = data.labels[tooltipItem.index];
            var tooltipData = allData[tooltipItem.index];
            var total = 0;
            for (var i in allData) {
              total += allData[i];
            }
            var tooltipPercentage = ((tooltipData / total) * 100).toFixed(2);
            return tooltipLabel + ': ' + tooltipData + ' (' + tooltipPercentage + '%)';
          }
        }
      }
    }
    });
  }

  function getRandomColorArray(num) {
    var nice_color = ["#000000","#ffc0cb","#008080","#ffe4e1","#ff0000","#ffd700","#00ffff","#d3ffce","#40e0d0","#ff7373","#0000ff","#eeeeee","#e6e6fa","#ffa500","#b0e0e6","#cccccc","#7fffd4","#333333","#800080","#00ff00","#c0c0c0","#20b2aa","#f6546a","#003366","#fa8072","#666666","#c6e2ff","#faebd7","#ffb6c1","#00ced1","#ffff00","#088da5","#ff6666","#ffc3a0","#66cdaa","#f08080","#468499","#fff68f","#800000","#ff00ff","#660066","#008000","#990000","#808080","#8b0000","#afeeee","#cbbeb5","#dddddd","#81d8d0","#c39797","#ffdab9","#daa520","#0e2f44","#ff7f50","#b4eeb4","#f5f5dc","#c0d6e4","#ff4040","#00ff7f","#66cccc","#b6fcd5","#cc0000","#a0db8e","#0099cc","#999999","#3399ff","#8a2be2","#ccff00","#ffff66","#3b5998","#794044","#6dc066","#000080","#191970","#31698a","#6897bb","#191919","#ff4444","#404040","#4169e1"];

    if(nice_color.length >= num){
      var shuffled = nice_color.sort(function(){return .5 - Math.random()});
      return shuffled.slice(0,num);
    }
  }
</script>

<?php 

function process_alumni_data($degree,$academic_year,$graduation_year,$research_interest,$employer){
  // Filter alumni 
    if ($graduation_year == 0) {
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

  $args = array ( 
        'post_type'         => 'student', 
        'posts_per_page'    => -1,   
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
      ($research_interest? array( // for Graduation Year
          'taxonomy'  => 'research_interest',
          'field'     => 'id', 
          'terms'     => $research_interest,
      ): ''),
      ($employer? array( // for Employer
          'taxonomy'  => 'employer',
          'field'     => 'id', 
          'terms'     => $employer,
      ): ''),
  );    
  $the_query = new WP_Query( $args );
  if (count($graduation_year) > 1){ // return GY to 0 or not chosen GY option
      $graduation_year = 0;
  }


  if($the_query->post_count == 0)// no students found
    return;

  // Initialize result arrays
  $graduated_array = [];
  $ri_array_by_gy = [];
  $gender_array = [];
  $degree_array = [];
  $ri_array = [];
  $radar_array = [];
  $employer_array = [];

  // Add array keys as name of categories
  if ($graduation_year == 0) { // Not choose specific Graduation Year
    $graduated_array = taxonomy_name_index_array(array(),'graduation_year');
    $ri_array_by_gy = add_array_keys(array(), 'research_interest', false, 
      array_fill(0,count($graduated_array),0));
  }

  $radar_array = add_array_keys($radar_array,'gender'); 
  $gender_array = add_array_keys($gender_array,'gender'); 
  if ($degree == 0){
    $radar_array = add_array_keys($radar_array,'degree');
    $degree_array = add_array_keys($degree_array,'degree');
  }
  if ($research_interest == 0){
    $radar_array = add_array_keys($radar_array,'research_interest'); 
    $ri_array = add_array_keys($ri_array,'research_interest');  
  }
  if ($employer == 0){
    $employer_array = add_array_keys($employer_array,'employer'); 
  }
  
  $num_not_decide = 0;
  $num_no_job     = 0;

  while($the_query->have_posts()) {
    $the_query->the_post(); 
    $student = pods(get_post_type(), get_the_ID());
    // Get taxonomy
    $ay = get_the_terms( $post->ID, 'academic_year' );
    $gy = get_the_terms( $post->ID, 'graduation_year' );
    $sex = get_the_terms( $post->ID, 'gender' );
    $ri = get_the_terms( $post->ID, 'research_interest' );
    $d = get_the_terms( $post->ID, 'degree' );
    $employer_name = get_the_terms( $post->ID, 'employer' );

    if($graduation_year == 0)
      $ri_array_by_gy[$ri[0]->name][$graduated_array[$gy[0]->name]]++;    

    if($sex){
      $radar_array[$sex[0]->name] += 1; 
      $gender_array[$sex[0]->name] += 1; 
    }
    if($d){
      $radar_array[$d[0]->name] += 1; 
      $degree_array[$d[0]->name] += 1; 
    }
    if($employer_name){
      foreach ( $employer_name as $name ) {
        $employer_array[$name->name] += 1;
      } 
    }else{
      $num_no_job ++;
    }
    $num_ri = count($ri);
    if($ri){
      foreach ( $ri as $i ) {
        $radar_array[$i->name] += 1/$num_ri;
        $ri_array[$i->name] += 1/$num_ri;
      }
    }else{
      $num_not_decide++;
    }
  } 
 
  $ri_array['Not Decide Research Interests'] = $num_not_decide ;
  $employer_array['Unknown'] = $num_no_job;
  $radar_array['Not Decide Research Interests'] = $num_not_decide ;

  return [ $radar_array, $ri_array_by_gy, $graduated_array, $gender_array, $degree_array, $ri_array, $employer_array];
}

function add_array_keys($arr, $taxonomy, $hide = false, $data = 0.0){
  $terms = get_terms( array(
      'taxonomy' => $taxonomy,
      'hide_empty' => $hide,
      'orderby' => 'name',
      'order'   => 'ASC',
    ) );
  foreach ( $terms as $term ) {
      $arr[$term->name] = $data; 
    }
  return $arr;
}

function taxonomy_name_index_array($arr, $taxonomy, $hide = false){
  $terms = get_terms( array(
      'taxonomy' => $taxonomy,
      'hide_empty' => $hide,
      'orderby' => 'name',
      'order'   => 'ASC',
    ) );
  $index = 0;
  foreach ( $terms as $term ) {
      $arr[$term->name] = $index;
      $index++; 
    }
  return $arr;
}
?>