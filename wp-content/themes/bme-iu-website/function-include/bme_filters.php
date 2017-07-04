<?php 
add_filter('wpseo_breadcrumb_single_link' ,'timersys_remove_companies', 10 ,2);
function timersys_remove_companies($link_output, $link ){
 
    if( $link['text'] == 'Staffs') {
        if (get_locale() == 'en_US' ) {
            $link_output = '<a>About</a> » <a>Faculty</a>';
        }else
            $link_output = '<a>Giới thiệu</a> » <a>Nhân sự</a>';   
    }elseif ($link['text'] == 'Events') {
        // echo $link['text'];
        // echo $link_output;
        if (get_locale() == 'en_US' ) {
            $link_output = '<a>News & Events</a> » <a>Events</a>';
        }else
            $link_output = '<a>Thông tin & Sự kiện </a> » <a>Sự Kiện</a>';
    }
    return $link_output;
}
 ?>