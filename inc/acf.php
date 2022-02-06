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

