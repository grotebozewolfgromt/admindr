/**
 * 8 mei 2015: lib_misc.js created
 * 14 mei 2015: lib_misc.js addVariableToURLMultipleValues() toegevoegd
 * 
 */

/**
 * preloads an image
 * (may come handy for the loading message dialog) 
 * 
 * @param sImagePath
 */
function preloadImage(sImagePath)
{
	var objImg = new Image();
	objImg.src = sImagePath;
}


/**
 * to prevent the enter-key being hit and form beeing submitted or a search field automatically refreshing a page
 * place function call in the onkeydown event of a text box
 * 
 * example:
 * <input type="search" onkeydown="preventEnter(event)">
 * 
 * @param event
 * @returns Boolean
 */
function preventEnter(event)
{
	if (event.keyCode == 13) //if enter-key is hit
		event.preventDefault();
	
	return false;
}


/**
 * add a variable to a url
 * this function prevents adding one variable twice and takes into account that there isnt a question mark in the url
 * equivalent to php function addVariableToURL() from lib_inet.php
 * 
 * function escapes url unfriendly values
 * 
 * @param sURL string
 * @return string
 */
function addVariableToURL(sURL, sVariable, sValue)
{
	return addVariableToURLMultipleValues(sURL, sVariable, sValue, false, ',');
}

/**
 * add multiple values in variable of an url
 * @param sURL
 * @param sVariable
 * @param sValue
 * @param bAddMultipleValuesToOneVariable
 * @param sValueSeparator
 * @returns {String}
 */
function addVariableToURLMultipleValues(sURL, sVariable, sValue, bAddMultipleValuesToOneVariable, sValueSeparator)
{
    iPosQuestionMark = sURL.indexOf('?');
    
    if (iPosQuestionMark) //if question mark is present
    	{    	
    		var bVariableExists = false;
                
        //preventing that you can't add the same variable twice, so we have to parse the url a little
        var sURLPreQuestionMark  = sURL.substring(0, iPosQuestionMark);// substr($sURL, 0, $iPosQuestionMark);
        var sURLPostQuestionMark = sURL.substring(iPosQuestionMark+1)//substr($sURL, $iPosQuestionMark+1);
        var sNewVariablesSection = '';
        var arrURLVars = sURLPostQuestionMark.split('&');
        var bFirstTimeLoop = true;
        var bVariableExists = false;
        var arrVarValue = null;
        var sVarValue = '';
        var iCountArrUrlVars = arrURLVars.length;
        
        
        for (var iTeller = 0; iTeller < iCountArrUrlVars; iTeller++)//looping al variables in url
        {
        	sVarValue = arrURLVars[iTeller];       		

        	arrVarValue = sVarValue.split('=');
            if (arrVarValue[0] == escape(sVariable)) //if the variable already exists in url then replace it(the first index of the array is the variable, the second the value)
            {
                bVariableExists = true;
                
                if (bAddMultipleValuesToOneVariable == true)
                	arrVarValue[1] += sValueSeparator + escape(sValue);
                else
                	arrVarValue[1] = escape(sValue);  //replace value (second index)                
            }
            
            if (bFirstTimeLoop)
                sNewVariablesSection += arrVarValue[0] + '=' + arrVarValue[1];
            else
                sNewVariablesSection += '&' + arrVarValue[0] + '=' + arrVarValue[1];
            
            bFirstTimeLoop = false;
        }        
        
        if (!bVariableExists) //if not exists, then add
            sNewVariablesSection += '&' + escape(sVariable) + '=' + escape(sValue);
        
        sURL = sURLPreQuestionMark + '?' + sNewVariablesSection;        
        
        
        
        
        
        
        
    	
    		//look if variable is already present
//    		var arr
//    		//if variable not yes present, add it to the others
//    		sURL +='&'+sVariable+'='+sValue;
    	}
    else //no question mark, add it
    	{
    		sURL += '?'+escape(sVariable)+'='+escape(sValue);
    	}
    
    return sURL;	
}

/**
 * return an url of values of the checked checkboxes (with name sCheckboxName)
 * 
 * finds all selected checkboxes with name sName and adds them to sURL, wich is returned
 * 
 * @param sCheckboxName string
 * @param sVariableURL variable in url
 * @param sValueSeparator sepator sign i.e. komma (,)
 * @returns string url
 * */
function addSelectedCheckboxesToURL(sCheckboxName, sVariableURL, sURL, sValueSeparator)
{
	var arrIDElements = document.getElementsByName(sCheckboxName);
	var iCountIDs = arrIDElements.length;

	for (var iIndex = 0; iIndex < iCountIDs; ++iIndex) 
	{			
		if (arrIDElements[iIndex].checked == true)
			sURL = addVariableToURLMultipleValues(sURL, sVariableURL, arrIDElements[iIndex].value, true,  sValueSeparator);
	}
	
	return sURL
}



/**
 * returns the number of checkboxes (with name sCheckboxName) that are checked
 * 
 * @param sCheckboxName
 * @returns boolean
 */
function countCheckboxesChecked(sCheckboxName)
{
	var arrIDElements = document.getElementsByName(sCheckboxName);
	var iCountIDs = arrIDElements.length;
	var iCount = 0;
	
	for (var iIndex = 0; iIndex < iCountIDs; ++iIndex) 
	{	
		if (arrIDElements[iIndex].checked == true)
			++iCount;
	}
	
	return iCount;	
}


/**
 * get text of selected element of an option box 
 * @param string sElementId
 * @returns
 */
function getOptionSelectedText(sElementId) 
{
    var elt = document.getElementById(sElementId);

    if (elt != null)
    	{
	    if (elt.selectedIndex == -1)
	        return null;
	
	    return elt.options[elt.selectedIndex].text;
    	}
    return null;
}

/**
 * return the value of the selected element in an option box
 * 
 * @param string sElementId
 * @returns
 */
function getOptionSelectedValue(sElementId) 
{
    var elt = document.getElementById(sElementId);

    if (elt != null)
    	{
	    if (elt.selectedIndex == -1)
	        return null;
	
	    return elt.options[elt.selectedIndex].value;
    	}
    return null;
}
