<?php
namespace dr\modules\Mod_Dev;

use dr\classes\patterns\TModuleAbstract;

/**
 * I wanted to have a module to do some experiments with and testing new functionality without changing 
 * existing modules/code/scripts
 */
 

/**
 * Description of Mod_Dev
 *
 * @author drenirie
 */
class Mod_Dev extends TModuleAbstract
{
    const PERM_CAT_ALL = 'all';

    //put your code here
    public function getIsSystemModule() 
    {
        return false;
    }

    /**
     * returns list of models that are used
     * 
     * THE ORDER IN WHICH IT RETURNS IS IMPORTANT
     * First the dependencies, than the objects itself
     *
     * system modules are done in the system, so they return: array()
     * 
     * @return array 1d with recordlistobjects
     */    
    public function getModelObjects() 
    {
        return array();
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
        return array(
            array('scripttests.php', Mod_Dev::PERM_CAT_ALL, 'script tests', 'testing new php scripts'),
            array('fileupload.php', Mod_Dev::PERM_CAT_ALL, 'file upload', 'file upload'),
            array('fileuploadfetch.php', Mod_Dev::PERM_CAT_ALL, 'file upload w fetch', 'file upload w fetch'),
            array('fileuploadprogress.php', Mod_Dev::PERM_CAT_ALL, 'file upload w progressbar', 'file upload w fetch')
        );
    }  


    public function getCategoryDefault()
    {
        return TModuleAbstract::CATEGORYDEFAULT_TOOLS;
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
            Mod_Dev::PERM_CAT_ALL => array (  
                                                TModuleAbstract::PERM_OP_VIEW
                                                ),
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
        return 'dev mod';
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
