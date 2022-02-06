<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


add_filter( 'the_content', 'add_acf_form', 1 );
 
function add_acf_form( $content ) {
    $content = str_replace('<h2>Add Activities</h2>', '', $content);
    $new_form = str_replace('gravityform id="1"', 'gravityform id="4"', $content);
    
    return $new_form;
}

add_action( 'gform_after_submission_4', 'swede_update_record', 10, 2 );//pay attention to form ID

function swede_update_record($entry, $form){
    global $post;
    $post_id = $post->ID;
    $record = $entry;
    var_dump($record);
    $record_category = swede_one_cat($entry['1'], $entry['5']);
           
            $row = array(
                'record_title' => sweded_data_set('8', $entry),
                'record_category' => $record_category,
                'record_year' => sweded_data_set('10', $entry),
                //presentation specific
                'presentation_title' => sweded_data_set('11', $entry),
                'presentation_host' => sweded_data_set('12', $entry),
                'presentation_location' => sweded_data_set('13', $entry),
               //hosted visitor specific
                'hosted_visitor_org' => sweded_data_set('27', $entry),
                'hosted_visitor_activity' => sweded_data_set('26', $entry),
               //impact specific 
                'societal_impact' => sweded_data_set('5', $entry),
                // 'impact_type' => sweded_data_set('17', $entry),
                'impact_type_string' => sweded_data_set('17', $entry),
                //societal impact
                'societal_impact_type' => sweded_data_set('17', $entry),
                //exterma org collab
                'external_org_contribution' => sweded_data_set('25', $entry),
                'course_code_and_term' => sweded_data_set('20', $entry),
                'term_and_year' => sweded_data_set('21', $entry),
                'organization_name' => sweded_data_set('22', $entry),
                'location_of_organization' => sweded_data_set('23', $entry),
                'type_of_collaboration' => sweded_data_set('24', $entry),
            );
            //var_dump($row);
             add_row('faculty_record', $row, $post_id);
}


function swede_one_cat($entry_1, $entry_2){
    if($entry_1 != "Deselect" && $entry_1 != null){
        return $entry_1;
    } else {
        return $entry_2;
    }

}

function sweded_data_set($field, $entry){
    if (array_key_exists($field, $entry)){
        return $entry[$field];
    } else {
        return '';
    }
}

//***********************************ORIGINAL---OLD---------
//BUILD GRAVITY FORM

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
        'label'  => 'Does this activity have or potentially have societal impact?',
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
                "text" => "Use of intellectual contribution in education",
                "value" => "Use of intellectual contribution in education",
                'isSelected' => false,
            ),
            array(
                "text" => "Use of intellectual contribution in organizations",
                "value" => "Use of intellectual contribution in organizations",
                'isSelected' => false,
            ),
            array(
                "text" => "Use of intellectual contribution in research",
                "value" => "Use of intellectual contribution in research",
                'isSelected' => false,
            ),
            array(
                "text" => "Use of intellectual contribution in society",
                "value" => "Use of intellectual contribution in society",
                'isSelected' => false,
            ),
            
         ],
        'inputs' => [
        array(
                "id" => "1010.1",
                "value" => "Use of intellectual contribution in education",
                "name" => '',
            ),
        array(
            "id" => "1010.2",
            "value" => "Use of intellectual contribution in organizations",
            "name" => '',
        ),
        array(
            "id" => "1010.3",
            "value" => "Use of intellectual contribution in research",
            "name" => '',
        ),
        array(
            "id" => "1010.4",
            "value" => "Use of intellectual contribution in society",
            "name" => '',
        ),                
           

    ],
    ) );

//*************************** END conditional additions for Impact


//*************************** START conditional additions for external collab
  //create a checkbox for external org
  //DONT FORGET TO ADD TO FORM CONSTRUCTION AND CHANGE INITIAL VARIABLE NAME  
  $external = GF_Fields::create( array(
    'type'   => 'checkbox',
    'id'     => '1011', // The Field ID must be unique on the form
    'formId' => $form['id'],
    'label'  => 'Has an external organization contributed to your course? ',
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
            'id' => '1011.1',
            'label' => 'Yes',
            'name' => ''
        )
    ],
) );

$course_info = GF_Fields::create( array(
    'type'   => 'text',
    'id'     => '1012', // The Field ID must be unique on the form
    'formId' => $form['id'],
    'label'  => 'Course Code and Title',
    'pageNumber'  => 1, // Ensure this is correct        
) );

$term = GF_Fields::create( array(
    'type'   => 'text',
    'id'     => '1013', // The Field ID must be unique on the form
    'formId' => $form['id'],
    'label'  => 'Term and Year',
    'pageNumber'  => 1, // Ensure this is correct        
) );

$collab = GF_Fields::create( array(
    'type'   => 'text',
    'id'     => '1014', // The Field ID must be unique on the form
    'formId' => $form['id'],
    'label'  => 'Organization Name',
    'cssClass' => 'REID',
    'pageNumber'  => 1, // Ensure this is correct        
) );

$location = GF_Fields::create( array(
    'type'   => 'text',
    'id'     => '1015', // The Field ID must be unique on the form
    'formId' => $form['id'],
    'label'  => 'Location of organization (city, country)',
    'pageNumber'  => 1, // Ensure this is correct        
) );


$collab_choices = array(
    array(
      'text'       => 'Guest lecture',
      'value'      => 'Guest Lecture',
      'isSelected' => false,
      'price'      => '' //only populated if a product, product option, shipping field
    ),
    array(
      'text'       => 'Assess course',
      'value'      => 'Assess course',
      'isSelected' => false,
      'price'      => ''
    ),
    array(
      'text'       => 'Traineeship',
      'value'      => 'Traineeship',
      'isSelected' => false,
      'price'      => ''
    ),
    array(
        'text'       => 'Mentor',
        'value'      => 'Mentor',
        'isSelected' => false,
        'price'      => ''
      ),
      array(
        'text'       => 'Co-produce teaching materials (cases, texts, tools)',
        'value'      => 'Co-produce teaching materials (cases, texts, tools)',
        'isSelected' => false,
        'price'      => ''
      ),
      array(
        'text'       => 'Study visit',
        'value'      => 'Study visit',
        'isSelected' => false,
        'price'      => ''
      ),
      array(
        'text'       => 'Thesis work',
        'value'      => 'Thesis work',
        'isSelected' => false,
        'price'      => ''
      ),
      array(
        'text'       => 'Networks (academic or professional)',
        'value'      => 'Networks',
        'isSelected' => false,
        'price'      => ''
      ),
      array(
        'text'       => 'Other',
        'value'      => 'Other',
        'isSelected' => false,
        'price'      => ''
      ),
  );


$collab_type =  GF_Fields::create( array(
    'type'   => 'select',
    'id'     => '1016', // The Field ID must be unique on the form
    'formId' => $form['id'],
    'required' => true,
    'label'  => 'Type of collaboration',
    'choices'  => $collab_choices,
    'pageNumber'  => 1, // Ensure this is correct
) );

    // Create a repeater for the team members and add the name and email fields as the fields to display inside the repeater.
    $evidence = GF_Fields::create( array(
        'type'             => 'repeater',
        //'description'      => 'No max',
        'id'               => 1000, // The Field ID must be unique on the form
        'formId'           => $form['id'],
        'label'            => 'Academic/Professional/Scholarly Activities',
        'addButtonText'    => 'Add another activity', // Optional
        'removeButtonText' => 'Remove activity', // Optional
        //'maxItems'         => 3, // Optional
        'pageNumber'       => 1, // Ensure this is correct
        'fields'           => array( $description, 
                                      $title, 
                                      $year, 
                                      $presentation_title, 
                                      $presentation_host, 
                                      $presentation_location, 
                                      $hosting_source, 
                                      $hosting_activity, 
                                      $impact, 
                                      $impact_type, 
                                      $external,
                                      $course_info,
                                      $term,
                                      $collab,
                                      $location,
                                      $collab_type,
                                     ), // Add the fields here. ***DON'T FORGET!!!!
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
FORM TO ACF UPDATE ACTUAL DATA
*/

add_action( 'gform_after_submission_1', 'update_record', 10, 2 );

function update_record($entry, $form){
    global $post;
    $post_id = $post->ID;
    $record = $entry;
    var_dump($record);


        foreach ($record as $key => $entry) {
            // $all_impacts = array($entry['1010.1'],
            //                     $entry['1010.2'],
            //                     $entry['1010.3'],
            //                     $entry['1010.4'],
            //                     $entry['1010.5'],
            //                     $entry['1010.6'],
            //                     $entry['1010.7'],
            //                     $entry['1010.8'],
            //                     $entry['1010.9'],
            //                     $entry['1010.11'],
            //                     $entry['1010.12'],
            //                     $entry['1010.13'],
            //                     $entry['1010.14'],
            //                     $entry['1010.15'],
            //                     $entry['1010.16'],
            //                     $entry['1010.17'], 
            //                     $entry['1010.18']);
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
                //exterma org collab
                'external_org_contribution' => $entry['1011.1'],
                'course_code_and_term' => $entry['1012'],
                'term_and_year' => $entry['1013'],
                'organization_name' => $entry['1014'],
                'location_of_organization' => $entry['1015'],
                'type_of_collaboration' => $entry['1016'],
            );
            //var_dump($row);
             add_row('faculty_record', $row, $post_id);
        }
}

