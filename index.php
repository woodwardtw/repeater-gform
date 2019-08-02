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
    global $post;
    $deps = array('jquery');
    $version= '1.0'; 
    $in_footer = true;
    wp_enqueue_script('list-js', 'https://cdnjs.cloudflare.com/ajax/libs/list.js/1.5.0/list.min.js', $deps, $version, $in_footer);  
    wp_enqueue_script('jqueryUI', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js', $deps, $version, $in_footer);     
    wp_enqueue_script('gform-repeater-js', plugin_dir_url( __FILE__) . 'js/gform-repeater.js', $deps, $version, $in_footer); 
    wp_localize_script('gform-repeater-js', 'ajaxurl', admin_url( 'admin-ajax.php' ) );  
    wp_enqueue_style( 'gform-repeater-main-css', plugin_dir_url( __FILE__) . 'css/gform-repeater-main.css');
    wp_enqueue_style( 'jqueryUI-css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
}



// Adjust your form ID
    //REMBER TO CHANGE THIS TO ID 1
add_filter( 'gform_form_post_get_meta_1', 'add_my_field' );
function add_my_field( $form ) {

     // Create a category for the evidence
    $description = GF_Fields::create( array(
        'type'   => 'text',
        'id'     => 1001, // The Field ID must be unique on the form
        'formId' => $form['id'],
        'label'  => 'Activity Category',
        'cssClass' => 'fish',
        'pageNumber'  => 1, // Ensure this is correct        
    ) );
 
    // Create a Single Line text field for the title
    $title = GF_Fields::create( array(
        'type'   => 'text',
        'id'     => 1002, // The Field ID must be unique on the form
        'formId' => $form['id'],
        'label'  => 'Activity Details',
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
        'label'            => 'Academic/Professional/Scholarly Activities',
        'addButtonText'    => 'Add item', // Optional
        'removeButtonText' => 'Remove item', // Optional
        //'maxItems'         => 3, // Optional
        'pageNumber'       => 1, // Ensure this is correct
        'fields'           => array(  $description, $title, $year ), // Add the fields here.
    ) );
 
    $form['fields'][] = $evidence;
 
    return $form;
}
 
// Remove the field before the form is saved. Adjust your form ID
    //REMBER TO CHANGE THIS TO ID 1
add_filter( 'gform_form_update_meta_1', 'remove_my_field', 10, 3 );
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

//INDIVIDUAL TENURE DISPLAY
function build_tenure_table(){
    $html = '';
    if( have_rows('faculty_record', get_the_ID()) ):
            $html = '<h2>Previous Entries</h2>';

            // loop through the rows of data
            while ( have_rows('faculty_record') ) : the_row();
                // display a sub field value               
                $record_title = get_sub_field('record_title');
                $record_category = get_sub_field('record_category');
                $record_year = get_sub_field('record_year');
                $html .= specific_tenure_records($record_title, $record_category, $record_year, get_row_index());
            endwhile;

        else :

            // no rows found

    endif;

    return $html;
}
add_shortcode( 'repeater-table', 'build_tenure_table' );

function specific_tenure_records($title, $category, $year, $row_index){
    global $post;
    $post_id = $post->ID;
    $current_user = get_current_user_id();
    $author_id = get_the_author_meta('ID');
    $html = '';
        $html .= '<div class="row tenure-record" id="tenure-row-'. $row_index . '">';
        $html .= '<div class="col-md-2">' . $year .'</div>';
        $html .= '<div class="col-md-5">' . $title .'</div><div class="col-md-4">' . $category .'</div>';
        $html .= '<div class="col-md-1">';
        if( $author_id === $current_user || is_admin() || is_super_admin()){
            $html .= '<button class="delete" id="delete-' . $row_index . '" data-row="' . $row_index . '" " data-id="' . $post_id . '" data-toggle="tooltip" title="Delete this row.">x</button>';
        }
        $html .= '</div></div>';
    return $html;
}


//ALL TENURE DISPLAY

//look at export to csv js https://stackoverflow.com/questions/7161113/how-do-i-export-html-table-data-as-csv-file
function all_tenure_records(){
    //add query here
    $args = array(
        'post_type'    => 'page',
        'orderby'      => 'title',
        'order' => 'ASC',
        'posts_per_page' => -1,
    );
    $the_query = new WP_Query ( $args );
    echo '<h2>' . $the_query->found_posts . '</h2>';
    if ( $the_query->have_posts() ) :
        echo '<table id="all-data"><tr><th>Name</th><th>Category</th><th>Detail</th><th>Year</th><th>Edit</th></tr>';
        while ( $the_query->have_posts() ) : $the_query->the_post();
            $post_id = get_the_ID();
            $author = get_the_title();            
            echo all_make_tenure_records($post_id, $author);
        endwhile;
        echo '</table>';
    endif;

        // Reset Post Data
        wp_reset_postdata();

    
}

function all_make_tenure_records($post_id, $author){    
    $html = '';
     if( have_rows('faculty_record', $post_id)):
            // loop through the rows of data                    
             while ( have_rows('faculty_record') ) : the_row();
                // display a sub field value               
                $record_title = get_sub_field('record_title');
                $record_category = get_sub_field('record_category');
                $record_year = get_sub_field('record_year');
                $html .= '<tr><td>' . $author . '</td><td>' . $record_category . '</td><td>' . $record_title . '</td><td>' . $record_year . '</td><td>' . data_edit_post($post_id) . '</td></tr>'; 
        endwhile;

        else :

            // no rows found

    endif;
    return $html;
}

function data_edit_post($post_id){
    //wp-admin/post.php?post=61&action=edit
    $url = get_site_url() . '/wp-admin/post.php?post=61&action=edit#acf-group_5cf50e360aba8';
    return '<a href="' . $url . '">edit</a>';
}

add_shortcode( 'all-records', 'all_tenure_records' );




/*
FORM TO ACF 
*/

add_action( 'gform_after_submission_1', 'update_record', 10, 2 );

function update_record($entry, $form){
	global $post;
	$post_id = $post->ID;
	$array = $entry['1000'];
	    foreach ($array as $key => $record) {
			$row = array(
				'record_title' => $record['1002'],
				'record_category' => $record['1001'] ,
				'record_year' => $record['1003'],
			);
			 add_row('faculty_record', $row, $post_id);
		}
}


//add_shortcode( 'update-record', 'update_record' );
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





//ACF JSON SAVER
add_filter('acf/settings/save_json', 'my_acf_json_save_point');
 
function my_acf_json_save_point( $path ) {
    
    // update path
    $path = plugin_dir_path( __FILE__ )  . '/acf-json';
    // return
    return $path;
    
}


add_filter('acf/settings/load_json', 'my_acf_json_load_point');

function my_acf_json_load_point( $paths ) {
    
    // remove original path (optional)
    unset($paths[0]);
    
    
    // append path
    $paths[] = plugin_dir_path( __FILE__ )  . '/acf-json';
    
    
    // return
    return $paths;
    
}


//USER IS there?

function user_is_member(){
    global $post;
    if (is_user_logged_in()){
        $user_id = get_current_user_id();
        $current_users = get_users();
        $user_ids = [];//create array of user IDs
        $url = get_site_url();
        $title = sanitize_title(wp_get_current_user()->user_login);
        foreach ($current_users as $user) {
            array_push($user_ids, $user->ID);
        }
        //var_dump($user_ids);
        if (in_array($user_id, $user_ids)) {
            data_post_finder($title, $user_id);
            if($post->post_name != $title && !is_super_admin() && !is_admin()){ //stop redirect is superadmin
                wp_redirect($url . '/' . $title); 
                exit;
            }
        } else {
            $blog_id = get_current_blog_id();
            kstad_add_user_to_blog($user_id, $blog_id);
            if($post->post_name != $title){
                wp_redirect($url . '/' . $title); 
                exit;
            }
            data_post_finder($title, $user_id);
        }
    }  else {
        return 'Please login.';
    }
}

//add_shortcode( 'mem', 'user_is_member' );
add_action('template_redirect', 'user_is_member');


function data_post_maker($title, $user_id){
    $my_post = array(
      'post_title'    => wp_strip_all_tags( $title ),
      'post_content'  => '<h2>Add Activities</h2><div class="row"><div class="col-md-7">[gravityform id="1" title="false" description="false" ajax="false"]</div><div class="col-md-5">[repeater-table]</div></div>',
      'post_status'   => 'publish',
      'post_author'   => $user_id,
      'post_type'     => 'page',  
      'page_template'  => 'page-templates/fullwidthpage.php',
      //'post_category' => array( 8,39 )
    );
     
    // Insert the post into the database
    $new_post_id = wp_insert_post( $my_post );
    add_post_meta( $new_post_id, 'personal-page', 'personal');
    //wp_redirect();
}


function data_post_finder($title, $user_id){
    $args = array(
      'name'        => $title,
      'post_type'   => 'page',
      'post_status' => 'publish',
      'numberposts' => 1
    );
    $my_posts = get_posts($args);
    if( $my_posts ) {     
      $post_id = get_the_ID();      
      if ($my_posts[0]->ID != $post_id) {
       //nada
      }     
    } else {
        data_post_maker($title, $user_id);
    }   
}


//FRONT PAGE BUTTON FOR DIRECTING TO SPECIFIC PAGE  - PROBABLY NOT NEEDED ANY MORE
// function js_redirector($content){
//     global $post;
//     $personal = get_post_meta($post->ID,'personal-page', true);
//     if (is_user_logged_in() && $personal != 'personal'){
//         $title = sanitize_title(wp_get_current_user()->user_login);
//         $url = '<a class="btn btn-primary" href="' . site_url() .'/'. $title . '">Click to Enter Information</a>';
//         return $content . $url;
//     } else {
//         return $content;
//     }
// }

//add_filter( 'the_content', 'js_redirector' );


//CONTENT VIEW RESTRICTOR 
function filter_all_pages($content){
    global $post;
    $personal = get_post_meta($post->ID,'personal-page', true);// is a personal page
    $user_id = get_current_user_id(); //current user id
    $author_id = intval($post->post_author);
    if ($personal === 'personal'){
        if( $user_id === $author_id || is_super_admin($user_id) || is_admin()){ 
                return $content;//show the logged data option
            }
            else {
                return 'Content restricted. Please <a href="' . wp_login_url().'" title="login">login</a>';
            }
        } else {
            return $content;
        }

}

add_filter('the_content', 'filter_all_pages');


// // returns the content of $GLOBALS['post']
// // if the page is called 'debug'
// function my_the_content_filter($content) {
//   // assuming you have created a page/post entitled 'debug'
//   if ($GLOBALS['post']->post_name == 'debug') {
//     return var_export($GLOBALS['post'], TRUE );
//   }
//   // otherwise returns the database content
//   return $content;
// }

// add_filter( 'the_content', 'my_the_content_filter' );


//add user to blog if not a member
function kstad_add_user_to_blog($user_id, $blog_id){
    if(!is_user_member_of_blog( $user_id, $blog_id )){
       add_user_to_blog($blog_id, $user_id, 'author');       
    }
}


//let authors edit their own pages

//Add capabilities to author, so he/she can edit...    
 function add_author_theme_caps() {
    // gets the author role
    $role = get_role( 'author' );
    $role->add_cap( 'edit_pages' );
    $role->add_cap( 'edit_published_pages' );
    $role->remove_cap( 'edit_posts' ); 
    $role->remove_cap( 'publish_posts' );  
    $role->add_cap( 'publish_pages' );  
    $role->add_cap( 'edit_published_posts' );   
    $role->add_cap( 'edit_published_pages' );       
}
add_action( 'admin_init', 'add_author_theme_caps');


//show acf fields
add_filter( 'acf/settings/remove_wp_meta_box', '__return_false' );


//acf options page
if( function_exists('acf_add_options_page') ) {
    
    acf_add_options_page(array(
        'page_title'    => 'KSTAD Settings',
        'menu_title'    => 'KSTAD Settings',
        'menu_slug'     => 'KSTAD-settings',
        'redirect'  => false
    ));
  
    
}


//display faculty directions
function acf_faculty_directions($content){
    $post_id = $GLOBALS['post']->ID;    
    if(get_post_meta($post_id,'personal-page', true)){
        $directions = get_field('faculty_directions', 'options');
        return $directions . $content;
    } else {
        return $content;
    }
}

add_filter ('the_content', 'acf_faculty_directions');


//add button for info
add_filter('wp_content', 'modify_faculty_titles', 10, 2);

function modify_faculty_titles($content) {
  $post_id = $GLOBALS['post']->ID;    
    if(get_post_meta($post_id,'personal-page', true)){
        $details = '<button class="info" aria-label="extra information" data-toggle="modal" data-target="#cat-details"><i class="fa fa-info-circle" aria-hidden="true"></i></button>';
        return $details . $content ;
    } else {
        return $content;
    }
}
add_filter ('the_content', 'modify_faculty_titles');



add_filter('wp_footer', 'category_modal_content', 10, 2);

function category_modal_content (){
$key = get_field('category_key', 'options');
echo '<div class="modal fade bd-example-modal-lg" id="cat-details" tabindex="-1" role="dialog" aria-labelledby="catModalLabel" aria-hidden="true"><div class="modal-dialog modal-lg" role="document"><div class="modal-content"><div class="modal-header"><h5 class="modal-title" id="catModalLabel">Details</h5><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><div class="modal-body">'.  $key .'</div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button></div></div></div></div>';
}


add_action( 'wp_ajax_repeater_editor', 'repeater_editor' );


//delete that row
function repeater_editor(){
    $post_id = (int) $_POST['id'];
    $row = (int) $_POST['row'];

    delete_row("field_5cf50e404b399", $row, $post_id); //functional 
}    
