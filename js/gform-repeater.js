

//delete rows of entered information
jQuery( document ).on( 'click', '.delete', function() {

    var rownumjs = jQuery(this).data("row");   
    var id = jQuery(this).data("id");
    //hideDeletedRow(rownumjs);
    var confirmThis = confirm("Clicking ok will delete this row of data.");
    if (confirmThis == true) {
      jQuery.post(             
              ajaxurl,
              {
                  'action': 'repeater_editor',
                  'row': rownumjs,
                  'id': id,
              }
              ,
              function(response){
                  console.log(response);            
                  window.location = window.location.href;
              }
      );
    }

});

function hideDeletedRow(id){
 
    var row = document.getElementById('tenure-row-'+id);
    row.classList.add('mark-for-deletion')    
}


//WATCH FOR ADDITIONS
//FROM https://developer.mozilla.org/en-US/docs/Web/API/MutationObserver
  // Select the node that will be observed for mutations
const targetNode = document.getElementById('gform_wrapper_1');

// Options for the observer (which mutations to observe)
const config = { attributes: true, childList: true, subtree: true };

// Callback function to execute when mutations are observed
const callback = function(mutationsList, observer) {
    for(let mutation of mutationsList) {
        if (mutation.type === 'childList') {
            //console.log('A child node has been added or removed.');
            dropDownMaker();

        }
    }
};

// Create an observer instance linked to the callback function
const observer = new MutationObserver(callback);

// Start observing the target node for configured mutations
observer.observe(targetNode, config);

function dropDownMaker(){
        let theInputs = document.querySelectorAll('input');
       theInputs.forEach(function(input,index){
        var theId = input.id;
        var mainId = theId.substring(0, 12);

        if (mainId === 'input_1_1001' && !document.getElementById('list-'+index)){          
          categoryDropDown(theId, categoriesKstad)
          //jQuery('.ui-autocomplete-input').css('width','400px') //makes too many things large
        }
        if (mainId === 'input_1_1003' && !document.getElementById('list-'+index)){
          yearDropDown(theId)
        }

  })
}



function categoryDropDown(id, categories){
  jQuery( function() {
     
      jQuery('#'+id).autocomplete({
        source: categories,
        minLength: 0,
        search: '',
      }).focus(function () {
          jQuery(this).autocomplete('search', jQuery(this).val())
      });
    
  })
}

function yearDropDown(id){
  jQuery( function() {
    var years = [
      "2,018",
      "2,019",
      "2,020",
      "2,021",     
    ];
    jQuery( "#"+id).autocomplete({
      source: years,
      minLength: 0,
      search: '',
    }).focus(function () {
          jQuery(this).autocomplete('search', jQuery(this).val())
      });
  
})
}


function showList(listId,searchId){
  let searcher = document.getElementById(searchId)
  if ( searcher === document.activeElement || searcher.value.length >1 ) { 
    console.log('active')
    document.getElementById(listId).classList.add('show')
  } 

  if ( searcher.value.length === 0 || searcher != document.activeElement ){
     document.getElementById(listId).classList.remove('show')
     document.getElementById(searchId).classList.remove('chosen')
  }
}



jQuery( document ).ready(function() {
  console.log('loaded');
  dropDownMaker();
});






/**
 * Gravity Wiz // Gravity Forms // Disable Submission when Pressing Enter 
 * http://gravitywiz.com/disable-submission-when-pressing-enter-for-gravity-forms/
 */
jQuery(document).on( 'keypress', '.gform_wrapper', function (e) {
    var code = e.keyCode || e.which;
    if ( code == 13 && ! jQuery( e.target ).is( 'textarea,input[type="submit"],input[type="button"]' ) ) {
        e.preventDefault();
        return false;
    }
} );

//turn off autocomplete which is a big mess
function endAutocomplete(){
  let fields = document.querySelectorAll('input');
  console.log(fields)
  fields[0].attributes.autocomplete = "autocomplete_off_hack";
}


const categoriesKstad = [
  {"label": "Opponent för avhandling / thesis", "description": "(Master / Licentiate / PhD / Doctoral thesis, when staff are the external opponents, either for other departments in Kau or other Universities. As some international master / doctoral thesis are examined differently, use this criterion for staff who are External Examiners for Doctoral students / viva’s etc. globally."}, {"label": "Betygskommité / Grading Committee", "description": "(PhD / Doctoral thesis, for the staff who decide the grades for students’ thesis)"}, {"label": "Academic meeting presentation", "description": "(Konferens, Nätverks, Seminarium / Oral Presentations at conferences / workshops, network meetings etc. – where staff have applied / volunteered to speak themselves (rather than be invited))"}, {"label": "Competitive research award / prize", "description": "(E.g. Best paper in journals or conference / other prizes or award opportunities to be included here)"}, {"label": "Editorship of academic journal or Professional Business / Management Publication", "description": "(Faculty who are editors of journals)"}, {"label": "Service on editorial board or committee", "description": "(Faculty who review journal articles, papers for conferences)"}, {"label": "Public inquiry", "description": "(Faculty who have participated in public enquiries)"}, {"label": "NGO (non-governmental organization) report", "description": ""}, {"label": "Validation of scholarly academic status:", "description": "(Through leadership positions, participation in recognized academic societies and associations, academic fellow status, invited presentations (national / international), peer-review assignments, Föredrag, förening, inbjuden gäst etc.)"}, {"label": "Competitive teaching award", "description": "(KAU merited teacher award or equivalent from other institutions)"}, {"label": "Service in development project for collaborations with organizations and other universities", "description": "(Samverkan / Faculty who have played a significant role in establishing partners, collaborations with other Universities, such as exchange partners for students, staff or with organizations / companies to support student activities or engagement between students, staff and industry)"}, {"label": "Service on a board for teaching, research, and academic development", "description": "(Faculty who sit on University, Faculty and School level Committees / Boards)"}, {"label": "Substantive role and participation in academic development project", "description": "(Uppdrag, E.g. Faculty who play a significant role in AACSB, Utveckling av nya kurser/program)"}, {"label": "Consulting activity that are material in terms of time and substance", "description": "(Konsult, Projektledare / Faculty who have consulting activities)"}, {"label": "Faculty internship", "description": "(Faculty who have participated in work, sabbaticals, internships within industry, during the academic year)"}, {"label": "Development and presentation of executive education program or course", "description": "(Commissioned education, activities via Uppdrags AB)"}, {"label": "Significant participation in business professional association", "description": "(Active membership, activities in industry based associations, such as Charted Institute of Personnel and Development, Chartered Institute of Management Accountants etc.)"}, {"label": "Professional meeting presentation", "description": "(Faculty who have either been invited to or applied / volunteered to present at professional meetings with, guest lectures, training for industry or networks or workshops connected to industry, Konferens, Nätverks, Seminarium)"}, {"label": "Active service on a board of directors", "description": "(Ordföramde, medlem i Styrelse, Kommite, jury)"}, {"label": "Participation in professional event that focuses on the practice of business, management, and related issues or other activity that place faculty in direct contact with business or other organizational leaders", "description": ""}, {"label": "Visiting, Guest, Honorary Professor in another institution", "description": "(Nationally / Internationally)"}, {"label": "Organising Conference, Network, Seminar, Workshop, Arrangera conferens/nätverksträff/seminarium/workshop", "description": "WebForm Text"}
];


jQuery('[data-toggle="tooltip"]').tooltip(); //tooltip

