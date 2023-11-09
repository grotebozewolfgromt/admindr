<?php
namespace dr\modules\Support;

use dr\classes\patterns\TModuleAbstract;


/**
 * Description of Support module
 *
 * @author drenirie
 * created 11 mrt 2022
 */
class Support extends TModuleAbstract
{
    const PERM_CAT_CONTACTFORM = 'contactform';

    //put your code here
    public function getIsSystemModule() 
    {
        return false;
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
            array('contactform', Support::PERM_CAT_CONTACTFORM, 'Contact form', 'Send an email')
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
            Support::PERM_CAT_CONTACTFORM => array (  
                                                TModuleAbstract::PERM_OP_VIEW,
                                                TModuleAbstract::PERM_OP_CREATE
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
        return 'Support';
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
