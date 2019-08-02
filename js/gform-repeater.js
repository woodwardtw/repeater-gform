

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


// function chooseIt(e){
//   let newChoice = e.innerText;
//   let fieldId = e.parentNode.dataset.field;
//   document.getElementById(fieldId).value = newChoice;
//   //document.getElementById(fieldId).classList.add('chosen') //problem is this gets copied down 
//   let remove = document.getElementById(e.parentNode.id).classList.remove('show')
//   console.log(document.getElementById(e.parentNode.id.classList))
//   //document.getElementById('the-list').style.height = '0px'
//   //document.getElementById('the-list').style.opacity = '0'
//   let stringLeng = fieldId.length;
//   let theItem = fieldId.substring(13,stringLeng); //input_1_1001-0
//   document.getElementById('input_1_1002-'+theItem).focus(); //change focus to next element
// }


// function filterThings(listId, searchId) {
//   // Declare variables
//   var input, filter, ul, li, i, txtValue;
//   input = document.getElementById(searchId);
//   filter = input.value.toUpperCase();
//   ul = document.getElementById(listId);
//   console.log(ul)
//   li = ul.getElementsByTagName('li');
//   console.log(li)
//   // Loop through all list items, and hide those who don't match the search query
//   for (i = 0; i < li.length; i++) {
//      console.log(li[i].innerHTML);
//     txtValue = li[i].innerHTML;
//     if (txtValue.toUpperCase().indexOf(filter) > -1) {
//       li[i].style.display = "";
//     } else {
//       li[i].style.display = "none";
//     }
//   }
// }


// function setUpFilters(){
//   let theInputs = document.querySelectorAll('input');
//   let theItems = '';
//   items.forEach(function(item){
//      theItems = theItems + '<li onclick="chooseIt(this)">' +item.title + '<span class="extras">' + item.description + '</span></li>';
//   })

//   theInputs.forEach(function(input,index){
//     var theId = input.id;
//     var mainId = theId.substring(0, 12);
//     //REMBER TO CHANGE THIS TO ID 1
//     if (mainId === 'input_1_1001' && !document.getElementById('list-'+index)){
//       input.spellcheck = false;
//       input.insertAdjacentHTML('afterend','<ul class="tenure-list" id="list-'+index+'" data-field="' + theId + '">'+theItems+'</ul>');
//       let lists = input.parentNode.querySelectorAll('ul');//deal with gform copying down the list on add item
//       lists.forEach(function(list){
//         if (list.dataset.field != input.id){
//           list.remove()
//         }
//       })
//       input.addEventListener("input", function() {
//       showList('list-'+index, theId); 
//       filterThings('list-'+index, theId);     
      
//     });
//     }
//   })
// }

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


// function doThings(id){ jQuery( function() {
//     const projects = [
//   {
//     "title": "An authored or edited volume published by an academic book publisher",
//     "value": "An authored or edited volume published by an academic book publisher  writing or publication all  "
//   },
//   {
//     "title": "An article in an edited volume published by an academic book publisher",
//     "value": "An article in an edited volume published by an academic book publisher  writing or publication all  "
//   },
//   {
//     "title": "An article in a peer-reviewed conference proceeding",
//     "value": "An article in a peer-reviewed conference proceeding  writing or publication all  "
//   },
//   {
//     "title": "Peer-reviewed working papers",
//     "value": "Peer-reviewed working papers  writing or publication all  "
//   },
//   {
//     "title": "Publication or revision of textbooks",
//     "value": "Publication or revision of textbooks  writing or publication all  "
//   },
//   {
//     "title": "Publication of cases",
//     "value": "Publication of cases  writing or publication all Utveckling skarpa  "
//   },
//   {
//     "title": "Publication of other teaching material",
//     "value": "Publication of other teaching material  writing or publication all Kurslitteratur bokprojekt kompendium  "
//   },
//   {
//     "title": "Publication in non peer-reviewed journals",
//     "value": "Publication in non peer-reviewed journals  writing or publication all  "
//   },
//   {
//     "title": "NGO (non-governmental organization) reports",
//     "value": "NGO (non-governmental organization) reports  writing or publication all  "
//   },
//   {
//     "title": "Approved research grant application",
//     "value": "Approved research grant application  grant or award all  "
//   },
//   {
//     "title": "Competitive research awards / prizes",
//     "value": "Competitive research awards / prizes  grant or award all  "
//   },
//   {
//     "title": "Competitive teaching awards",
//     "value": "Competitive teaching awards  grant or award all  "
//   },
//   {
//     "title": "Faculty opponent on dissertation",
//     "value": "Faculty opponent on dissertation  dissertation all Uppsats avhandling  "
//   },
//   {
//     "title": "Member of dissertation grading committee",
//     "value": "Member of dissertation grading committee  dissertation all uppsats Betygskommité avhandling  "
//   },
//   {
//     "title": "Academic meeting presentations (Conference, Networks, Seminars, Workshops)",
//     "value": "Academic meeting presentations (Conference, Networks, Seminars, Workshops)  presentation all Konferens Nätverks Seminarium  "
//   },
//   {
//     "title": "Editorship with academic journal",
//     "value": "Editorship with academic journal  Editorial all  "
//   },
//   {
//     "title": "Service on editorial boards or committees",
//     "value": "Service on editorial boards or committees  Editorial all Reviewer  "
//   },
//   {
//     "title": "Editorship of professional or other business/management publication",
//     "value": "Editorship of professional or other business/management publication  Editorial all  "
//   },
//   {
//     "title": "Academic meeting proceedings",
//     "value": "Academic meeting proceedings  Meeting all  "
//   },
//   {
//     "title": "Public inquires",
//     "value": "Public inquires  Service all  "
//   },
//   {
//     "title": "PRJ (not on ABS or Noweigan List)",
//     "value": "PRJ (not on ABS or Noweigan List)  all  "
//   },
//   {
//     "title": "KAU University Press Reports",
//     "value": "KAU University Press Reports  Publication all  "
//   },
//   {
//     "title": "Validation of scholarly academic status through leadership positions, participation in recognized academic societies and associations, academic fellow status, invited presentations (national / international), peer-review assignments, etc.",
//     "value": "Validation of scholarly academic status through leadership positions, participation in recognized academic societies and associations, academic fellow status, invited presentations (national / international), peer-review assignments, etc.  all Föredrag förening inbjuden gäst  "
//   },
//   {
//     "title": "PhD courses in the discipline",
//     "value": "PhD courses in the discipline  Publication all  "
//   },
//   {
//     "title": "Service in development projects for collaborations with organizations and other universities",
//     "value": "Service in development projects for collaborations with organizations and other universities  Service all samverkan  "
//   },
//   {
//     "title": "Service on boards for teaching, research, and academic development",
//     "value": "Service on boards for teaching, research, and academic development  Service all  "
//   },
//   {
//     "title": "Substantive roles and participation in academic development projects",
//     "value": "Substantive roles and participation in academic development projects  Service all Uppdrag  "
//   },
//   {
//     "title": "Consulting activities that are material in terms of time and substance",
//     "value": "Consulting activities that are material in terms of time and substance  Service all Konsult  "
//   },
//   {
//     "title": "Faculty internships",
//     "value": "Faculty internships  Service all  "
//   },
//   {
//     "title": "Development and presentation of executive education programs",
//     "value": "Development and presentation of executive education programs  Service all Utveckling  "
//   },
//   {
//     "title": "Sustained professional work supporting qualified status",
//     "value": "Sustained professional work supporting qualified status  Service all  "
//   },
//   {
//     "title": "Significant participation in business professional associations",
//     "value": "Significant participation in business professional associations  Service all  "
//   },
//   {
//     "title": "Professional meeting proceedings",
//     "value": "Professional meeting proceedings  Service all  "
//   },
//   {
//     "title": "Professional meeting presentations",
//     "value": "Professional meeting presentations  Service all  "
//   },
//   {
//     "title": "Relevant, active service on boards of directors",
//     "value": "Relevant, active service on boards of directors  Service all Ordföramde Styrelse Kommite  "
//   },
//   {
//     "title": "Documented continuing professional education experiences",
//     "value": "Documented continuing professional education experiences  Education all  "
//   },
//   {
//     "title": "Participation in professional events that focus on the practice of business, management, and related issues",
//     "value": "Participation in professional events that focus on the practice of business, management, and related issues  Education all  "
//   },
//   {
//     "title": "Participation in other activities that place faculty in direct contact with business or other organizational leaders",
//     "value": "Participation in other activities that place faculty in direct contact with business or other organizational leaders  Education all  "
//   },
//   {
//     "title": "Visiting & Guest Professor",
//     "value": "Visiting & Guest Professor  Teaching all  "
//   },
//   {
//     "title": "Media (TV, Radio, Newspaper Articles & Presentations)",
//     "value": "Media (TV, Radio, Newspaper Articles & Presentations)  Teaching all Tidning Artikla  "
//   },
//   {
//     "title": "Development of new courses / programmes",
//     "value": "Development of new courses / programmes  Teaching all  "
//   },
//   {
//     "title": "Teacher Exchange",
//     "value": "Teacher Exchange  Teaching all  "
//   },
//   {
//     "title": "Organising Conference, Networks, Seminars, Workshops",
//     "value": "Organising Conference, Networks, Seminars, Workshops  Teaching all  "
//   },
//   {
//     "title": "Teaching, Leading, Presenting at Summerschools",
//     "value": "Teaching, Leading, Presenting at Summerschools  Teaching all  "
//   }
// ]
//     id.autocomplete({
//       minLength: 0,
//       source: projects,
//       focus: function( event, ui ) {
//         id.val( ui.item.title );
//         return true;
//       },
//       select: function( event, ui ) {
//         jQuery( "#project" ).val( ui.item.title );
//         jQuery( "#project-id" ).val( ui.item.value ); 
//         return false;
//       }
//     })
//     .autocomplete( "instance" )._renderItem = function( ul, item ) {
//       return jQuery( "<li>" )
//         .append( "<div>" + item.title + "</div>" )
//         .appendTo( ul );
//     };
//   });
         

//   }