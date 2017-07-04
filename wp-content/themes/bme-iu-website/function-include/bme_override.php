<?php
// Homepage the slider Vietnamese Language
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