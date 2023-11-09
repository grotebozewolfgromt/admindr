/**
* javascript lib for cms
*
*/

$( document ).ready(
function() 
{
	//menu start invisible
	$( "#menu" ).hide();
	$( "#menubackground" ).hide();
	
	//set correct height content div
	resizeContentDiv();
	
	//on window resize ook div size
	window.onresize = function(event) 
	{            	
		resizeContentDiv();
	}
}
);


/* menuknop: modulemenu zichtbaar/onzichtbaar */
function showMenu() 
{
	$( "#menu" ).fadeIn(300, function() { /* animation complete */ });
	$( "#menubackground" ).fadeIn(300, function() { /* animation complete */ });
	
}	

function hideMenu() 
{
	$( "#menu" ).fadeOut(300, function() { /* animation complete */ });
	$( "#menubackground" ).fadeOut(300, function() { /* animation complete */ });	
}

function toggleMenu()
{
	$( "#menu" ).fadeToggle(300, function() { /* animation complete */ });
	$( "#menubackground" ).fadeToggle(300, function() { /* animation complete */ });
}

function resizeContentDiv() 
{
    	iViewPointWidth = $(window).width();
    	iWidthContent = iViewPointWidth - 20;//-20 vanwege 2x 10px marge
    	iViewPointHeight = $(window).height() -10;//-10 vanwege 10px marge aan de onderkant (bovenkant is header, die heeft aparte magin)
    	iHeightContent = iViewPointHeight - $('#header').height();
    	$('#contentcontainer').css({'width': iWidthContent + 'px'});
    //	$('#contentcontainer').css({'min-height': iHeightContent + 'px'});
	}

