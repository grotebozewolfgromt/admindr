<?php
namespace dr\modules\Mod_Sys_Websites;

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
class Mod_Sys_Websites extends TModuleAbstract
{
    const PERM_CAT_WEBSITES = 'websites';

    //put your code here
    public function getIsSystemModule() 
    {
        return true;
    }

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
        // return array(
        //     'overview.php' => 'overview'
        //     ) ;
        return array(
            array('list_websites', Mod_Sys_Websites::PERM_CAT_WEBSITES, 'websites', 'manage websites in system')
        );
    } 

 

    public function getCategoryDefault()
    {
        return TModuleAbstract::CATEGORYDEFAULT_SYSTEM;
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
            Mod_Sys_Websites::PERM_CAT_WEBSITES => array (  
                                                TModuleAbstract::PERM_OP_VIEW,
                                                TModuleAbstract::PERM_OP_DELETE,
                                                TModuleAbstract::PERM_OP_CREATE,
                                                TModuleAbstract::PERM_OP_CHANGE,
                                                TModuleAbstract::PERM_OP_LOCKUNLOCK,
                                                TModuleAbstract::PERM_OP_CHECKINOUT, 
                                                TModuleAbstract::PERM_OP_CHANGEORDER
                                                )
            ) ;    
    }     

    /**
     * is module visible in menus?
     *
     * @return boolean 
     */
    public function getIsVisible()
    {
        return true;
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
        return 'Websites';
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
}
