<?php
/*
Template Name: Students Page Template
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
          $student_per_page = ( isset( $_GET['student_per_page'] ) ) ? $_GET['student_per_page'] : 9;

          // Name of Overview
          $degree_name = get_term( $degree, 'degree' )->name;
          $academic_year_name = get_term( $academic_year, 'academic_year' )->name;
          $graduation_year_name = get_term( $graduation_year, 'graduation_year' )->name; 
          $research_interest_name = get_term( $research_interest, 'research_interest' )->name;

          $overview_arr = [];

          $overview_arr[] = ($degree_name == "")? "All Programs" : $degree_name." Program";
          $overview_arr[] = ($academic_year_name == "")? "All Academic Years" : "Academic Year ".$academic_year_name;
          $overview_arr[] = ($graduation_year_name == "")? "All Graduation Years" : "Graduation Year ".$graduation_year_name;
          $overview_arr[] = ($research_interest_name == "")? "All Research Interests" : $research_interest_name." Fied";
          $overview = join( ", ", $overview_arr );
          // Prepare data to draw
          list($graduated_data, $not_graduated_data, $radar_data, $donut_graduated_data, $donut_gender_data, $donut_degree_data, $donut_ri_data) = process_student_data($degree,$academic_year,$graduation_year,$research_interest);

          $donut_arrays = [];
          if($donut_graduated_data){
            $donut_arrays['donut_graduated'] = $donut_graduated_data;
          }
          if($donut_gender_data){
            $donut_arrays['donut_gender'] = $donut_gender_data;
          }
          if($degree == 0 && $donut_degree_data){
            $donut_arrays['donut_degree'] = $donut_degree_data;
          }
          if($research_interest == 0 && $donut_ri_data){
            $donut_arrays['donut_ri'] = $donut_ri_data;
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
            <div class="su-spoiler-title"><span class="su-spoiler-icon"></span>Overview students in <?php echo $overview; ?></div>
            <div class="su-spoiler-content su-clearfix"> 
            <?php if($no_result){
              echo "<strong>No student found!</strong>";
              } ?>
            <?php if ($academic_year == 0) {
              $academic_bar_chart_id = "graduated_by_academic_bar_chart";
              echo '<div class="su-row">    
              <div class="su-column su-column-size-2-3 su-column-centered">
                <div class="su-column-inner su-clearfix">
                <canvas id="'.$academic_bar_chart_id.'" width="1" height="1"></canvas> 
                </div>
              </div>
            </div> ';
            } ?>
            <!-- <div class="su-row">    
              <div class="su-column su-column-size-2-3 su-column-centered">
                <div class="su-column-inner su-clearfix">
                <canvas id="radar_chart" width="1" height="1"></canvas> 
                </div>
              </div>
            </div> -->
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
          $content = '[bme_students type="student" degree="'.$degree.'" academic_year="'.$academic_year.'" graduation_year="'.$graduation_year.'" research_interest="'.$research_interest.'" tag_logic="" pagination_type="numeric" limit="'.$student_per_page.'" grid="3" show_date="false" show_content="true" show_category_name="true" content_words_limit="30"]';
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
var not_graduated_data = <?php echo json_encode(array_values($not_graduated_data)) ?>;
var graduated_data = <?php echo json_encode(array_values($graduated_data)) ?>;
// Bar Chart
if(chart_name){
  bar_chart(chart_name, labels, not_graduated_data, graduated_data);
}
// Radar Chart
// chart_name = 'radar_chart';
// labels = <?php //echo json_encode($radar_labels) ?>;
// var radar_data = <?php //echo json_encode($radar_data) ?>;
// radar_chart(chart_name, labels, radar_data);
// Donut Charts
var donut_arrays_id = <?php echo json_encode(array_keys($donut_arrays)); ?>;
var donut_arrays = <?php echo json_encode(array_values($donut_arrays)); ?>;
console.log(donut_arrays_id);
console.log(donut_arrays);
console.log(typeof(donut_arrays));

for (var i = 0; i < donut_arrays_id.length; i++) {
  chart_name = donut_arrays_id[i];
  labels = Object.keys(donut_arrays[i]);
  var data = Object.values(donut_arrays[i]);
  var bg_color = getRandomColorArray(labels.length);
  doughnut_chart(chart_name, labels, data, bg_color);
}


  function bar_chart(chart_name, labels, not_graduated_data, graduated_data){
    var ctx = document.getElementById(chart_name);

    var myChart = new Chart(ctx,{
      type: 'bar',
      data: {
          labels: labels,
          datasets: [
          {
              label: 'Graduated',
              data: graduated_data,
              backgroundColor: "rgba(55, 160, 225, 0.7)",
              hoverBackgroundColor: "rgba(55, 160, 225, 0.7)",
              hoverBorderWidth: 2,
              hoverBorderColor: 'lightgrey'
          },
          {
              label: 'Not Graduated',
              data: not_graduated_data,
              backgroundColor: "rgba(225, 58, 55, 0.7)",
              hoverBackgroundColor: "rgba(225, 58, 55, 0.7)",
              hoverBorderWidth: 2,
              hoverBorderColor: 'lightgrey'
          },
          ]
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
    var nice_color = ["#ffc0cb","#008080","#ffe4e1","#ff0000","#ffd700","#d3ffce","#00ffff","#40e0d0","#ff7373","#0000ff", "#ffa500","#cccccc","#7fffd4","#00ff00","#f6546a","#003366", "#c6e2ff", "#3399ff", "#0099cc", "#cc0000"];

    if(nice_color.length >= num){
      var shuffled = nice_color.sort(function(){return .5 - Math.random()});
      return shuffled.slice(0,num);
    }
  }
</script>

<?php 

function count_student($degree,$sex,$academic_year,$graduation_year,$research_interest){
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
      array( 
          'taxonomy'  => 'gender',
          'field'     => 'slug', 
          'terms'     => $sex,
      ),
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
      'fields' => 'ids',
      'cache_results' => false,               
      'update_post_term_cache' => false,       
      'update_post_meta_cache' => false,
      'no_found_rows' => true,
  );    
  $the_query = new WP_Query( $args );
  $count = $the_query->post_count;
  // Reset Post Data
  wp_reset_postdata();
  return $count;
}

function process_student_data($degree,$academic_year,$graduation_year,$research_interest){
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
  );    
  $the_query = new WP_Query( $args );
  
  if($the_query->post_count == 0)// no students found
    return;

  // Initialize result arrays
  $graduated_student_array = []; 
  $not_graduated_student_array = [];
  $graduated_array = [];
  $gender_array = [];
  $degree_array = [];
  $ri_array = [];
  $radar_array = [
    'Graduated' => 0,
    'Not Graduated' => 0,
  ];

  // Add array keys as name of categories
  if ($academic_year == 0) { // Not choose specific Academic Year
    $graduated_student_array = add_array_keys($graduated_student_array, 'academic_year');
    $not_graduated_student_array = add_array_keys($not_graduated_student_array, 'academic_year');
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
  

  $num_graduated = 0;
  $num_not_graduated = 0;
  $num_not_decide = 0;

  while($the_query->have_posts()) {
    $the_query->the_post(); 
    $student = pods(get_post_type(), get_the_ID());
    // Get taxonomy
    $ay = get_the_terms( $post->ID, 'academic_year' );
    $gy = get_the_terms( $post->ID, 'graduation_year' );
    $sex = get_the_terms( $post->ID, 'gender' );
    $ri = get_the_terms( $post->ID, 'research_interest' );
    $d = get_the_terms( $post->ID, 'degree' );

    if($gy){ // graduated
      $graduated_student_array[$ay[0]->name] += 1; 
      $num_graduated++;
    }else{
      $not_graduated_student_array[$ay[0]->name] += 1;
      $num_not_graduated++;
    }

    if($sex){
      $radar_array[$sex[0]->name] += 1; 
      $gender_array[$sex[0]->name] += 1; 
    }
    if($d){
      $radar_array[$d[0]->name] += 1; 
      $degree_array[$d[0]->name] += 1; 
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
 
  $graduated_array['Graduated'] = $num_graduated;
  $graduated_array['Not Graduated'] = $num_not_graduated;
  $ri_array['Not Decide Research Interests'] = $num_not_decide ;
  $radar_array['Graduated'] = $num_graduated;
  $radar_array['Not Graduated'] = $num_not_graduated;
  $radar_array['Not Decide Research Interests'] = $num_not_decide ;

  return [$graduated_student_array, $not_graduated_student_array, $radar_array,
  $graduated_array, $gender_array, $degree_array, $ri_array];
}

function add_array_keys($arr, $taxonomy, $hide = false){
  $terms = get_terms( array(
      'taxonomy' => $taxonomy,
      'hide_empty' => $hide,
    ) );
  foreach ( $terms as $term ) {
      $arr[$term->name] = 0.0; 
    }
  return $arr;
}
?>