jQuery( document ).on( 'click', '.delete', function() {

    var rownumjs = jQuery(this).data("row");
    console.log('row num');
    console.log(rownumjs);
    var id = jQuery(this).data("id");
    console.log('post id');
    console.log(id);

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
                //hideDeletedRow(id);
                window.location = window.location.href;
            }
    );

});

function hideDeletedRow(id){
    console.log('hider?');
    let row = document.getElementById('tenure-row-'+id);
    row.classList.add('hidden')    
}

//watch page
MutationObserver = window.MutationObserver || window.WebKitMutationObserver;

  var observer = new MutationObserver(function(mutations, observer) {
      // fired when a mutation occurs
      console.log(mutations, observer);
  let theInputs = document.querySelectorAll('input');
  let theItems = '';
  items.forEach(function(item){
     theItems = theItems + '<li onclick="chooseIt(this)">' +item.title + '<span class="extras">' + item.description + '</span></li>';
  })

  theInputs.forEach(function(input,index){
    var theId = input.id;
    var mainId = theId.substring(0, 12);
    //REMBER TO CHANGE THIS TO ID 1
    if (mainId === 'input_1_1001' && !document.getElementById('list-'+index)){
      input.spellcheck = false;
      input.insertAdjacentHTML('afterend','<ul class="tenure-list" id="list-'+index+'" data-field="' + theId + '">'+theItems+'</ul>');
      let lists = input.parentNode.querySelectorAll('ul');//deal with gform copying down the list on add item
      lists.forEach(function(list){
        if (list.dataset.field != input.id){
          list.remove()
        }
      })
      input.addEventListener("input", function() {
      showList('list-'+index, theId); 
      filterThings('list-'+index, theId);     
      
    });
    }
  })
  //year field
  jQuery( function() {
    var availableTags = [
      "2016",
      "2017",
      "2018",
      "2019",
      "2020",
    ];
    jQuery( ".ginput_container_number input" ).autocomplete({
      source: availableTags
    });
  } );

});

// define what element should be observed by the observer
// and what types of mutations trigger the callback
observer.observe(document, {
  subtree: true,
  attributes: true
  //...
});


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


function chooseIt(e){
  let newChoice = e.innerText;
  let fieldId = e.parentNode.dataset.field;
  document.getElementById(fieldId).value = newChoice;
  //document.getElementById(fieldId).classList.add('chosen') //problem is this gets copied down 
  let remove = document.getElementById(e.parentNode.id).classList.remove('show')
  console.log(document.getElementById(e.parentNode.id.classList))
  //document.getElementById('the-list').style.height = '0px'
  //document.getElementById('the-list').style.opacity = '0'
  let stringLeng = fieldId.length;
  let theItem = fieldId.substring(13,stringLeng); //input_1_1001-0
  document.getElementById('input_1_1002-'+theItem).focus(); //change focus to next element
}


function filterThings(listId, searchId) {
  // Declare variables
  var input, filter, ul, li, i, txtValue;
  input = document.getElementById(searchId);
  filter = input.value.toUpperCase();
  ul = document.getElementById(listId);
  console.log(ul)
  li = ul.getElementsByTagName('li');
  console.log(li)
  // Loop through all list items, and hide those who don't match the search query
  for (i = 0; i < li.length; i++) {
     console.log(li[i].innerHTML);
    txtValue = li[i].innerHTML;
    if (txtValue.toUpperCase().indexOf(filter) > -1) {
      li[i].style.display = "";
    } else {
      li[i].style.display = "none";
    }
  }
}


function setUpFilters(){
  let theInputs = document.querySelectorAll('input');
  let theItems = '';
  items.forEach(function(item){
     theItems = theItems + '<li onclick="chooseIt(this)">' +item.title + '<span class="extras">' + item.description + '</span></li>';
  })

  theInputs.forEach(function(input,index){
    var theId = input.id;
    var mainId = theId.substring(0, 12);
    //REMBER TO CHANGE THIS TO ID 1
    if (mainId === 'input_1_1001' && !document.getElementById('list-'+index)){
      input.spellcheck = false;
      input.insertAdjacentHTML('afterend','<ul class="tenure-list" id="list-'+index+'" data-field="' + theId + '">'+theItems+'</ul>');
      let lists = input.parentNode.querySelectorAll('ul');//deal with gform copying down the list on add item
      lists.forEach(function(list){
        if (list.dataset.field != input.id){
          list.remove()
        }
      })
      input.addEventListener("input", function() {
      showList('list-'+index, theId); 
      filterThings('list-'+index, theId);     
      
    });
    }
  })
}

jQuery( document ).ready(function() {
  console.log('loaded');
  setUpFilters();
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


const items = [
  {
    title: 'An authored or edited volume published by an academic book publisher',
    description: 'writing or publication all'
  },
  {
    title: 'An article in an edited volume published by an academic book publisher',
    description: 'writing or publication all'
  },
  {
    title: 'An article in a peer-reviewed conference proceeding',
    description: 'writing or publication all'
  },
   {
    title: 'Peer-reviewed working papers',
    description: 'writing or publication all'
  },
  {
    title: 'Publication or revision of textbooks',
    description: 'writing or publication all'
  },
   {
    title: 'Publication of cases',
    description: 'writing or publication all Utveckling skarpa'
  },
   {
    title: 'Publication of other teaching material',
    description: 'writing or publication all Kurslitteratur bokprojekt kompendium'
  },
   {
    title: 'Publication in non peer-reviewed journals',
    description: 'writing or publication all'
  },
  {
    title: 'NGO (non-governmental organization) reports',
    description: 'writing or publication all'
  },
  {
    title: 'Approved research grant application',
    description: 'grant or award all'
  },
  {
    title: 'Competitive research awards / prizes',
    description: 'grant or award all'
  },
  {
    title: 'Competitive teaching awards',
    description: 'grant or award all'
  },
  {
    title: 'Faculty opponent on dissertation',
    description: 'dissertation all Uppsats avhandling'
  },
  {
    title: 'Member of dissertation grading committee',
    description: 'dissertation all uppsats Betygskommité avhandling'
  },
  {
    title: 'Academic meeting presentations (Conference, Networks, Seminars, Workshops)',
    description: 'presentation all Konferens Nätverks Seminarium'
  },
  {
    title: 'Editorship with academic journal',
    description: 'Editorial all'
  },
  {
    title: 'Service on editorial boards or committees',
    description: 'Editorial all Reviewer'
  },
  {
    title: 'Editorship of professional or other business/management publication',
    description: 'Editorial all'
  },
  {
    title: 'Academic meeting proceedings',
    description: 'Meeting all'
  },
  {
    title: 'Public inquires',
    description: 'Service all'
  },
  {
    title: 'PRJ (not on ABS or Noweigan List)',
    description: 'all'
  },
  {
    title: 'KAU University Press Reports',
    description: 'Publication all'
  },
  {
    title: 'Validation of scholarly academic status through leadership positions, participation in recognized academic societies and associations, academic fellow status, invited presentations (national / international), peer-review assignments, etc.',
    description: 'all Föredrag förening inbjuden gäst'
  },
  {
    title: 'PhD courses in the discipline',
    description: 'Publication all'
  },
  {
    title: 'Service in development projects for collaborations with organizations and other universities',
    description: 'Service all samverkan'
  },
  {
    title: 'Service on boards for teaching, research, and academic development',
    description: 'Service all'
  },
  {
    title: 'Substantive roles and participation in academic development projects',
    description: 'Service all Uppdrag'
  },
  {
    title: 'Consulting activities that are material in terms of time and substance',
    description: 'Service all Konsult'
  },
  {
    title: 'Faculty internships',
    description: 'Service all'
  },
  {
    title: 'Development and presentation of executive education programs',
    description: 'Service all Utveckling'
  },
  {
    title: 'Sustained professional work supporting qualified status',
    description: 'Service all'
  },
  {
    title: 'Significant participation in business professional associations',
    description: 'Service all'
  },
  {
    title: 'Professional meeting proceedings',
    description: 'Service all'
  },
  {
    title: 'Professional meeting presentations',
    description: 'Service all'
  },
  {
    title: 'Relevant, active service on boards of directors',
    description: 'Service all Ordföramde Styrelse Kommite'
  },
  {
    title: 'Documented continuing professional education experiences',
    description: 'Education all'
  },
  {
    title: 'Participation in professional events that focus on the practice of business, management, and related issues',
    description: 'Education all'
  },
  {
    title: 'Participation in other activities that place faculty in direct contact with business or other organizational leaders',
    description: 'Education all'
  },
  {
    title: 'Visiting & Guest Professor',
    description: 'Teaching all'
  },
  {
    title: 'Media (TV, Radio, Newspaper Articles & Presentations)',
    description: 'Teaching all Tidning Artikla'
  },
  {
    title: 'Development of new courses / programmes',
    description: 'Teaching all'
  },
  {
    title: 'Teacher Exchange',
    description: 'Teaching all'
  },
  {
    title: 'Organising Conference, Networks, Seminars, Workshops',
    description: 'Teaching all'
  },
  {
    title: 'Teaching, Leading, Presenting at Summerschools',
    description: 'Teaching all'
  }
];


