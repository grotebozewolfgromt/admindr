/* 
 * dialogs library
 * 
 * 10 apr 2015: dialogs.js aangemaakt
 *  
 */


//	$( document ).ready(function() 
//	{
////		$("body").append('<div class="messagedlg_busy" style="visibility:visible">' + 'lajen...' + '<img src="' + 'http://raspberrypi.home/cms3/ftproot/subdomains/cms/images/spinner.gif' + '"></div>');
////		$(".messagedlg_busy").width(150);
////		$(".messagedlg_busy").height(150);
////		alert('test');
//		
//		showBusyDlg('test', 'http://raspberrypi.home/cms3/ftproot/subdomains/cms/images/spinner.gif');
//	}
//	);
//	
//	function tempBusy()
//	{
//		$(".messagedlg_content").css({"visibility":"visible"}); 
//	}



	/**
	* show a dialog that the system is busy.
	* DONT FORGET TO PRELOAD THE IMAGE!!!!
	*
	* sBusyText string - the text to display in the dialog (language dependent). sBusyText can be '' than a default value will assumed
	* sBusyImagePath string - the path if the image to display in de dialog to indicate that the system is busy
	*/
	function showBusyDlg(sBusyText, sBusyImagePath) 
	{
//		alert('ik kom hier');
		if (sBusyText == '')
			sBusyText = 'Loading ...';
	
		$("body").css({"overflow-y":"hidden"});	//prevent background from scrolling
    	
    		//lagen toevoegen
		$("body").append('<div class="messagedlg_backgroundoverlay"></div>');
		$("body").append('<div class="messagedlg_content">' + sBusyText + '<img src="' + sBusyImagePath + '"></div>');
		
		$(".messagedlg_content img").load();
		
		//grootte dialoogvenster setten
		$(".messagedlg_content").width(150);
		$(".messagedlg_content").height(150);
		$(".messagedlg_content").css({"overflow":"hidden"}); //teveel tekst valt eraf
		
		//lagen tonen met animatie
		$(".messagedlg_content").animate({"opacity":"1"}, 100, "linear");
		$(".messagedlg_backgroundoverlay").animate({"opacity":"0.8"}, 100, "linear");		
			
		/* tijdelijk om de dialoog te kunnen afsluiten */		
		$(".messagedlg_content").click(function(){hideDlg();}); 
   	}
   	  
	
   	/**
   	* show message dialog with a proper ok button
   	*
   	* sTitle string - text to display in the upper part of the dialog
   	* sMessage string - text displayed in dialog
   	* sTextOKButton string
   	* sCSSClass for makeup of the button
   	**/	
	function showMessageDlgOk(sTitle, sMessage, sTextOKButton, sCSSClass)
	{
		objOKButton = $('<input type="button" value="'+sTextOKButton+'" class="'+sCSSClass+'">');
		objOKButton.click(function(){hideDlg();}); //close dialog when clicking the button   		  		
		
		showMessageDlg(sTitle, sMessage, [objOKButton]);
	}
	
   	/**
   	* show message dialog
   	*
   	* sTitle string - text to display in the upper part of the dialog
   	* sMessage string - text displayed in dialog
   	* arrButtons array 1d - jquery button objects (can be null, it will add a basic OK button) --> wanna nice button? use showMessageDlgOk()
   	**/
   	function showMessageDlg(sTitle, sMessage, arrButtons)
   	{
   		//add OK button if arrButtons is null
		if (arrButtons == null)
		{
			objOKButton = $('<input type="button" value="ok">');
			objOKButton.click(function(){hideDlg();}); //close dialog when clicking the button
			var arrButtons = [objOKButton];   		  		
		}
   	
		$("body").css({"overflow-y":"hidden"});	//prevent background from scrolling
    	
    	//lagen toevoegen
		$("body").append('<div class="messagedlg_backgroundoverlay"></div>');
		$("body").append('<div class="messagedlg_content"></div>');
		
		//grootte dialoogvenster setten
		$(".messagedlg_content").width('50%');
		$(".messagedlg_content").height(130);
		$(".messagedlg_content").css({"overflow":"hidden"}); //teveel tekst valt eraf
		
		//title div
		$(".messagedlg_content").append('<div class="messagedlg_content_title"></div>');
		$(".messagedlg_content_title").append(sTitle);
				
		//message div
		$(".messagedlg_content").append('<div class="messagedlg_content_message"></div>');
		$(".messagedlg_content_message").append(sMessage);	
		$(".messagedlg_content_message").height(40);	
		
		//knoppenbalk toevoegen en knoppen
		$(".messagedlg_content").append('<div class="messagedlg_content_commandpanel"></div>');					
		for	(iButtonIndex = 0; iButtonIndex < arrButtons.length; iButtonIndex++) 
		{
			$(".messagedlg_content_commandpanel").append(arrButtons[iButtonIndex]);
		}		
		
		//lagen tonen met animatie
		$(".messagedlg_content").animate({"opacity":"1"}, 100, "linear");
		$(".messagedlg_backgroundoverlay").animate({"opacity":"0.8"}, 100, "linear");		
		

   	}
   	
   	/**
   	* show image gallery i.e. showImageDlg(['sun1.jpg', 'sun2.gif', 'sun3.gif']
   	*
   	* arrImagePaths array 1d - array with paths of images
   	**/
   	function showImageDlg(arrImagePaths)
   	{
		var iImgArrayIndex = 0;
		
		//when next-button clicked
		function showNext()
		{
			iImgArrayIndex++;
			if (iImgArrayIndex > arrImagePaths.length-1)
				iImgArrayIndex = 0;
			
			//hide the old image, call new one
			$(objDivContent.animate({"opacity":"0"}, 200, "linear", function(){
				showNewImg(arrImagePaths[iImgArrayIndex]);
			}));
		}
		
		//when previous-button clicked
		function showPrevious()
		{
			iImgArrayIndex--;
			if (iImgArrayIndex < 0)
				iImgArrayIndex = arrImagePaths.length-1;		
		
			//hide the old image, call new one
			$(objDivContent.animate({"opacity":"0"}, 200, "linear", function(){
				showNewImg(arrImagePaths[iImgArrayIndex]);
			}));
		}
		
		//called to show new image
		function showNewImg(sImagePath)
		{
			//grootte en positie dialoogvenster
			$(objImg.attr("src",sImagePath));
 			$(objImg.load(function() {
				var iImgWidth = objImg.width();
				var iImgHeight = objImg.height();
										
				$(".messagedlg_content")
					.css({
						"top":        "50%",
						"left":        "50%",
						"width":      iImgWidth,
						"height":     iImgHeight,
						"margin-top": -(iImgHeight/2), // the middle position
						"margin-left":-(iImgWidth/2)
						})
	 		}));
	 		
	 		//show the image
	 		$(objDivContent.animate({"opacity":"1"}, 200, "linear"));	 		
	 	}
		
		
		$("body").css({"overflow-y":"hidden"});	//prevent background from scrolling
    	
		//lagen toevoegen
		$("body").append('<div class="messagedlg_backgroundoverlay"></div>');
		var objDivContent = $('<div class="messagedlg_content"></div>'); 
		$("body").append(objDivContent);
		var objImg = $('<img alt="imagegallery"/>');
		$(".messagedlg_content").append(objImg); //image toevoegen
		objImg.click(function(){showNext();}); 

		//knoppen toevoegen
		var objPreviousButton = $('<input type="button" value="&lt;" style="border-radius: 10px;"/>');
		objPreviousButton.click(function(){showPrevious();}); 
		$(".messagedlg_content").append(objPreviousButton);
		var objNextButton = $('<input type="button" value="&gt;" style="border-radius: 10px;"/>');
		objNextButton.click(function(){showNext();}); 
		$(".messagedlg_content").append(objNextButton);

		//starten
		sImagePath = arrImagePaths[iImgArrayIndex];
		showNewImg(sImagePath);
						
		//lagen tonen met animatie
		$(".messagedlg_content").animate({"opacity":"1"}, 100, "linear");
		$(".messagedlg_backgroundoverlay").animate({"opacity":"0.8"}, 100, "linear");					
		
		/* op de achtergrond klikken om de dialoog te kunnen afsluiten */		
		$(".messagedlg_backgroundoverlay").click(function(){hideDlg();}); 		
   	}
   	
   	/**
   	 * are you sure you want to delete X? 
   	 * 
   	 * when yes clicked it will load the url sURLDelete
   	 * when cancel clicked it 
   	 * 
   	 * @param sTitle
   	 * @param sMessage
   	 * @param sButtonTextDelete
   	 * @param sButtonClassYes  css class of button
   	 * @param sButtonTextCancel
   	 * @param sButtonClassCancel  css class of button
   	 * @param sURLToLoadWhenYesClicked when the YES button is clicked, it will do a window.location.href to this url
   	 */
   	function showAreYouSureDlg(sTitle, sMessage, sButtonTextYes, sButtonClassYes, sButtonTextCancel, sButtonClassCancel, sURLToLoadWhenYesClicked)
   	{
   		objDeleteButton = $('<input type="button" value="'+sButtonTextYes+'" class="'+sButtonClassYes+'"/>');
   		objDeleteButton.click(function(){window.location.href=sURLToLoadWhenYesClicked;}); //goto url when clicking the button  		
   		objCancelButton = $('<input type="button" value="'+sButtonTextCancel+'" class="input_type_button"/>');
   		objCancelButton.click(function(){hideDlg();}); //close dialog when clicking the button
   		
   		var arrButtons = [objDeleteButton, objCancelButton];   		  		

   		showMessageDlg(sTitle, sMessage, arrButtons);
   	}   	
   	
   	
   	/**
   	* close the dialog called by show***Dlg()
   	*/
   	function hideDlg()
   	{
		$("body").css({"overflow-y":"auto"}); //turnoff:prevent background from scrolling
		
		//animate and remove objects form html tree
		$(".messagedlg_content").animate({"opacity":"0"}, 100, "linear", function(){$(".messagedlg_content").remove();});
		$(".messagedlg_backgroundoverlay").animate({"opacity":"0"}, 100, "linear", function(){$(".messagedlg_backgroundoverlay").remove();});		
   	}   	