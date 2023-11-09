/* 
 * javascripts for template tpl_listrecords.js
 * 
 * 7 mei 2015: aangemaakt
 * 
 */


$( document ).ready(
function() 
{

    //====table behaviour
    $("#chkListRecordsCheckAll").change(function () {
        //First the checkbox statuses will be changed according to
        //the status of the master checkbox
        $('input:checkbox').prop('checked', this.checked);
        //Then the change event defined below will be triggered. By
        //doing this, the row color will be changed according to the
        //checkbox status
        $('tr').toggleClass("selectedtablerow", this.checked)
    });

    //This changes the color of a row and the checkbox status
    //whenever the user clicks on the row or the checkbox.
    //In order to exclude the header row, it will be applied only to the
    //tbody tr selector. The 'check_all' check box must be excluded as well
    //in order to not change the color of the header row.
    $('table.listrecords tbody :checkbox').change(function (event) {
        $(this).closest('tr').toggleClass("selectedtablerow", this.checked);
        
        
    });
    $('table.listrecords tbody tr').click(function(event) {
					if (event.target.type !== 'checkbox') {
						$(':checkbox', this).trigger('click');
					}
					$("input[type='checkbox']").not('#check_all').change(function(e) {
						if($(this).is(":checked")){
							$(this).closest('tr').addClass("selected_row");
						}else{
							$(this).closest('tr').removeClass("selected_row");
						}
					});
					//alert( "hoppla" );
				});
                                                                                                
    
    
    //====EINDE table behaviour
    
    
  
    
}
);


function loadMoreTableRows(objSource, sVariable, sValue, sBusyLoadingText, sURLSpinner)
{
	objSource.onclick = function(){return false;} //prevent double clicks while busy
	var sHTMLSpinner = '<img alt="loading" src="' + sURLSpinner + '">';
	
	objSource.innerHTML = sHTMLSpinner + '&nbsp;' + sBusyLoadingText; //laadtext

	//we dont want to replace sURLRefreshRecordsOnly, because the page counter is only valid for 1 page (pagecounter has te be reset with filters, search and sort)
	sURL = addVariableToURL(sURLRefreshRecordsOnly, sVariable, sValue);
	
//alert(sURL);

	$.get( sURL, function(data) //data ophalen
	{
		var objTableBody = $(objSource).closest('tbody');
		$(objSource).closest('tr').remove();
		$(data).appendTo(objTableBody);
	}, 'text' );  		
	    			
}

/**
 * 
 * @param objSource source object
 * @param objEvent event object of the source
 * @param sTextLoading text to display when loading
 * @param sURLSpinner url of the spinner image
 * @param sVariablePagePaginator we have to reset the paginator page (otherwise we get the wrong page)
 */
function loadSimpleSearch(objSource, objEvent, sTextLoading, sURLSpinner, sVariablePagePaginator)
{
	objSource.onkeypress = function(){ if (objEvent.keyCode == 13) return false;} //prevent extra keypresses

	
	if (objEvent.keyCode == 13) //if enter-key is hit
	{				
		//retrieve search keywords and add it to url
		sURLRefreshRecordsOnly = addVariableToURLMultipleValues(sURLRefreshRecordsOnly, $(objSource).prop('name'), objSource.value, false, ',');
		sURLRefreshRecordsOnly = addVariableToURLMultipleValues(sURLRefreshRecordsOnly, sVariablePagePaginator, '', false, ','); //reset paginator
		
		showBusyDlg(sTextLoading,sURLSpinner);		
		
		//data ophalen
		$.get( sURLRefreshRecordsOnly, function(objData) 
		{			
			hideDlg();
						
			//show results
			$(".listrecords tbody").empty();
			$(objData).appendTo(".listrecords tbody");
		}, 'text' ); 			
	}
	
}

/**
 * loading the advanced search (with more search conditions)
 * @param objSource
 * @param objEvent
 * @param sURLSpinner
 */
function loadAdvancedSearch(objSource, objEvent, sTextLoading, sURLSpinner, sVariablePagePaginator)
{
	objSource.onkeypress = function(){ if (objEvent.keyCode == 13) return false;} //prevent extra keypresses
	
	if (objEvent.keyCode == 13) //if enter-key is hit
	{
		sURLRefreshRecordsOnly = addVariableToURLMultipleValues(sURLRefreshRecordsOnly, sVariablePagePaginator, '', false, ','); //reset paginator		
		
		//retrieve search keywords and add it to url
		var objDivAdvancedSearch = objSource.parentNode.parentNode;
		
		//zoekurl resetten op nieuwe zoekopdrachten
		var bFirstTimeLoopReset = true;
		
		$(objDivAdvancedSearch).children().each(function(iIndexQueryLines) //elke queryline-div wordt nagelopen
		{ 
			var objQueryLine = this; //'this' is de div van de queryline
			
			//elk element in de queryline div wordt nagelopen
			$(objQueryLine).children().each(function(iIndexQueryLines)
			{
				var objQuerlineElement = this; //'this' is het element binnen de div van de queryline
							
				
				//eerste select is het database veld
				if (iIndexQueryLines == 0)
					sURLRefreshRecordsOnly = addVariableToURLMultipleValues(sURLRefreshRecordsOnly, $(objQuerlineElement).prop('name'), objQuerlineElement.value, !bFirstTimeLoopReset, ',');
				
				//tweede select is de operator
				if (iIndexQueryLines == 1)
					sURLRefreshRecordsOnly = addVariableToURLMultipleValues(sURLRefreshRecordsOnly, $(objQuerlineElement).prop('name'), objQuerlineElement.value, !bFirstTimeLoopReset, ',');

				//derde is textbox met daarin zoekwoord
				if (iIndexQueryLines == 2)
					sURLRefreshRecordsOnly = addVariableToURLMultipleValues(sURLRefreshRecordsOnly, $(objQuerlineElement).prop('name'), objQuerlineElement.value, !bFirstTimeLoopReset, ',');

				
			});	
			
			bFirstTimeLoopReset = false;
		})
		

	
	
		showBusyDlg(sTextLoading,sURLSpinner);
		
		//data ophalen
		$.get( sURLRefreshRecordsOnly, function(objData) 
		{			
			hideDlg();
						
			//show results
			$(".listrecords tbody").empty();
			$(objData).appendTo(".listrecords tbody");
		}, 'text' ); 			
	}	
}

/**
 * loading sort
 * 
 * @param sVariableColumnIndex
 * @param iValueColumnIndex
 * @param sVariableSortOrder
 * @param sValueSortOrder
 * @returns next sorting order
 */
function loadSortColumn(sVariableColumnIndex, iValueColumnIndex, sVariableSortOrder, sValueSortOrder, sBusyLoadingText, sPathSpinner, sVariablePagePaginator)
{
	var sNewSortOrder = getNextSortOrder(sValueSortOrder);
	
	sURLRefreshRecordsOnly = addVariableToURLMultipleValues(sURLRefreshRecordsOnly, sVariableColumnIndex, iValueColumnIndex, false, ','); //-->false omdat het stapelen een extra probleem geeft dat de huidige kolom meerdere keren gestapeld kan worden
	sURLRefreshRecordsOnly = addVariableToURLMultipleValues(sURLRefreshRecordsOnly, sVariableSortOrder, sNewSortOrder, false, ','); //-->false omdat het stapelen een extra probleem geeft dat de huidige kolom meerdere keren gestapeld kan worden
	sURLRefreshRecordsOnly = addVariableToURLMultipleValues(sURLRefreshRecordsOnly, sVariablePagePaginator, '', false, ','); //reset paginator
	
	showBusyDlg(sBusyLoadingText,sPathSpinner);
	
	
	//data ophalen
	$.get( sURLRefreshRecordsOnly, function(objData) 
	{			
		hideDlg();
		
		//show results
		$(".listrecords tbody").empty();
		$(objData).appendTo(".listrecords tbody");
	}, 'text' ); 	
	
	return sNewSortOrder;
}

/**
 * retrieve next sortorder
 * @param {string} sCurrentSortOrder if empty no-sort-order is assumed
 * @returns {String}
 */
function getNextSortOrder(sCurrentSortOrder)
{
	if (sCurrentSortOrder == "ASC")
		return "DESC";
	
	if (sCurrentSortOrder == "DESC")
		return "";
	else
		return "ASC";
}


/**
 * loadChangeOrderOne
 * 
 * change order of one record
 * that order is one record up or one record down
 * (what is up and what is down is determined by the sorting order ASC or DESC)
 * 
 * @param sVariableID
 * @param sValueID
 * @param sVariableSortOrder
 * @param sValueSortOrder
 * @param sVariableUpOrDown
 * @param sValueUpOrDown
 * @param sBusyLoadingText
 * @param sPathSpinner
 * @param sVariablePagePaginator
 */
function loadChangeOrderOne(sVariableID, sValueID, sVariableSortOrder, sValueSortOrder, sVariableUpOrDown, sValueUpOrDown, sBusyLoadingText, sPathSpinner)
{	
	sURLRefreshRecordsOnly = addVariableToURLMultipleValues(sURLRefreshRecordsOnly, sVariableID, sValueID, false, ','); 
	sURLRefreshRecordsOnly = addVariableToURLMultipleValues(sURLRefreshRecordsOnly, sVariableSortOrder, sValueSortOrder, false, ',');
	sURLRefreshRecordsOnly = addVariableToURLMultipleValues(sURLRefreshRecordsOnly, sVariableUpOrDown, sValueUpOrDown, false, ',');	
	
	showBusyDlg(sBusyLoadingText,sPathSpinner);
	
	//data ophalen
	$.get( sURLRefreshRecordsOnly, function(objData) 
	{			
		hideDlg();
		
		//show results
		$(".listrecords tbody").empty();
		$(objData).appendTo(".listrecords tbody");
	}, 'text' ); 	
	
}


/**
 * loading filters
 * @param sVariable
 * @param sValue
 * @param sTextLoading
 * @param sURLSpinner
 */
function loadFilter(sVariable, sValue, sTextLoading,sURLSpinner, sVariablePagePaginator)
{
	//retrieve search keywords and add it to url
	sURLRefreshRecordsOnly = addVariableToURLMultipleValues(sURLRefreshRecordsOnly, sVariable, sValue, false, ',');
	sURLRefreshRecordsOnly = addVariableToURLMultipleValues(sURLRefreshRecordsOnly, sVariablePagePaginator, '', false, ','); //reset paginator
	
//alert(sURLRefreshRecordsOnly);
	
	showBusyDlg(sTextLoading,sURLSpinner);		
	
	//data ophalen
	$.get( sURLRefreshRecordsOnly, function(objData) 
	{			
		hideDlg();
					
		//show results
		$(".listrecords tbody").empty();
		$(objData).appendTo(".listrecords tbody");
	}, 'text' ); 				
}


