<?php 
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
 ?>