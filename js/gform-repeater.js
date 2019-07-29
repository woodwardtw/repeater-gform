

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


// //watch page
// MutationObserver = window.MutationObserver || window.WebKitMutationObserver;

//   var observer = new MutationObserver(function(mutations, observer) {
//       // fired when a mutation occurs
//       //console.log(mutations, observer);
  //     let theInputs = document.querySelectorAll('input');
  //      theInputs.forEach(function(input,index){
  //       var theId = input.id;
  //       var mainId = theId.substring(0, 12);

  //       if (mainId === 'input_1_1001' && !document.getElementById('list-'+index)){          
  //         categoryDropDown(theId, categoriesKstad)
  //       }
  //       if (mainId === 'input_1_1003' && !document.getElementById('list-'+index)){
  //         yearDropDown(theId)
  //       }

  // })



// });

//FROM https://developer.mozilla.org/en-US/docs/Web/API/MutationObserver
  // Select the node that will be observed for mutations
const targetNode = document.getElementById('gform_wrapper_1');

// Options for the observer (which mutations to observe)
const config = { attributes: true, childList: true, subtree: true };

// Callback function to execute when mutations are observed
const callback = function(mutationsList, observer) {
    for(let mutation of mutationsList) {
        if (mutation.type === 'childList') {
            console.log('A child node has been added or removed.');
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



// // define what element should be observed by the observer
// // and what types of mutations trigger the callback
// observer.observe(document, {
//   subtree: true,
//   attributes: true
//   //...
// });

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
  {
    label: 'An authored or edited volume published by an academic book publisher',
    description: 'writing or publication all'
  },
  {
    label: 'An article in an edited volume published by an academic book publisher',
    description: 'writing or publication all'
  },
  {
    label: 'An article in a peer-reviewed conference proceeding',
    description: 'writing or publication all'
  },
   {
    label: 'Peer-reviewed working papers',
    description: 'writing or publication all'
  },
  {
    label: 'Publication or revision of textbooks',
    description: 'writing or publication all'
  },
   {
    label: 'Publication of cases',
    description: 'writing or publication all Utveckling skarpa'
  },
   {
    label: 'Publication of other teaching material',
    description: 'writing or publication all Kurslitteratur bokprojekt kompendium'
  },
   {
    label: 'Publication in non peer-reviewed journals',
    description: 'writing or publication all'
  },
  {
    label: 'NGO (non-governmental organization) reports',
    description: 'writing or publication all'
  },
  {
    label: 'Approved research grant application',
    description: 'grant or award all'
  },
  {
    label: 'Competitive research awards / prizes',
    description: 'grant or award all'
  },
  {
    label: 'Competitive teaching awards',
    description: 'grant or award all'
  },
  {
    label: 'Faculty opponent on dissertation',
    description: 'dissertation all Uppsats avhandling'
  },
  {
    label: 'Member of dissertation grading committee',
    description: 'dissertation all uppsats Betygskommité avhandling'
  },
  {
    label: 'Academic meeting presentations (Conference, Networks, Seminars, Workshops)',
    description: 'presentation all Konferens Nätverks Seminarium'
  },
  {
    label: 'Editorship with academic journal',
    description: 'Editorial all'
  },
  {
    label: 'Service on editorial boards or committees',
    description: 'Editorial all Reviewer'
  },
  {
    label: 'Editorship of professional or other business/management publication',
    description: 'Editorial all'
  },
  {
    label: 'Academic meeting proceedings',
    description: 'Meeting all'
  },
  {
    label: 'Public inquires',
    description: 'Service all'
  },
  {
    label: 'PRJ (not on ABS or Noweigan List)',
    description: 'all'
  },
  {
    label: 'KAU University Press Reports',
    description: 'Publication all'
  },
  {
    label: 'Validation of scholarly academic status through leadership positions, participation in recognized academic societies and associations, academic fellow status, invited presentations (national / international), peer-review assignments, etc.',
    description: 'all Föredrag förening inbjuden gäst'
  },
  {
    label: 'PhD courses in the discipline',
    description: 'Publication all'
  },
  {
    label: 'Service in development projects for collaborations with organizations and other universities',
    description: 'Service all samverkan'
  },
  {
    label: 'Service on boards for teaching, research, and academic development',
    description: 'Service all'
  },
  {
    label: 'Substantive roles and participation in academic development projects',
    description: 'Service all Uppdrag'
  },
  {
    label: 'Consulting activities that are material in terms of time and substance',
    description: 'Service all Konsult'
  },
  {
    label: 'Faculty internships',
    description: 'Service all'
  },
  {
    label: 'Development and presentation of executive education programs',
    description: 'Service all Utveckling'
  },
  {
    label: 'Sustained professional work supporting qualified status',
    description: 'Service all'
  },
  {
    label: 'Significant participation in business professional associations',
    description: 'Service all'
  },
  {
    label: 'Professional meeting proceedings',
    description: 'Service all'
  },
  {
    label: 'Professional meeting presentations',
    description: 'Service all'
  },
  {
    label: 'Relevant, active service on boards of directors',
    description: 'Service all Ordföramde Styrelse Kommite'
  },
  {
    label: 'Documented continuing professional education experiences',
    description: 'Education all'
  },
  {
    label: 'Participation in professional events that focus on the practice of business, management, and related issues',
    description: 'Education all'
  },
  {
    label: 'Participation in other activities that place faculty in direct contact with business or other organizational leaders',
    description: 'Education all'
  },
  {
    label: 'Visiting & Guest Professor',
    description: 'Teaching all'
  },
  {
    label: 'Media (TV, Radio, Newspaper Articles & Presentations)',
    description: 'Teaching all Tidning Artikla'
  },
  {
    label: 'Development of new courses / programmes',
    description: 'Teaching all'
  },
  {
    label: 'Teacher Exchange',
    description: 'Teaching all'
  },
  {
    label: 'Organising Conference, Networks, Seminars, Workshops',
    description: 'Teaching all'
  },
  {
    label: 'Teaching, Leading, Presenting at Summerschools',
    description: 'Teaching all'
  }
];


jQuery('[data-toggle="tooltip"]').tooltip(); //tooltip


function doThings(id){ jQuery( function() {
    const projects = [
  {
    "title": "An authored or edited volume published by an academic book publisher",
    "value": "An authored or edited volume published by an academic book publisher  writing or publication all  "
  },
  {
    "title": "An article in an edited volume published by an academic book publisher",
    "value": "An article in an edited volume published by an academic book publisher  writing or publication all  "
  },
  {
    "title": "An article in a peer-reviewed conference proceeding",
    "value": "An article in a peer-reviewed conference proceeding  writing or publication all  "
  },
  {
    "title": "Peer-reviewed working papers",
    "value": "Peer-reviewed working papers  writing or publication all  "
  },
  {
    "title": "Publication or revision of textbooks",
    "value": "Publication or revision of textbooks  writing or publication all  "
  },
  {
    "title": "Publication of cases",
    "value": "Publication of cases  writing or publication all Utveckling skarpa  "
  },
  {
    "title": "Publication of other teaching material",
    "value": "Publication of other teaching material  writing or publication all Kurslitteratur bokprojekt kompendium  "
  },
  {
    "title": "Publication in non peer-reviewed journals",
    "value": "Publication in non peer-reviewed journals  writing or publication all  "
  },
  {
    "title": "NGO (non-governmental organization) reports",
    "value": "NGO (non-governmental organization) reports  writing or publication all  "
  },
  {
    "title": "Approved research grant application",
    "value": "Approved research grant application  grant or award all  "
  },
  {
    "title": "Competitive research awards / prizes",
    "value": "Competitive research awards / prizes  grant or award all  "
  },
  {
    "title": "Competitive teaching awards",
    "value": "Competitive teaching awards  grant or award all  "
  },
  {
    "title": "Faculty opponent on dissertation",
    "value": "Faculty opponent on dissertation  dissertation all Uppsats avhandling  "
  },
  {
    "title": "Member of dissertation grading committee",
    "value": "Member of dissertation grading committee  dissertation all uppsats Betygskommité avhandling  "
  },
  {
    "title": "Academic meeting presentations (Conference, Networks, Seminars, Workshops)",
    "value": "Academic meeting presentations (Conference, Networks, Seminars, Workshops)  presentation all Konferens Nätverks Seminarium  "
  },
  {
    "title": "Editorship with academic journal",
    "value": "Editorship with academic journal  Editorial all  "
  },
  {
    "title": "Service on editorial boards or committees",
    "value": "Service on editorial boards or committees  Editorial all Reviewer  "
  },
  {
    "title": "Editorship of professional or other business/management publication",
    "value": "Editorship of professional or other business/management publication  Editorial all  "
  },
  {
    "title": "Academic meeting proceedings",
    "value": "Academic meeting proceedings  Meeting all  "
  },
  {
    "title": "Public inquires",
    "value": "Public inquires  Service all  "
  },
  {
    "title": "PRJ (not on ABS or Noweigan List)",
    "value": "PRJ (not on ABS or Noweigan List)  all  "
  },
  {
    "title": "KAU University Press Reports",
    "value": "KAU University Press Reports  Publication all  "
  },
  {
    "title": "Validation of scholarly academic status through leadership positions, participation in recognized academic societies and associations, academic fellow status, invited presentations (national / international), peer-review assignments, etc.",
    "value": "Validation of scholarly academic status through leadership positions, participation in recognized academic societies and associations, academic fellow status, invited presentations (national / international), peer-review assignments, etc.  all Föredrag förening inbjuden gäst  "
  },
  {
    "title": "PhD courses in the discipline",
    "value": "PhD courses in the discipline  Publication all  "
  },
  {
    "title": "Service in development projects for collaborations with organizations and other universities",
    "value": "Service in development projects for collaborations with organizations and other universities  Service all samverkan  "
  },
  {
    "title": "Service on boards for teaching, research, and academic development",
    "value": "Service on boards for teaching, research, and academic development  Service all  "
  },
  {
    "title": "Substantive roles and participation in academic development projects",
    "value": "Substantive roles and participation in academic development projects  Service all Uppdrag  "
  },
  {
    "title": "Consulting activities that are material in terms of time and substance",
    "value": "Consulting activities that are material in terms of time and substance  Service all Konsult  "
  },
  {
    "title": "Faculty internships",
    "value": "Faculty internships  Service all  "
  },
  {
    "title": "Development and presentation of executive education programs",
    "value": "Development and presentation of executive education programs  Service all Utveckling  "
  },
  {
    "title": "Sustained professional work supporting qualified status",
    "value": "Sustained professional work supporting qualified status  Service all  "
  },
  {
    "title": "Significant participation in business professional associations",
    "value": "Significant participation in business professional associations  Service all  "
  },
  {
    "title": "Professional meeting proceedings",
    "value": "Professional meeting proceedings  Service all  "
  },
  {
    "title": "Professional meeting presentations",
    "value": "Professional meeting presentations  Service all  "
  },
  {
    "title": "Relevant, active service on boards of directors",
    "value": "Relevant, active service on boards of directors  Service all Ordföramde Styrelse Kommite  "
  },
  {
    "title": "Documented continuing professional education experiences",
    "value": "Documented continuing professional education experiences  Education all  "
  },
  {
    "title": "Participation in professional events that focus on the practice of business, management, and related issues",
    "value": "Participation in professional events that focus on the practice of business, management, and related issues  Education all  "
  },
  {
    "title": "Participation in other activities that place faculty in direct contact with business or other organizational leaders",
    "value": "Participation in other activities that place faculty in direct contact with business or other organizational leaders  Education all  "
  },
  {
    "title": "Visiting & Guest Professor",
    "value": "Visiting & Guest Professor  Teaching all  "
  },
  {
    "title": "Media (TV, Radio, Newspaper Articles & Presentations)",
    "value": "Media (TV, Radio, Newspaper Articles & Presentations)  Teaching all Tidning Artikla  "
  },
  {
    "title": "Development of new courses / programmes",
    "value": "Development of new courses / programmes  Teaching all  "
  },
  {
    "title": "Teacher Exchange",
    "value": "Teacher Exchange  Teaching all  "
  },
  {
    "title": "Organising Conference, Networks, Seminars, Workshops",
    "value": "Organising Conference, Networks, Seminars, Workshops  Teaching all  "
  },
  {
    "title": "Teaching, Leading, Presenting at Summerschools",
    "value": "Teaching, Leading, Presenting at Summerschools  Teaching all  "
  }
]
    id.autocomplete({
      minLength: 0,
      source: projects,
      focus: function( event, ui ) {
        id.val( ui.item.title );
        return true;
      },
      select: function( event, ui ) {
        jQuery( "#project" ).val( ui.item.title );
        jQuery( "#project-id" ).val( ui.item.value ); 
        return false;
      }
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
      return jQuery( "<li>" )
        .append( "<div>" + item.title + "</div>" )
        .appendTo( ul );
    };
  });
         

  }