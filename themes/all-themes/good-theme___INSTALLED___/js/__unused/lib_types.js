/* 
 * libvrary for string functions
 * 
 * 13 aug 2014: lib aangemaakt
 * 13 aug 2014: removeFirstChar() toegevoegd
 * 
 */


/**
 * remove the first character from a string 
 * 
 * @param {string} sString
 * @returns {string}
 */
function removeFirstChar(sString)
{
    return sString.slice( 1 );
} 


/**
 * convert boolean into integer
 * @param bValue boolean
 */
function boolToInt(bValue)
{
	if (bValue)
		return 1;
	else
		return 0;
}

/**
 * convert integer to boolean
 * @param iValue integer
 * @returns {Boolean}
 */
function intToBool(iValue)
{
	if (iValue == 0)
		return false;
	else
		return true;
}
