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

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;



// Array of files to include.
$swede_data_includes = array(
      '/display.php',                  // 
      '/acf.php',                  // 
      '/form.php'

);

foreach ( $swede_data_includes as $file ) {
   require_once plugin_dir_path( __FILE__ ) .'/inc/' . $file;
}


add_action('wp_enqueue_scripts', 'swede_data_load_scripts');

function swede_data_load_scripts() {                           
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
            if($post->post_name != $title && !is_super_admin() && !current_user_can( 'manage_options' )&& get_current_user_id() != 630){ //stop redirect is superadmin
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
        if( $user_id === $author_id || is_super_admin($user_id) || current_user_can( 'manage_options' )){ 
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
