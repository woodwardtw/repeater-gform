<?php 
/*
Plugin Name: repeater gform - deals with accreditation tracking
Plugin URI:  https://github.com/
Description: For repeater fields and other pieces that merge with gravity - kstad
Version:     2.0
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
    $version= '2.0'; 
    $in_footer = true;
    wp_enqueue_script('list-js', 'https://cdnjs.cloudflare.com/ajax/libs/list.js/1.5.0/list.min.js', $deps, $version, $in_footer);  
    wp_enqueue_script('jqueryUI', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js', $deps, $version, $in_footer);     
    wp_enqueue_script('gform-repeater-js', plugin_dir_url( __FILE__) . 'js/gform-repeater.js', $deps, $version, $in_footer); 
    wp_localize_script('gform-repeater-js', 'ajaxurl', admin_url( 'admin-ajax.php' ) );  
    wp_enqueue_style( 'gform-repeater-main-css', plugin_dir_url( __FILE__) . 'css/gform-repeater-main.css');
    wp_enqueue_style( 'jqueryUI-css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
    wp_localize_script('gform-repeater-js', 'ajax_var', array(
            'url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ajax-nonce')
        ));
}



// Adjust your form ID
    //REMBER TO CHANGE THIS TO ID 1
add_filter( 'gform_form_post_get_meta_1', 'add_my_field' );
function add_my_field( $form ) {

     // Create a category for the evidence
    $description = GF_Fields::create( array(
        'type'   => 'text',
        'id'     => '1001', // The Field ID must be unique on the form
        'formId' => $form['id'],
        'label'  => 'Activity Category',
        'pageNumber'  => 1, // Ensure this is correct        
    ) );
 
    // Create a Single Line text field for the title
    $title = GF_Fields::create( array(
        'type'   => 'text',
        'id'     => '1002', // The Field ID must be unique on the form
        'formId' => $form['id'],
        'label'  => 'Activity Details',
        'pageNumber'  => 1, // Ensure this is correct
    ) );
    

    $year = GF_Fields::create( array(
        'type'   => 'number',
        'id'     => '1003', // The Field ID must be unique on the form
        'formId' => $form['id'],
        'label'  => 'Year',
        'pageNumber'  => 1, // Ensure this is correct        
    ) );

//*************************** START conditional additions for presentation
    $presentation_title = GF_Fields::create( array(
        'type'   => 'text',
        'id'     => '1004', // The Field ID must be unique on the form
        'formId' => $form['id'],
        'label'  => 'Title of Presentation',
        'pageNumber'  => 1, // Ensure this is correct        
    ) );

    $presentation_host = GF_Fields::create( array(
        'type'   => 'text',
        'id'     => '1005', // The Field ID must be unique on the form
        'formId' => $form['id'],
        'label'  => 'Presentation Host',
        'pageNumber'  => 1, // Ensure this is correct        
    ) );

    $presentation_location = GF_Fields::create( array(
        'type'   => 'text',
        'id'     => '1006', // The Field ID must be unique on the form
        'formId' => $form['id'],
        'label'  => 'Location of Presentation',
        'pageNumber'  => 1, // Ensure this is correct        
    ) );

    //visitor hosting
    $hosting_source = GF_Fields::create( array(
        'type'   => 'text',
        'id'     => '1007', // The Field ID must be unique on the form
        'formId' => $form['id'],
        'label'  => 'Visitor University or Organization name',
        'pageNumber'  => 1, // Ensure this is correct        
    ) );

     $hosting_activity = GF_Fields::create( array(
        'type'   => 'text',
        'id'     => '1008', // The Field ID must be unique on the form
        'formId' => $form['id'],
        'label'  => 'Activity performed e.g. lecture, assessment, research interview',
        'pageNumber'  => 1, // Ensure this is correct   

    ) );
 //*************************** END conditional additions

 //*************************** START conditional additions for Impact
  //create a checkbox for impact
   
    $impact = GF_Fields::create( array(
        'type'   => 'checkbox',
        'id'     => '1009', // The Field ID must be unique on the form
        'formId' => $form['id'],
        'label'  => 'Societal Impact?',
        'pageNumber'  => 1, // Ensure this is correct     
        'choices' => [ array(
            'text' => 'Yes',
            'value' => 'yes',
            'isSelected' => false,
            'price' => ''
            )
        ],
        'inputs' => [
            array(
                'id' => '1009.1',
                'label' => 'Yes',
                'name' => ''
            )
        ],
    ) );
   
    $impact_type = GF_Fields::create( array(
        'type'   => 'checkbox',
        'id'     => '1010', // The Field ID must be unique on the form
        'formId' => $form['id'],
        'label'  => '',
        'pageNumber'  => 1, // Ensure this is correct     
        'choices' => [
        array(
                "text" => "Basis for decisions and policies with implications on people",
                "value" => "Basis for decisions and policies with implications on people",
                'isSelected' => false,
            ),
             array(
                "text" => "Basis for decisions and policies with implications on planet",
                "value" => "Basis for decisions and policies with implications on planet",
                'isSelected' => false,
            ),
            array(
                "text" => "Basis for decisions and policies with implications on profit",
                "value" => "Basis for decisions and policies with implications on profit",
                'isSelected' => false,
            ),
            array(
                "text" => "Dissemination in academic channels",
                "value" => "Dissemination in academic channels",
                'isSelected' => false,
            ),
            array(
                "text" => "Dissemination in public channels",
                "value" => "Dissemination in public channels",
                'isSelected' => false,
            ),
             array(
                "text" => "Forms of co-production",
                "value" => "Forms of co-production",
                'isSelected' => false,
            ),
             array(
                "text" => "Global scope in research",
                "value" => "Global scope in research",
                'isSelected' => false,
            ),
             array(
                "text" => "National scope in research",
                "value" => "National scope in research",
                'isSelected' => false,
            ),
             array(
                "text" => "Local scope in research",
                "value" => "Local scope in research",
                'isSelected' => false,
            ),
             array(
                "text" => "Innovation in research",
                "value" => "Innovation in research",
                'isSelected' => false,
            ),
             array(
                "text" => "Innovations in sustainable ways of working",
                "value" => "Innovations in sustainable ways of working",
                'isSelected' => false,
            ),
             array(
                "text" => "Interdisciplinary initiatives",
                "value" => "Interdisciplinary initiatives",
                'isSelected' => false,
            ),
             array(
                "text" => "Research awards",
                "value" => "Research awards",
                'isSelected' => false,
            ),
             array(
                "text" => "Use of ICs in education",
                "value" => "Use of ICs in education",
                'isSelected' => false,
            ),
             array(
                "text" => "Use of ICs in organizations",
                "value" => "Use of ICs in organizations",
                'isSelected' => false,
            ),
             array(
                "text" => "Use of ICs in research",
                "value" => "Use of ICs in research",
                'isSelected' => false,
            ),
             array(
                "text" => "Use of ICs in society",
                "value" => "Use of ICs in society",
                'isSelected' => false,
            ),
         ],
        'inputs' => [
        array(
                "id" => "1010.1",
                "value" => "Basis for decisions and policies with implications on people",
                "name" => '',
            ),
            array(
                "id" => "1010.2",
                "value" => "Basis for decisions and policies with implications on planet",
                'name' => '',
            ),
            array(
                "id" => "1010.3",
                "value" => "Basis for decisions and policies with implications on profit",
                'name' => '',
            ),
            array(
                "id" => "1010.4",
                "value" => "Dissemination in academic channels",
                'name' => '',
            ),
            array(
                "id" => "1010.5",
                "value" => "Dissemination in public channels",
                'name' => '',
            ),
             array(
                "id" => "1010.6",
                "value" => "Forms of co-production",
                'name' => '',
            ),
             array(
                "id" => "1010.7",
                "value" => "Global scope in research",
                'name' => '',
            ),
             array(
                "id" => "1010.8",
                "value" => "National scope in research",
                'name' => '',
            ),
             array(
                "id" => "1010.9",
                "value" => "Local scope in research",
                'name' => '',
            ),
             array(
                "id" => "1010.11",
                "value" => "Innovation in research",
                'name' => '',
            ),
             array(
                "id" => "1010.12",
                "value" => "Innovations in sustainable ways of working",
                'name' => '',
            ),
             array(
                "id" => "1010.13",
                "value" => "Interdisciplinary initiatives",
                'name' => '',
            ),
             array(
                "id" => "1010.14",
                "value" => "Research awards",
                'name' => '',
            ),
             array(
                "id" => "1010.15",
                "value" => "Use of ICs in education",
                'name' => '',
            ),
             array(
                "id" => "1010.16",
                "value" => "Use of ICs in organizations",
                'name' => '',
            ),
             array(
                "id" => "1010.17",
                "value" => "Use of ICs in research",
                'name' => '',
            ),
             array(
                "id" => "1010.18",
                "value" => "Use of ICs in society",
                'name' => '',             
            ),

    ],
    ) );

//*************************** END conditional additions for Impact



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
        'fields'           => array( $description, $title, $year, $presentation_title, $presentation_host, $presentation_location, $hosting_source, $hosting_activity, $impact, $impact_type ), // Add the fields here. ***DON'T FORGET!!!!
    ) );
 
    //$form['fields'][] = $evidence;
    array_splice( $form['fields'], 2, 0, array( $evidence ) );
    return $form;
}
 
// Remove the field before the form is saved. Adjust your form ID
    //REMBER TO CHANGE THIS TO ID 1
add_filter( 'gform_form_update_meta_1', 'remove_my_field', 10, 3 );
function remove_my_field( $form_meta, $form_id, $meta_name ) {
 
    if ( $meta_name == 'display_meta' ) {
        // Remove the Repeater field: ID 1000
        $form_meta['fields'] = wp_list_filter( $form_meta['fields'], array( 'id' => 1 ), 'NOT' );
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
                $impact = get_sub_field('societal_impact');
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
     if(!is_user_logged_in()){
        $current_url = home_url( add_query_arg( [], $GLOBALS['wp']->request ) );
        return '<a href="'.wp_login_url($current_url).'">Please Login</a>';
    } else {
        $args = array(
            'post_type'    => 'page',
            'orderby'      => 'title',
            'order' => 'ASC',
            'posts_per_page' => -1,
        );
        $the_query = new WP_Query ( $args );
        echo '<h2>' . $the_query->found_posts . '</h2>';
        echo '<button class="btn btn-primary" id="download">Download Data</button>';
        if ( $the_query->have_posts() ) :
            echo '<table id="all-data"><tr><th>Name</th><th>Category</th><th>Detail</th><th>Year</th><th>Edit</th><th>Recorded</th></tr>';
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
                $record_recorded = get_sub_field('recorded');
                //presentation details
                if(get_sub_field('presentation_title')){
                    $presentation_title = get_sub_field('presentation_title');
                    $presentation_host = get_sub_field('presentation_host');
                    $presentation_location = get_sub_field('presentation_location');
                    $presentation_details = ' - ' . $presentation_title . ' - ' . $presentation_location . ' - ' . $presentation_host;
                } else {
                    $presentation_details = '';
                }
              
                //visitor hosting
                if(get_sub_field('hosted_visitor_org')){
                    $visitor_host = get_sub_field('hosted_visitor_org');
                    $visitor_source = get_sub_field('hosted_visitor_activity');
                    $visitor_details = ' - ' . $visitor_host . ' - ' . $visitor_source;
                } else {
                    $visitor_details = '';
                }
                

                $html .= '<tr><td>' . $author . '</td><td>' . $record_category . '</td><td>' . $record_title . $presentation_details . $visitor_details . '</td><td>' . $record_year . '</td><td>' . data_edit_post($post_id) . '</td><td><input class="recorded" type="checkbox" data-post_id="'.$post_id.'" data-row="' . get_row_index() . '" data-checked="' . $record_recorded . '" name="recorded-'. get_row_index().'" ' . recorded_checkbox($record_recorded) . '></td></tr>'; 
        endwhile;

        else :

            // no rows found

    endif;
    return $html;
}

function data_edit_post($post_id){
    //wp-admin/post.php?post=61&action=edit
    $url = get_site_url() . '/wp-admin/post.php?post='.$post_id.'&action=edit#acf-group_5cf50e360aba8';
    return '<a href="' . $url . '">edit</a>';
}

function recorded_checkbox($state){
    if ($state){
         if ($state == 'Yes' || $state == 'yes'){
        return 'checked';
        }
    }   
}

add_shortcode( 'all-records', 'all_tenure_records' );

add_action('wp_ajax_update-recorded-status', 'update_recorded_status');

function update_recorded_status(){
     $nonce = $_POST['nonce'];
   
    // if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) )
    //     die ( 'Busted!');
     
    //if(isset($_POST['update-recorded-status'])){
        //write_log("The post id is {$_POST['post_id']} and the row is {$_POST['row']} status is {$_POST['status']} ");

        $post_id = $_POST['post_id'];
        $row = $_POST['row'];
        $status = $_POST['status'];
        update_sub_field( array('field_5cf50e404b399', $row, 'field_5d8813a24f8d6'), $status, $post_id);
    //}
}


/*
FORM TO ACF UPDATE ACTUAL DATA
*/

add_action( 'gform_after_submission_1', 'update_record', 10, 2 );

function update_record($entry, $form){
    global $post;
    $post_id = $post->ID;
    $record = $entry['1000'];
    //var_dump($record);


        foreach ($record as $key => $entry) {
            $all_impacts = array($entry['1010.1'],$entry['1010.2'],$entry['1010.3'],$entry['1010.4'],$entry['1010.5'],$entry['1010.6'],$entry['1010.7'],$entry['1010.8'],$entry['1010.9'],$entry['1010.11'],$entry['1010.12'],$entry['1010.13'],$entry['1010.14'],$entry['1010.15'],$entry['1010.16'],$entry['1010.17'], $entry['1010.18']);
            $row = array(
                'record_title' => $entry['1002'],
                'record_category' => $entry['1001'] ,
                'record_year' => $entry['1003'],
                //presentation specific
                'presentation_title' => $entry['1004'],
                'presentation_host' => $entry['1005'],
                'presentation_location' => $entry['1006'],
               //hosted visitor specific
                'hosted_visitor_org' => $entry['1007'],
                'hosted_visitor_activity' => $entry['1008'],
               //impact specific 
                'societal_impact' => $entry['1009.1'],
                'impact_type' => $all_impacts,
                'impact_type_string' => implode(', ', array_filter($all_impacts)),
            );
            //var_dump($row);
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
            if($post->post_name != $title && !is_super_admin() && !is_admin() && get_current_user_id() != 630){ //stop redirect is superadmin
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


if ( ! function_exists('write_log')) {
   function write_log ( $log )  {
      if ( is_array( $log ) || is_object( $log ) ) {
         error_log( print_r( $log, true ) );
      } else {
         error_log( $log );
      }
   }
}
