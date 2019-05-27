<?php 
/*
Plugin Name: repeater gform - deals with accreditation tracking
Plugin URI:  https://github.com/
Description: For repeater fields and other pieces that merge with gravity
Version:     1.0
Author:      Tom Woodward
Author URI:  http://bionicteaching.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: my-toolset

*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


add_action('wp_enqueue_scripts', 'prefix_load_scripts');

function prefix_load_scripts() {                           
    $deps = array('jquery');
    $version= '1.0'; 
    $in_footer = true;
    wp_enqueue_script('list-js', 'https://cdnjs.cloudflare.com/ajax/libs/list.js/1.5.0/list.min.js', $deps, $version, $in_footer);     
    wp_enqueue_script('gform-repeater-js', plugin_dir_url( __FILE__) . 'js/gform-repeater.js', $deps, $version, $in_footer); 
    wp_enqueue_style( 'gform-repeater-main-css', plugin_dir_url( __FILE__) . 'css/gform-repeater-main.css');
}

// Adjust your form ID
    //REMBER TO CHANGE THIS TO ID 1
add_filter( 'gform_form_post_get_meta_7', 'add_my_field' );
function add_my_field( $form ) {
 
    // Create a Single Line text field for the title
    $title = GF_Fields::create( array(
        'type'   => 'text',
        'id'     => 1002, // The Field ID must be unique on the form
        'formId' => $form['id'],
        'label'  => 'Title',
        'pageNumber'  => 1, // Ensure this is correct
    ) );
 
    // Create a category for the evidence
    $description = GF_Fields::create( array(
        'type'   => 'text',
        'id'     => 1001, // The Field ID must be unique on the form
        'formId' => $form['id'],
        'label'  => 'Category',
        'pageNumber'  => 1, // Ensure this is correct        
    ) );

    $year = GF_Fields::create( array(
        'type'   => 'number',
        'id'     => 1003, // The Field ID must be unique on the form
        'formId' => $form['id'],
        'label'  => 'Year',
        'pageNumber'  => 1, // Ensure this is correct        
    ) );
 
    // Create a repeater for the team members and add the name and email fields as the fields to display inside the repeater.
    $evidence = GF_Fields::create( array(
        'type'             => 'repeater',
        //'description'      => 'No max',
        'id'               => 1000, // The Field ID must be unique on the form
        'formId'           => $form['id'],
        'label'            => 'Evidence',
        'addButtonText'    => 'Add item', // Optional
        'removeButtonText' => 'Remove item', // Optional
        //'maxItems'         => 3, // Optional
        'pageNumber'       => 1, // Ensure this is correct
        'fields'           => array( $title, $description, $year ), // Add the fields here.
    ) );
 
    $form['fields'][] = $evidence;
 
    return $form;
}
 
// Remove the field before the form is saved. Adjust your form ID
    //REMBER TO CHANGE THIS TO ID 1
add_filter( 'gform_form_update_meta_7', 'remove_my_field', 10, 3 );
function remove_my_field( $form_meta, $form_id, $meta_name ) {
 
    if ( $meta_name == 'display_meta' ) {
        // Remove the Repeater field: ID 1000
        $form_meta['fields'] = wp_list_filter( $form_meta['fields'], array( 'id' => 1000 ), 'NOT' );
    }
 
    return $form_meta;
}


/*
**
DISPLAY STUFF
**
*/

//[foobar]
function build_tenure_table(){
    $html = '';
    $search_criteria = array();
    $sorting         = array();
    $paging          = array( 'offset' => 0, 'page_size' => 500 );
    $total_count     = 0;
    $entries         = GFAPI::get_entries( 7, $search_criteria, $sorting, $paging, $total_count );
        foreach ($entries as $entry) {   
            print("<pre>".print_r($entry['1000'],true)."</pre>"); //$entry['1000'] == $entry['1.3'] $entry['1.6'] 
            $html .= '<div class="row"><div class="col-md-1">' . $entry['1.3'] .'</div><div class="col-md-1">' . $entry['1.6'] .'</div><div class="col-md-1">' . $entry['1000'][0]['1002'] .'</div></div>';
    }

    return $html;
}
add_shortcode( 'repeater-table', 'build_tenure_table' );


