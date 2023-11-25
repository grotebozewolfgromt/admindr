<?php
namespace dr\modules\Mod_Sys_Modules;

use dr\classes\patterns\TModuleAbstract;



/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SysLanguages
 *
 * @author drenirie
 */
class Mod_Sys_Modules extends TModuleAbstract
{
    const PERM_CAT_MODULESINSTALLED = 'modules installed';
    const PERM_CAT_MODULESUNINSTALLED = 'modules uninstalled';
    const PERM_CAT_MODULECATEGORIES = 'module categories';

    const PERM_OP_UNINSTALL = 'uninstall';
    const PERM_OP_INSTALL = 'install';
    const PERM_OP_UPLOAD = 'upload';

    //put your code here
    public function getIsSystemModule() 
    {
        return true;
    }

    public function getModelObjects() 
    {
        return array();
    }

    public function getCategoryDefault()
    {
        return TModuleAbstract::CATEGORYDEFAULT_SYSTEM;
    }

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
    public function getTabsheets()
    {
        // return array(
        //     'list_installedmodules.php' => 'installed',
        //     'list_uninstalledmodules.php' => 'not installed',
        //     'list_modulescategories.php' => 'module categories'
        // );
        return array(
            array('list_installedmodules', Mod_Sys_Modules::PERM_CAT_MODULESINSTALLED, 'installed', 'manage all available modules in system'),
            array('list_uninstalledmodules', Mod_Sys_Modules::PERM_CAT_MODULESUNINSTALLED, 'not installed', 'manage modules that are not installed yet'),
            array('list_modulescategories', Mod_Sys_Modules::PERM_CAT_MODULECATEGORIES, 'module categories', 'manage categories of modules')
        );
    } 



    public function handleCronJob() 
    {
        return true;
    }

    /**
     * return permissions array
     *
     * @return array
     */
    public function getPermissions()
    {
        return array(
            Mod_Sys_Modules::PERM_CAT_MODULESINSTALLED => array (  
                                                TModuleAbstract::PERM_OP_VIEW,
                                                TModuleAbstract::PERM_OP_DELETE,
                                                Mod_Sys_Modules::PERM_OP_UNINSTALL,
                                                TModuleAbstract::PERM_OP_CHANGE,
                                                TModuleAbstract::PERM_OP_CREATE,
                                                TModuleAbstract::PERM_OP_LOCKUNLOCK,
                                                TModuleAbstract::PERM_OP_CHECKINOUT,
                                                TModuleAbstract::PERM_OP_CHANGEORDER
                                            ),
            Mod_Sys_Modules::PERM_CAT_MODULESUNINSTALLED => array (  
                                                TModuleAbstract::PERM_OP_VIEW,
                                                TModuleAbstract::PERM_OP_DELETE,
                                                Mod_Sys_Modules::PERM_OP_INSTALL,
                                                Mod_Sys_Modules::PERM_OP_UPLOAD),
            Mod_Sys_Modules::PERM_CAT_MODULECATEGORIES => array (                  
                                                TModuleAbstract::PERM_OP_VIEW,
                                                TModuleAbstract::PERM_OP_DELETE,
                                                TModuleAbstract::PERM_OP_CHANGE,
                                                TModuleAbstract::PERM_OP_CREATE,
                                                TModuleAbstract::PERM_OP_LOCKUNLOCK,
                                                TModuleAbstract::PERM_OP_CHECKINOUT, 
                                                TModuleAbstract::PERM_OP_CHANGEORDER
                                                )

            ) ;    
    }     

    /**
     * get the default (non-internal) name for the module.
     * This is de DEFAULT ENGLISH translation as it is passed to the
     * transm() function
     *
     * @return void
     */
    public function getNameDefault()
    {
        return 'Modules';
    }      

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
    public function getSettingsEntries()
    {
        return array();
    }    


    /**
     * who made it?
     * @return string
     */
    public function getAuthor()
    {
        return 'Dennis Renirie';
    }

    /**
     * versi0n 1,2,3 etc needed for database refactoring.
     * when you are doing a database structur change, increment the version number by 1
     * (we use integers for fast, easy and reliable comparing between version numbers)
     * 
     * @return int
     */
    public function getVersion()
    {
         return 1;
    }

    /**
     * returns the url to the settings page in the cms
     * when '' is returned the setting screen is assumed not to exist
     * 
     * @return string
     */
    public function getURLSettingsCMS()
    {
        return '';
    }


    /**
     * is module visible in CMS menus?
     *
     * @return boolean 
     */
    public function isVisibleCMS()
    {
        return true;
    }


    /**
     * is module visible in menus in the frontend of the site?
     *
     * @return boolean 
     */
    public function isVisibleFrontEnd()
    {
        return false;
    }
}
