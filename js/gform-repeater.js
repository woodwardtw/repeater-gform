

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
            fixCheckboxId();
            hideFields();
            presoShow();
            impactShow();

        }
    }
};

// Create an observer instance linked to the callback function
const observer = new MutationObserver(callback);

// Start observing the target node for configured mutations
if(!document.getElementById('all-data')){
  observer.observe(targetNode, config);
}

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
      "2,022",
      "2,023",
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
  {"label": "Opponent för avhandling / thesis", "description": "(Master / Licentiate / PhD / Doctoral thesis, when staff are the external opponents, either for other departments in Kau or other Universities. As some international master / doctoral thesis are examined differently, use this criterion for staff who are External Examiners for Doctoral students / viva’s etc. globally."}, 
  {"label": "Betygskommité / Grading Committee", "description": "(PhD / Doctoral thesis, for the staff who decide the grades for students’ thesis)"}, 
  {"label": "Academic meeting presentation", "description": "(Konferens, Nätverks, Seminarium / Oral Presentations at conferences / workshops, network meetings etc. – where staff have applied / volunteered to speak themselves (rather than be invited))"}, 
  {"label": "Competitive research award / prize", "description": "(E.g. Best paper in journals or conference / other prizes or award opportunities to be included here)"}, 
  {"label": "Editorship of academic journal or Professional Business / Management Publication", "description": "(Faculty who are editors of journals)"}, 
  {"label": "Service on editorial board or committee", "description": "(Faculty who review journal articles, papers for conferences)"}, 
  {"label": "Public inquiry", "description": "(Faculty who have participated in public enquiries)"}, 
  {"label": "NGO (non-governmental organization) report", "description": ""}, 
  {"label": "Validation of scholarly academic status:", "description": "(Through leadership positions, participation in recognized academic societies and associations, academic fellow status, invited presentations (national / international), peer-review assignments, Föredrag, förening, inbjuden gäst etc.)"}, {"label": "Competitive teaching award", "description": "(KAU merited teacher award or equivalent from other institutions)"}, {"label": "Service in development project for collaborations with organizations and other universities", "description": "(Samverkan / Faculty who have played a significant role in establishing partners, collaborations with other Universities, such as exchange partners for students, staff or with organizations / companies to support student activities or engagement between students, staff and industry)"}, {"label": "Service on a board for teaching, research, and academic development", "description": "(Faculty who sit on University, Faculty and School level Committees / Boards)"}, 
  {"label": "Substantive role and participation in academic development project", "description": "(Uppdrag, E.g. Faculty who play a significant role in AACSB, Utveckling av nya kurser/program)"}, 
  {"label": "Consulting activity that are material in terms of time and substance", "description": "(Konsult, Projektledare / Faculty who have consulting activities)"}, 
  {"label": "Faculty internship", "description": "(Faculty who have participated in work, sabbaticals, internships within industry, during the academic year)"}, 
  {"label": "Development and presentation of executive education program or course", "description": "(Commissioned education, activities via Uppdrags AB)"}, 
  {"label": "Significant participation in business professional association", "description": "(Active membership, activities in industry based associations, such as Charted Institute of Personnel and Development, Chartered Institute of Management Accountants etc.)"}, 
  {"label": "Professional meeting presentation", "description": "(Faculty who have either been invited to or applied / volunteered to present at professional meetings with, guest lectures, training for industry or networks or workshops connected to industry, Konferens, Nätverks, Seminarium)"}, 
  {"label": "Active service on a board of directors", "description": "(Ordföramde, medlem i Styrelse, Kommite, jury)"}, 
  {"label": "Participation in professional event that focuses on the practice of business, management, and related issues or other activity that place faculty in direct contact with business or other organizational leaders", "description": ""}, 
  {"label": "Visiting, Guest, Honorary Professor in another institution", "description": "(Nationally / Internationally)"}, 
  {"label": "Organising Conference, Network, Seminar, Workshop, Arrangera conferens/nätverksträff/seminarium/workshop", "description": "WebForm Text"}, 
  {"label": "Participation in courses", "description": ""},
  {"label": "Hosted international academics or industrial participants for research of teaching", "description":""}
];


jQuery('[data-toggle="tooltip"]').tooltip(); //tooltip


//RECORDED or NOT
jQuery(document).ready(function() {
 
    jQuery(".recorded").change(function(){
 // Ajax call
      console.log(this)
      const post_id = this.dataset.post_id
      const row = this.dataset.row
      const state = this.dataset.checked
      if (state == 'yes'){
        var status = 'no'
      } else {
        var status = 'yes'
      }

        jQuery.ajax({
            type: "post",
            url: ajax_var.url,
            data: "action=update-recorded-status&nonce="+ajax_var.nonce+"&post_id="+post_id+"&row="+row+"&status="+status,            
            success: function(){
               alert('updated')
            }
        });
      })
  })

jQuery(document).ready(function() {
  if(document.getElementById('download')){
    const dlButton = document.getElementById(('download'));
    dlButton.addEventListener('click', dlExcel, false);
  }
 })

function dlExcel(){
  const table = document.getElementById('all-data');
  const name = 'all data';
  const filename = 'professional_dev_data.xls';
  tableToExcel(table, name, filename);

}


function tableToExcel(table, name, filename) {
        let uri = 'data:application/vnd.ms-excel;base64,', 
        template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><title></title><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>', 
        base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) },         format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; })}
        
        if (!table.nodeType) table = document.getElementById(table)
        var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}

        var link = document.createElement('a');
        link.download = filename;
        link.href = uri + base64(format(template, ctx));
        link.click();
}

function presoShow(){
  var nodeCount = document.querySelectorAll('.gfield_repeater_item').length-1;
  let topic = document.querySelector('#input_1_1001-'+nodeCount);
  topic.addEventListener('focusout', (event) => {
    if(topic.value === 'Academic meeting presentation' || topic.value === 'Invited Presentations'){
      fieldIds = ['#input_1_1004-'+nodeCount, '#input_1_1005-'+nodeCount, '#input_1_1006-'+nodeCount];
      fieldIds.forEach(function(id) {
        let fieldTitle = document.querySelector(id);
        fieldTitle.parentNode.parentNode.classList.remove('hide');
        fieldTitle.classList.remove('hide');
      })
    }
    if(topic.value === 'Hosted international academics or industrial participants for research of teaching'){
      fieldIds = ['#input_1_1007-'+nodeCount, '#input_1_1008-'+nodeCount];
      fieldIds.forEach(function(id) {
        let fieldTitle = document.querySelector(id);
        fieldTitle.parentNode.parentNode.classList.remove('hide');
        fieldTitle.classList.remove('hide');
      })
    }
  });
}

function impactShow(){
  let nodeCount = document.querySelectorAll('.gfield_repeater_item').length;
  let checkboxes = document.querySelectorAll('.gfield_checkbox');//needs to get both checkboxes
  let cleanCheck = [];
//get just the first checkbox by not getting li's >1
  checkboxes.forEach(function(box,index){
    if(box.getElementsByTagName("li").length <=1){      
      cleanCheck.push(box);
    }
  })
   cleanCheck.forEach(function(box,index){
      box.parentNode.parentNode.classList.add('society-box')
      box.addEventListener('change', (event) => {
        //input_1_1010-0
        let options = document.getElementById('input_1_1010-'+index);
        console.log(box)
        options.classList.add('society-options');
        if(options.classList.contains('hide')){
          options.classList.remove('hide');
        } else {
          options.classList.add('hide');
        }
      })

    })
}

function hideFields(){
  if (document.querySelectorAll('.gfield_repeater_item') !== null){
    let nodeCount = document.querySelectorAll('.gfield_repeater_item').length-1;
    fixCheckboxId();
    fieldIds = ['#input_1_1004-'+nodeCount, '#input_1_1005-'+nodeCount, '#input_1_1006-'+nodeCount, '#input_1_1007-'+nodeCount, 
    '#input_1_1008-'+nodeCount];
    fieldIds.forEach(function(id) {
      let fieldTitle = document.querySelector(id);
      fieldTitle.parentNode.parentNode.classList.add('hide');
      fieldTitle.classList.add('hide');
    })
  }
  
}

function fixCheckboxId(){
  let nodeCount = document.querySelectorAll('.gfield_repeater_item').length;
  let checkboxes = document.querySelectorAll('.gfield_checkbox');//needs to only get second checkbox item!!!!!
  let specialBoxes = [];
  // console.log('cb length = ' +checkboxes.length)
  checkboxes.forEach(function(box,index){
    if(box.getElementsByTagName("li").length>1){      
      box.classList.add('hide');
      specialBoxes.push(box)
      //box.id = 'input_1_1010-'+((checkboxes.length/nodeCount));
    }
    specialBoxes.forEach(function(box, index){
      box.id = 'input_1_1010-'+index;
    })
    //box.parentNode.parentNode.classList.add('hide');
  })
}

fixCheckboxId();

//IF NEW FORM THEN TRASH ALL THIS
//hideFields();
// presoShow();
// impactShow();
swede_highlight_unchecked();

function swede_highlight_unchecked(){
  if (document.querySelector('#all-data')){
    const table = document.querySelector('#all-data');
    for (let row of table.rows) 
      {
         console.log(row.getElementsByTagName("td")[5])
      }
  }
}