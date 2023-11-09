<!DOCTYPE html>
<html>
<head>
	<title><?php echo $sHTMLTitle; ?></title>
	<meta name="description" content="<?php echo $sHTMLMetaDescription ?>">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="shortcut icon" type="image/png" href="<?php echo GLOBAL_PATH_WWW_CMS_IMAGES ?>/projecticons/icon128.png">
    <meta name="viewport" content="width=800, initial-scale=1">     
    <link rel="stylesheet" href="<?php echo GLOBAL_PATH_WWW_CMS ?>/vendor/cookieconsent/cookieconsent.css" media="print" onload="this.media='all'">
    <script defer src="<?php echo GLOBAL_PATH_WWW_CMS ?>/vendor/cookieconsent/cookieconsent.js"></script>
    <script defer src="<?php echo GLOBAL_PATH_WWW_CMS ?>/vendor/cookieconsent/cookieconsent-init.js"></script>                     
    <?php

        if (GLOBAL_DEVELOPMENTENVIRONMENT) //use external files in development environment, this makes debugging easier
        {
            ?>
                <link href="<?php echo GLOBAL_PATH_WWW_CMS_STYLESHEETS ?>/reset.css" rel="stylesheet" type="text/css">               
                <link href="<?php echo GLOBAL_PATH_WWW_CMS_STYLESHEETS ?>/std.css" rel="stylesheet" type="text/css">               
                <link href="<?php echo GLOBAL_PATH_WWW_CMS_STYLESHEETS ?>/jquery-ui-1.12.1.css" rel="stylesheet" type="text/css">               

                <script src="<?php echo GLOBAL_PATH_WWW_CMS_JSSCRIPTS.'/jquery-3.4.1.min.js'?>"></script> 
                <script src="<?php echo GLOBAL_PATH_WWW_CMS_JSSCRIPTS.'/jquery-ui-1.12.1.min.js'?>"></script>
            <?php 
        }
    ?>
    <style>
        <?php 
            if (!GLOBAL_DEVELOPMENTENVIRONMENT) //include external files in live environment, this makes page loading faster
            {
                // include_once GLOBAL_PATH_LOCAL_CMS_STYLESHEETS.'/reset.css';
                include_once GLOBAL_PATH_LOCAL_CMS_STYLESHEETS.'/std.css';
                include_once GLOBAL_PATH_LOCAL_CMS_STYLESHEETS.'/jquery-ui-1.12.1.css';
            }
        ?>
    </style>          
    <style>
        /* JQuery UI image path correction */
        .ui-icon,
        .ui-widget-content .ui-icon {
                background-image: url("<?php echo GLOBAL_PATH_WWW_CMS_IMAGES ?>/jquery-ui/ui-icons_444444_256x240.png");
        }
        .ui-widget-header .ui-icon {
                background-image: url("<?php echo GLOBAL_PATH_WWW_CMS_IMAGES ?>/jquery-ui/ui-icons_444444_256x240.png");
        }
        .ui-state-hover .ui-icon,
        .ui-state-focus .ui-icon,
        .ui-button:hover .ui-icon,
        .ui-button:focus .ui-icon {
                background-image: url("<?php echo GLOBAL_PATH_WWW_CMS_IMAGES ?>/jquery-ui/ui-icons_555555_256x240.png");
        }
        .ui-state-active .ui-icon,
        .ui-button:active .ui-icon {
                background-image: url("<?php echo GLOBAL_PATH_WWW_CMS_IMAGES ?>/jquery-ui/ui-icons_ffffff_256x240.png");
        }
        .ui-state-highlight .ui-icon,
        .ui-button .ui-state-highlight.ui-icon {
                background-image: url("<?php echo GLOBAL_PATH_WWW_CMS_IMAGES ?>/jquery-ui/ui-icons_777620_256x240.png");
        }
        .ui-state-error .ui-icon,
        .ui-state-error-text .ui-icon {
                background-image: url("<?php echo GLOBAL_PATH_WWW_CMS_IMAGES ?>/jquery-ui/ui-icons_cc0000_256x240.png");
        }
        .ui-button .ui-icon {
                background-image: url("<?php echo GLOBAL_PATH_WWW_CMS_IMAGES ?>/jquery-ui/ui-icons_777777_256x240.png");
        }            
    </style>
    <script>
        <?php 
            if (!GLOBAL_DEVELOPMENTENVIRONMENT) //include external files in live environment, this makes page loading faster
            {
                include_once GLOBAL_PATH_LOCAL_CMS_JSSCRIPTS.'/jquery-3.4.1.min.js';
                include_once GLOBAL_PATH_LOCAL_CMS_JSSCRIPTS.'/jquery-ui-1.12.1.min.js';
            }
        ?>
    </script>            
	<script>            
            
            /**
             * asks if you want to log out
             * 
             * @returns null
             */
            function confirmLogout()
            {
                    var bLogOut;
                    bLogOut = window.confirm("<?php echo transcms('skin_loggedin_message_surelogout','Want to log out of [applicationname]?', 'applicationname', GLOBAL_CMS_APPLICATIONNAME) ?>");
                    if (bLogOut)
                    {
                            location.href = "<?php echo $objLoginController->getURLLogOut(); ?>";
                    }
            }
            
            /**
             * toggle checkboxes on overview form
             * 
             * @param object objSource
             * @param string sCheckboxNames
             * @returns null             
             * */
            function toggleAllCheckboxes(objSource, sCheckboxNames) 
            {
                checkboxes = document.getElementsByName(sCheckboxNames);
                for(var i=0, n=checkboxes.length;i<n;i++)
                {
                    checkboxes[i].checked = objSource.checked;
                }
            }

            /**
             * toggle <TR> rowcolor when checkbox is checked
             * 
             * @param object objSource
             * @returns null             
             * */
            function toggleRowColorCheckboxClick(objSource) 
            {
                if (objSource.checked == true)
                    objSource.parentElement.parentElement.style.backgroundColor = 'var(--theme-color-tablerow-selected)';        
                else
                    objSource.parentElement.parentElement.style.backgroundColor = 'var(--theme-color-tablerow-unselected)';
            }            
        
            /**
             * submit quicksearch form when pressing the X
             * 
             * @param  object objInput
             * @returns null             
             * */
            function onQuickSearch(objInput) 
            {                
                if(objInput.value == "") 
                {
                    document.getElementById('frmQuickSearch').submit();
                }
            }       
            
            /**
             * asks if you want to execute bulk action
             * 
             * @returns null
             */
             
            function confirmBulkAction(sIDOptionBox)
            {
                var bExecute;
                objSelect = document.getElementById(sIDOptionBox);
                objOption = getSelectedOption(objSelect);
                
                bExecute = window.confirm("<?php echo transcms('skin_loggedin_message_sureexecutebulkaction','Want to execute action on selected items?'); ?>\n"+objOption.value);
                if (bExecute)
                {
                     document.getElementById('frmBulkActions').submit();
                }
            }        
            
            
            /**
             * get option of selected html SELECT tag.
             * 
             * @param {type} sel
             * @returns {getSelectedOption.opt}
             */
            
            function getSelectedOption(sel) 
            {
                var opt;
                for ( var i = 0, len = sel.options.length; i < len; i++ ) {
                    opt = sel.options[i];
                    if ( opt.selected === true ) {
                        break;
                    }
                }
                return opt;
            }

            /**
             * copy the contents of an editbox to clipboard
             * 
             * @param string sElementID
             * @returns null
             */
            function copyToClipboardEditBox(sElementID) 
            {
                /* Get the text field */
                var objEditBox = document.getElementById(sElementID);

                if (objEditBox != null)
                {
                    /* Select the text field */
                    objEditBox.select(); 
                    objEditBox.setSelectionRange(0, 99999); /*For mobile devices*/

                    /* Copy the text inside the text field */
                    document.execCommand("copy");

                    /* Alert the copied text */
                    alert("Copied to clipboard:\n" + objEditBox.value);
                }
                else
                    alert("COULDNT FIND TEXTBOX ELEMENT WITH ID: " + sElementID);
            }        
            
            function setCookie(cname, cvalue, exdays) {
              var d = new Date();
              d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
              var expires = "expires="+d.toUTCString();
              document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
            }

            function getCookie(cname) {
              var name = cname + "=";
              var ca = document.cookie.split(';');
              for(var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                  c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                  return c.substring(name.length, c.length);
                }
              }
              return "";
            }      
            
            /**
             * copy the content of a contenteditable html element to <input type="hidden"> so you can submit it in a form
             */
            function copyContentEditableToHidden(sContentEditableID, sHiddenID)
            {
                document.getElementById(sHiddenID).value = document.getElementById(sContentEditableID).innerHTML;
                return true;                
            }

            /**
            * open url in new tab 
            * for example:
            * <div onclick="openInNewTab('www.test.com');">Something To Click On</div>
             */
            function openInNewTab(url) 
            {
                var win = window.open(url, '_blank');
                win.focus();
            }
            
        </script>	
	<meta name="viewport" content="initial-scale=1.0, width=device-width">
	<meta charset="UTF-8">	
</head>
<body>



<div id="page">

	<?php include(GLOBAL_PATH_LOCAL_CMS_TEMPLATES.DIRECTORY_SEPARATOR.'tpl_block_header.php') ?>
				
	<div class="not-header">
	
        <div class="leftcolumn">
                    
                    <ul class="leftcolumn_modules">
                    <?php

                        //display menu with tabsheets from modules
                        $arrKeys = array_keys($arrCats);
                        foreach($arrKeys as $sCatName)
                        {
                            $arrMods = $arrCats[$sCatName];
                            
                            echo '<li>'.$sCatName;
                            echo '<ul>';
                            
                            foreach ($arrMods as $iIndexMod)
                            {
                                $objSysModulesDB->setRecordPointerToIndex($iIndexMod);

                                $sIconPath = GLOBAL_PATH_WWW_CMS_IMAGES.'/icon-module16x16.png';  //default                              
                                if (is_file(getPathModuleImages($objSysModulesDB->getNameInternal()).DIRECTORY_SEPARATOR.'icon-module16x16.png'))
                                    $sIconPath = getURLModuleImages($objSysModulesDB->getNameInternal()).'/icon-module16x16.png';    
                                
                                $sHTMLSelectedMod = '';                                    
                                if ($objSysModulesDB->getNameInternal() == $sCurrentModule)
                                    $sHTMLSelectedMod = ' class="selected"';
                                
                                //only display if module folder exists
                                if(file_exists(GLOBAL_PATH_LOCAL_MODULES.DIRECTORY_SEPARATOR.$objSysModulesDB->getNameInternal()))
                                {                                        
                                    if (auth($objSysModulesDB->getNameInternal(), AUTH_CATEGORY_MODULEACCESS, AUTH_OPERATION_MODULEACCESS))
                                    {
                                        echo '<li'.$sHTMLSelectedMod.'>';
                                        echo '<img src="'.$sIconPath.'">';
                                        echo '<a href="'.getURLModule($objSysModulesDB->getNameInternal()).'/index.php">';
                                        echo transm($objSysModulesDB->getNameInternal(), TRANS_MODULENAME_MENU, $objSysModulesDB->getNameDefault());
                                        // echo $objSysModulesDB->getNameDefault();
                                        echo '</a>';              

                                        //tabsheets of current module
                                        // if ($sMod == $sCurrentModule)
                                        // {
                                        //     $arrTempTabs = $objCurrentModule->getTabsheets();
                                        //     $arrTabKeys = array();
                                        //     $arrTabKeys = array_keys($arrTempTabs);

                                        //     if (count ($arrTempTabs) > 1) //only display if more than one tabsheet (the first one is always the default hook into the cms: index.php of a module)
                                        //     {
                                        //         echo '<ul>';
                                        //         foreach($arrTabKeys as $sTabKey)
                                        //         {
                                        //             echo '<li>';
                                        //             echo '<a href="'.getURLModule($sMod).'/'.$sTabKey.'">';
                                        //             echo transm($sMod, 'cmsmodulelist_modulename_tabsheet_'.$arrTempTabs[$sTabKey],$arrTempTabs[$sTabKey]);
                                        //             echo '</a>';
                                        //             echo '</li>';

                                        //         }
                                        //         echo '</ul>';
                                        //     }
                                        // }

                                        echo '</li>';  
                                    }
                                }
                            }                                
                            
                            echo '</ul>';
                            echo '</li>';
                        }  
                        unset($arrCats);
                    ?>
                    </ul>
                        
                    <?php
                    
                        //CMS2 modules:
                        //categorien weergeven en hun modules
                        for($iTeller=0;$iTeller < count($arrModuleCaths);$iTeller++)
                        {
                                ?>
                                    <br>
                                    <b><?php echo str_replace("_", "&nbsp;", $arrModuleCaths[$iTeller]); //underscores vervangen door spaties?></b><br>
                                <?php
                                //================modules van deze categorie weergeven


                                //array van modules doorlopen en weergeven  --> opbouw array : localpath van script, wwwpath van script, volgnummer, naam van de module, minimaal dit recht hebben om te kunnen gebruiken, category
                                for($iTeller3=0;$iTeller3 < count($arrModules);$iTeller3++)
                                {
                                        $arrCurrModule = $arrModules[$iTeller3];//1 item uit de modulearray pakken

                                        if ($arrCurrModule[5] == $arrModuleCaths[$iTeller]) //goede categorie ?
                                        {
                                            if (($_SESSION['iAdminUserLevel'] <= $arrCurrModule[4]) && (isset($_SESSION['iAdminUserLevel']))) //heb je wel voldoende rechten ?

                                                echo "&nbsp;<a href=\"".$arrCurrModule[1]."?titelsub=".$arrCurrModule[3]."\"><img border=\"0\" src=\"".GLOBAL_PATH_WWW_CMS_IMAGES."/icon-module16x16.png\"></a>&nbsp;<a href=\"".$arrCurrModule[1]."?titelsub=".$arrCurrModule[3]."\">".str_replace(" ", "&nbsp;", $arrCurrModule[3])."</a><br>\n";
                                        }
                                }
                                ?>
                                <!-- EINDE ================= <?php echo $arrModuleCaths[$iTeller];?> ===================================== -->
                                <?php
                        }                        
                    ?>

                                    
                                    
                                    
                    <?php
                        if (auth(AUTH_MODULE_CMS, AUTH_CATEGORY_SYSSITES, AUTH_OPERATION_SYSSITES_VISIBILITY) && GLOBAL_CMS_SHOWWEBSITESINNAVIGATION)
                        {
                            $bAllowedSiteChange = true;
                            $bAllowedSiteChange = auth(AUTH_MODULE_CMS, AUTH_CATEGORY_SYSSITES, AUTH_OPERATION_SYSSITES_SWITCH)
                            ?>
                    
                                <h1><?php echo transcms('skin_loggedin_websitesname','websites') ?></h1>
                                <ul class="leftcolumn_websites">
                                    <?php
                                        $sSelected = '';
                                        $objWebsites->resetRecordPointer();
                                        while($objWebsites->next())
                                        {
                                            if ($objWebsites->getID() == GLOBAL_WEBSITEID_SELECTEDINCMS)                                      
                                                $sSelected = ' class="selected"';
                                            else
                                                $sSelected = '';
                                            ?>
                                                <li <?php echo $sSelected ?>>
                                                    <?php
                                                        if ($bAllowedSiteChange)
                                                            echo '<a href="'.addVariableToURL(getURLCMSDashboard(), GETARRAYKEY_SELECTEDSITEID, $objWebsites->getID()).'">';
                                                    ?>
                                                    <?php echo $objWebsites->getWebsiteName();?>
                                                    <?php
                                                        if ($bAllowedSiteChange)
                                                            echo '</a>';
                                                    ?>
                                                </li>
                                            <?php
                                        }
                                    ?>
                                </ul>
                            <?php
                        }
                    ?>
		</div> <!-- end left column -->
                
		<div class="maincolumn">
            <div class="maincolumn-centerwrapper">
                <h1><?php echo $sTitle; ?></h1>
                <?php             
                    if (isset($arrTabsheets))
                    {
                        if ($arrTabsheets)
                        {
                            include_once GLOBAL_PATH_LOCAL_CMS_TEMPLATES.DIRECTORY_SEPARATOR.'tpl_block_tabsheets.php'; 
                        }
                    }
                ?>                
                <div class="maincolumn-contentwrapper">
                    <?php echo $sHTMLContentMain; ?>
                </div>
            </div>
		</div> <!-- end maincolumn -->		


	</div> <!-- end content -->
	
	
</div><!-- end page-->

    <!-- javascript that the page need to be fully loaded for -->

    <?php  /***** honeypot: disable fields with 'letitbee' css class ****/  ?>
    <script>
        /* letitbee field */
        const objNodes = document.getElementsByClassName("letitbee");
        let iNodeCount = objNodes.length;
        for (i = 0; i < iNodeCount; i++) 
        {
            // objNodes[i].parentElement.style.visibility = 'hidden';
            objNodes[i].parentElement.style.height = 0; //otherwise it still takes up space
            objNodes[i].parentElement.style.overflow = 'hidden';
        } 
    </script>

    <script>        

            /**** FILE DRAG AND DROP ****/
                <?php //drag-drop tutorial: https://www.youtube.com/watch?v=Wtrin7C4b7w&t=1214s ?>
                const sMessageDragDropNumberOfFilesDragged = "<?php echo transcms('file_dropzone_numberoffilesdragged', 'files') ?>";

                // for all .file-dropzone-input elements 
                document.querySelectorAll(".file-dropzone-input").forEach(inputElement => 
                {
                    // look for element with class .file-dropzone 
                    const objDropZoneElement = inputElement.closest(".file-dropzone")
                    


                    //if user clicked: show file dialog
                    objDropZoneElement.addEventListener("click", objEvent => 
                    {
                        inputElement.click(); //trigger click of inputElement
                    });

                    //when user selected a file from filedialog
                    inputElement.addEventListener("change", objEvent => 
                    {
                        if (inputElement.files.length) //if at least 1 file is selected
                        {
                            updateFileDragThumbnail(objDropZoneElement, inputElement.files[0], inputElement.files.length);
                        }
                    })



                    // if file dragging, add class .file-dropzone-ondragover 
                    objDropZoneElement.addEventListener("dragover", objEvent => 
                    {
                        objEvent.preventDefault();//prevent default behavior of browser opening a file when dropping

                        objDropZoneElement.classList.add("file-dropzone-dragover");
                    });

                    // if file dragging-out of dropregion or cancel (like hitting escape), remove class .file-dropzone-ondragover
                    ["dragleave", "dragend"].forEach(type => 
                    {
                        objDropZoneElement.addEventListener(type, objEvent =>
                        {
                            objDropZoneElement.classList.remove("file-dropzone-dragover");
                        })
                    })



                    //handling the actual file drop
                    objDropZoneElement.addEventListener("drop", objEvent =>
                    {
                        objEvent.preventDefault();//prevent default behavior of browser opening a file when dropping
                        
                        if (objEvent.dataTransfer.files.length)
                        {
                            inputElement.files = objEvent.dataTransfer.files; //copy the dragged file properties to the actual html upload element in the form
                            updateFileDragThumbnail(objDropZoneElement, objEvent.dataTransfer.files[0], objEvent.dataTransfer.files.length);
                        }

                        //remove the file-dropzone-dragover class since the drag-over action is completed with a drop action
                        objDropZoneElement.classList.remove("file-dropzone-dragover");
                    })
                })


                //update thumbnail
                function updateFileDragThumbnail(objDropZoneElement, objFile, iNumberOfFilesDragged)
                {
                    let objThumbnailElement = objDropZoneElement.querySelector(".file-dropzone-thumbnail");

                    //if prompt text "drag here" exists, remove it
                    if (objDropZoneElement.querySelector(".file-dropzone-prompt"))
                    {
                        objDropZoneElement.querySelector(".file-dropzone-prompt").remove();
                    }

                    //first time there is no thumbnail element, so let's create it
                    if (!objThumbnailElement)
                    {
                        objThumbnailElement = document.createElement("div");
                        objThumbnailElement.classList.add("file-dropzone-thumbnail");
                        objDropZoneElement.appendChild(objThumbnailElement);
                    }

                    //update label with either filename or number of files
                    if (iNumberOfFilesDragged == 1)
                        objThumbnailElement.dataset.label = objFile.name;
                    else
                        objThumbnailElement.dataset.label = iNumberOfFilesDragged + " " + sMessageDragDropNumberOfFilesDragged;

                    //show thumbnail for images
                    if (objFile.type.startsWith("image/"))
                    {
                        const objReader = new FileReader();
                        
                        objReader.readAsDataURL(objFile);//read file as base64 data
                        objReader.onload = () => //if reading file done
                        {
                            objThumbnailElement.style.backgroundImage = `url('${ objReader.result }')`; //need to be backticks `
                        };
                    }
                    else
                    {
                        objThumbnailElement.style.backgroundImage = null; 
                    }

                }

    </script>
</body>
</html>