<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
/*
************SORT BY YEAR
*/

//SORT ACF YEAR FIELD TO NEWEST ON TOP
function sort_record_by_year( $value, $post_id, $field ) {
    // vars
    $order = array();
    
    // bail early if no value
    if( empty($value) ) {
        return $value;      
    }
    
    // populate order
    foreach( $value as $i => $row ) {
        
        $order[ $i ] = $row['field_5cf50f064b39c'];
        
    }
    
    // multisort
    array_multisort( $order, SORT_DESC, $value );
    
    // return   
    return $value;
    
}

add_filter('acf/load_value/name=faculty_record', 'sort_record_by_year', 10, 3);


//add ACF FORM TO HEADER***************************
// add_action( 'wp_head', 'sweden_add_acf_head' );

// function sweden_add_acf_head() {
//     if (
//         ! function_exists( 'acf_form_head' )

//     ) {
//         return;
//     }

//     acf_form_head();
// }
// add_filter('acf/settings/load_json', 'my_acf_json_load_point');



// add_filter( 'the_content', 'add_acf_form', 1 );
 
// function add_acf_form( $content ) {
//     global $post;
//     // if (  ) {
//     //     return $content . acf_form();;
//     // }
//     $args = array(
//         'post_id' => $post->id,
//         'id' => 'activity-submission',
//         'new_post' => false,
//         'fields' => array('faculty_record'),

//     );
 
//     return $content . acf_form($args);
// }


//ACF JSON SAVER
add_filter('acf/settings/save_json', 'my_acf_json_save_point');
 
function my_acf_json_save_point( $path ) {
    
    // update path
    $path = plugin_dir_path( __FILE__ )  . '/acf-json';
    // return
    return $path;
    
}


function my_acf_json_load_point( $paths ) {
    
    // remove original path (optional)
    unset($paths[0]);
    
    
    // append path
    $paths[] = plugin_dir_path( __FILE__ )  . '/acf-json';
    
    
    // return
    return $paths;
    
}