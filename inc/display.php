<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

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
        if( $author_id === $current_user || current_user_can( 'manage_options' ) || is_super_admin()){
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
