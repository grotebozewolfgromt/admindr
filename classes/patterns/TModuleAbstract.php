<?php
namespace dr\classes\patterns;

use dr\classes\models\TModel;
use dr\classes\models\TSysCMSPermissions;
use dr\classes\models\TSysModules;
use dr\classes\models\TSysModulesCategories;
use dr\classes\models\TSysSettings;
use dr\classes\models\TTableVersions;
use dr\classes\models\TSysTableVersions;

/**
 * Description of TModuleAbstract
 *
 * This is the business logic of a module
 * The idea behind this class is that you can ask certain properties, like: is it a system module.
 * THE CLASS IS ONLY INSTANTIATED WHEN USED SPECIFICALLY!
 * So it is NOT instantiated when displaying a list of modules in the cms.
 * This is done to keep the (system performance) costs down: no unnessary classes instantiated, only when absolutely needed
 * 
 * the first hook of a module to the system is always the index.php. This file is ALWAYS present to access a module!
 * 
 * 12 juli 2012: TModule: getPathLocalLanguages() toegevoegd
 * 13 juli 2012: TModule: translation object toegevoegd
 * 17 juli 2012: TModule: loadLibrary() toegevoegd
 * 17 juli 2012: TModule: getPathLocalLanguageFiles
 * 18 juli 2012: TModule: loadTranslation() toegevoegd
 * 23 juli 2012: TModule: tabsheets object toegevoegd
 * 25 juli 2012: TModule: rename getPathLocalViewsSystem() -> getPathLocalViewsCMS()
 * 27 sept 2012: TModule: aanpassingen voor nieuwe manier met prepared statement
 * 2 mrt 2014: TModule: added install(), uninstall() en upgrade()
 * 14 mrt 2014: TModule: tabsheets verwijderd (zitten standaard in de controller, dus ook de controller van deze module)
 * 31 jul 2014: TModule: install() aangepast: roept de install van TRecordList aan
 * 31 jul 2014: TModule: uninstall() aangepast: roept de install van TRecordList aan
 * 1 aug 2014: TModule: update() aangepast: roept de uninstall van TRecordList aan
 * 12 aug 2014: TModule: viewsCMS verwijderd
 * 9 mei 2019: aanpasssingen voor de cms 4 reboot
 * 9 mei 2019: TModule -> TModuleAbstract
 * 
 * 
 * @author d. renirie
 */
abstract class TModuleAbstract
{   
    //default module categories
    const CATEGORYDEFAULT_SYSTEM = 'system management';
    const CATEGORYDEFAULT_WEBSITE = 'website';
    const CATEGORYDEFAULT_TOOLS = 'tools';

    //permission category (create your own in the child class) in authorisation resource (i.e. Mod_Books/books/view)
    //const PERM_CAT_BOOKS   = 'books'; --> when it concerns books, but it can also be users, categories or any other database record
    
    //default permissions for authorisation system (add your own in the child class)
    //(these are just here out of consistency with the rest of the module operations)
    const PERM_OP_DELETE        = AUTH_OPERATION_DELETE;
    const PERM_OP_CREATE        = AUTH_OPERATION_CREATE;
    const PERM_OP_CHANGE        = AUTH_OPERATION_CHANGE;
    const PERM_OP_VIEW          = AUTH_OPERATION_VIEW;
    const PERM_OP_CHECKINOUT    = AUTH_OPERATION_CHECKINOUT;
    const PERM_OP_LOCKUNLOCK    = AUTH_OPERATION_LOCKUNLOCK;
    const PERM_OP_CHANGEORDER   = AUTH_OPERATION_CHANGEORDER;


    public function __construct()
    {}
    

    /**
     * who made it?
     * @return string
     */
    public function getAuthor()
    {
        return 'Dennis Renirie';
    }
  
    /**
     * versi0n 1,2,3 etc
     * 
     * @return int
     */
    public function getVersion()
    {
       return 1; 
    }

    
    /**
     * This function is called to update the module for all websites
     * (i.e. creating database tables, directories, files etc)
     * 
     * @var array $arrPreviousDependenciesModelClasses
     * @return bool success ?
     */
    public function updateModels($arrPreviousDependenciesModelClasses, TSysTableVersions $objTableVersionsFromDB)
    {
    	$arrModels = $this->getModelObjects();
    	
    	//call update on each mode
    	if ($arrModels)
    	{
	    	foreach($arrModels as $objModel)
	    	{
	    		if (!$objModel->update($arrPreviousDependenciesModelClasses, $objTableVersionsFromDB))
	    			return false;
	    	}
    	}
    	
    	return true;
    }    

    /**
     * 
     * the $arrPreviousDependenciesModelClasses prevents a endless loop by storing all the classnames that are already installed
     * 
     * @param array $arrPreviousDependenciesModelClasses array with class names
     * @return bool succes?
     */
    private function installModels($arrPreviousDependenciesModelClasses = null)
    {
    	$arrModels = $this->getModelObjects();
                
    	//call install on each mode
    	if ($arrModels)
    	{
	    	foreach($arrModels as $objModel)
	    	{	    
                if ($objModel) //can be empty array
                {
                    if ($objModel instanceof TModel)
                    {
    	    		    if (!$objModel->install($arrPreviousDependenciesModelClasses))
	        			    return false;
                    }
                    else
                        return false;
                }
	    	}
    	}
    	
    	return true;
    }

    
    /**
     * uninstall all tables from database
     * 
     * @param array $arrPreviousDependenciesModelClasses
     * @return boolean succes?
     */
    private function uninstallModels($arrPreviousDependenciesModelClasses = null)
    {
    	$arrModels = $this->getModelObjects();
        $arrModels = array_reverse($arrModels);//reverse because of dependencies
    	 
    	//call install on each mode
    	if ($arrModels)
    	{
    		foreach($arrModels as $objModel)
    		{
    			if (!$objModel->uninstall($arrPreviousDependenciesModelClasses))
    				return false;
    		}
    	}
    	 
    	return true;
    }
    
    /**
     * returns list of instantiated models that are used
     * 
     * THE ORDER IN WHICH IT RETURNS IS IMPORTANT
     * First the dependencies, than the objects itself
     *
     * system modules are done in the system, so they return: array()
     * 
     * @return array 1d with TModel objects
     */    
    abstract public function getModelObjects();
    
      
   
    /**
     * returns if a module is a framework-system module
     * when true, the module can not be removed from the framework
     *
     * when not explicitly set, the default value is false!
     *
     * @return bool
     */
    abstract public function getIsSystemModule();

    
    /**
     * return the (untranslated) module-category name.
     * this is the default module-category which is used to put the module in when installing the module
     * 
     * on the left side of the screen are categories displayed:
     * -WEBSITE (=category
     * |- blog (module)
     * |- web pages (module)
     * -WEBSHOP (=category)
     * |- product catalog (module)
     * |- vat (module)
     * |- invoices (module)
     * -SYSTEM (=category)
     * |- users (module)
     * |- languages (module)
     * 
     * for system modules, use const TModuleAbstract::CATEGORYDEFAULT_SYSTEM
     * 
     */
    abstract public function getCategoryDefault();
    
   /**
     * returns the tabsheets for this module
     *
     * dwhen not overridden, it returns index by default
     * 
     * specify array with filename, permission-category, description like this:
     *         return array(
     *                     array('overview_blog.php', Mod_Blog::PERM_CAT_BLOG, 'blog posts', 'explanation about blog posts'),
     *                     array('overview_authors.php', Mod_Blog::PERM_CAT_AUTHORS, 'blog authors', 'explanation about authors for blog posts')
     *                  )
     * 
     * the tab names and descriptions are translated with the transm() function, so don't return translated tabnames and descriptions
     * 
     * @return array
     */   
    abstract  public function getTabsheets();


    /**
     * returns the html for the help
     * @return string
     */
    //public function getHTMLHelp();

    /**
     * returns the html results for the search command
     * @return string
     */
    //abstract public function getHTMLQuickSearch($sSearchCommand);


    /**
     * return contribution of this module for the dashboard/today screen 
     * @return string
     */
    //abstract public function getHTMLDashboard();


    
    /**
     * this function deletes a module from the framework, wich means:
     * 1) delete database tables
     * 2) delete module directory (optional)
     * 3) delete rights in userrights table 
     * 4) delete registration in module table
     *
     * if a module is marked as system module, it can not be deleted
     * 
     * @param bool $bPreventDeletionSystemModules can not delete system modules if true
     * @param bool $bDeleteFromDisk delete module directory
     * @return bool
     */
    // public function deleteModuleFromFramework($bPreventDeletionSystemModules = true)
    public function uninstallModule($bPreventDeletionSystemModules = true, $bDeleteFromDisk = false)
    {
        $bSuccess = true;

        error_log('TModuleAbstract->uninstallModule(): start uninstalling module '.get_class_short($this));

        if (($this->getIsSystemModule()) && ($bPreventDeletionSystemModules))
        {
            error('TModuleAbstract: This is a system module. System modules can not be removed.', $this);
            return false;
        }
        else
        {
            //delete permissions
            $objPermissions = new TSysCMSPermissions();
            if (!$objPermissions->deletePermissionsForModule(get_class_short($this)))
            {
                $bSuccess = false;
                error_log('TModuleAbstract->uninstallModule(): $this->deletePermissionsForModule() failed');
            }
            unset($objPermissions);

            //delete settings from database
            $objSettings = new TSysSettings();
            if (!$objSettings->deleteSettingsDBForModule(get_class_short($this)))
            {
                $bSuccess = false;
                error_log('TModuleAbstract->uninstallModule(): $this->deleteSettingsDBForModule() failed');
            }
            unset($objSettings);

            //delete module registration in database
            $objSysModules = new TSysModules();
            $objSysModules->find(TSysModules::FIELD_NAMEINTERNAL, get_class_short($this));
            $objSysModules->deleteFromDB(true);
            unset($objSysModules);

            //delete database table
            if (!$this->uninstallModels())
            {
                $bSuccess = false;
                error_log('TModuleAbstract->uninstallModule(): $this->uninstallModels() failed');
            }


            //delete directory
            if ($bSuccess && $bDeleteFromDisk)
            {
                if (!rmdirrecursive(GLOBAL_PATH_LOCAL_MODULES.DIRECTORY_SEPARATOR.get_class_short($this)))
                {
                    $bSuccess = false;
                    error_log('TModuleAbstract->uninstallModule(): rmdirrecursive() failed');
                }
            }

            //@TODO: delete rights in userrights database table
        }

        return $bSuccess;
    }

    /**
     * Install module in framework
     *
     * @return bool
     */
    public function installModule()
    {
        $iCatID = 0;

        error_log('start install module: '. get_class_short($this));

        
        //==== request default category to put the module in 
        $objTempCat = new TSysModulesCategories();  
        $objTempCat->find(TSysModulesCategories::FIELD_NAME, $this->getCategoryDefault());
        $objTempCat->limitOne(); //we need just one record to be returned
        if (!$objTempCat->loadFromDB())
        {
            error('TSysModules: $objTempCat->loadFromDB() failed in TModuleAbstract->installModule()');
            return false;
        }

        //if default category exists
        if ($objTempCat->count()> 0) 
        {
            $iCatID = $objTempCat->getID();
        }
        else //if default category NOT EXISTS
        {
            //take first record in table
            $objTempCat->newQuery();
            $objTempCat->clear();
            $objTempCat->limitOne();

            if (!$objTempCat->loadFromDB())
            {
                error('TSysModules: $objTempCat->loadFromDB() failed in TModuleAbstract->installModule()');
                return false;
            }       
            $iCatID = $objTempCat->getID();    
        }

        unset($objTempCat);

        
        //==== add module registration to database
        error_log('add module to module db table: '.get_class_short($this));
        $objTempNewMod = new TSysModules();
        $objTempNewMod->newRecord();
        $objTempNewMod->setNameInternal(get_class_short($this));
        $objTempNewMod->setCategoryID($iCatID);
        $objTempNewMod->setVisible($this->getIsVisible());
        $objTempNewMod->setNameDefault($this->getNameDefault());
        if (!$objTempNewMod->saveToDB())
            return false;


        //==== create models
        error_log('creating models for: '. get_class_short($this));
        if (!$this->installModels())
            return false;



        //add permissions to database table
        $objPermissions = new TSysCMSPermissions();
        if (!$objPermissions->createPermissionsForModule(get_class_short($this)))
        {   
            error_log('create permissions for module '.get_class_short($this).' FAILED!!');
            return false;
        }
        unset($objPermissions);


        //add settings to database
        $objSettings = new TSysSettings();
        if (!$objSettings->createSettingsDBByBLArr(get_class_short($this), $this->getSettingsEntries()))
        {   
            error_log('create settings for module '.get_class_short($this).' FAILED!!');
            return false;
        }
        unset($objSettings);        


        return true;
    }
  
    /**
     * handle all the actions when a cron job is called
     * 
     * Cronjob is an action that is automaticall performed once a day or once a 
     * week, without the user triggerig it manually
     * @return boolean true if successful
     */
    abstract public function handleCronJob();
    

    /**
     * the permissions that this module uses
     * 
     * Based on what this function returns, permissions are created 
     * when installing this module and deleted when uninstalling.
     * these permissions are used for the auth() function
     * 
     * if you forget to add a permission this way, the auth() function
     * will return false when you request authorisation
     * 
     * example with a module to register 'books' and 'authors':
     * return array(
     *       TModuleAbstract::PERM_CAT_BOOKS => array (TModuleAbstract::PERM_OP_VIEW,
     *                                                 TModuleAbstract::PERM_OP_DELETE,
     *                                                 TModuleAbstract::PERM_OP_CHANGE,
     *                                                 TModuleAbstract::PERM_OP_CREATE,
     *                                                 TModuleAbstract::PERM_OP_LOCKUNLOCK,
     *                                                 TModuleAbstract::PERM_OP_CHECKINOUT)
     *       TModuleAbstract::PERM_CAT_AUTHORS => array (TModuleAbstract::PERM_OP_VIEW,
     *                                                 TModuleAbstract::PERM_OP_DELETE,
     *                                                 TModuleAbstract::PERM_OP_CHANGE)
     *      ) ;
     * 
     * 
     * @return array 2d
     */
    abstract public function getPermissions();


    /**
     * return an array with all settings for the cms
     *
     * this will return an array in this format:
     *         return array(
     *       SETTINGS_CMS_MEMBERSHIP_ANYONECANREGISTER => array ('0', TP_BOOL) //default, type
     *       );   
     * 
     * @return array
     */
    abstract public function getSettingsEntries();

    
    /**
     * is module visible in menus?
     *
     * @return boolean 
     */
    abstract public function getIsVisible();

    /**
     * get the default (non-internal) name for the module.
     * This is de DEFAULT ENGLISH translation as it is passed to the
     * transm() function
     *
     * @return string
     */
    abstract public function getNameDefault();

    public function getNameInternal()
    {
        return get_class_short($this);
    }
}
?>
