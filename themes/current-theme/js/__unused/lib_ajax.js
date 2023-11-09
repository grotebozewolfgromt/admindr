/* 
 * Ajax library
 * 
 * 13 aug 2014: lib aangemaakt
 * 13 aug 2014: loadPageInDiv() toegevoegd
 * 
 */


/**
* load html page into tabsheet
* 
* @param {string} sUrl url to load in the div
* @param {string} sDivIDWithoutHashChar
* @returns null
*/
function loadPageInDiv(sUrl, sDivIDWithoutHashChar)
{
     $.get(
           sUrl,
           function(newUpdates) {
                 $("#"+sDivIDWithoutHashChar).html(newUpdates);  // could use prepend to add them at the beginning
           }
     );
}  

