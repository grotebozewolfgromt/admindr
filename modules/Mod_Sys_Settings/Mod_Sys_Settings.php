<?php
namespace dr\modules\Mod_Sys_Settings;

use dr\classes\models\TSysSettings;
use dr\classes\patterns\TModuleAbstract;


/**
 * Description of Mod_Sys_Settings
 * 
 * settings of the framework
 *
 * @author drenirie
 */
class Mod_Sys_Settings extends TModuleAbstract
{
    const PERM_CAT_USERSETTINGS = 'user settings';
    const PERM_CAT_SYSTEMSETTINGS = 'system settings';
    const PERM_CAT_THEMES = 'themes';
    const PERM_CAT_CRONJOB = 'cronjob';

    const PERM_OP_EXECUTE = 'execute';

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
        return array(
            array('settings_user', Mod_Sys_Settings::PERM_CAT_USERSETTINGS, 'User settings', 'settings that apply only to current user'),
            array('settings_system', Mod_Sys_Settings::PERM_CAT_SYSTEMSETTINGS, 'System settings', 'settings that apply to the whole system'),
            array('settings_themes_overview', Mod_Sys_Settings::PERM_CAT_THEMES, 'Themes', 'View and change themes'),
            array('settings_cronjob', Mod_Sys_Settings::PERM_CAT_CRONJOB, 'Cron job', 'Execute a cron job')
        );
    } 



    public function getCategoryDefault()
    {
        return TModuleAbstract::CATEGORYDEFAULT_SYSTEM;
    }

    /**
     * handles cron job
     *
     * @return bool
     */
    public function handleCronJob() 
    {
        $bResult = true;

        //updating permissions
        error_log('updating settings');
        echo 'updating settings ... <br>';
        $objSettings = new \dr\classes\models\TSysSettings();
        if (!$objSettings->updateSettingsDB())
            $bResult = false;
        unset($objSettings);

        return $bResult;
    }

    /**
     * return permissions array
     *
     * @return array
     */
    public function getPermissions()
    {
        return array(
            Mod_Sys_Settings::PERM_CAT_USERSETTINGS => array (  
                                                TModuleAbstract::PERM_OP_VIEW,
                                                TModuleAbstract::PERM_OP_CHANGE
                                                ),
            Mod_Sys_Settings::PERM_CAT_SYSTEMSETTINGS => array (  
                                                TModuleAbstract::PERM_OP_VIEW,
                                                TModuleAbstract::PERM_OP_CHANGE
                                                ),
            Mod_Sys_Settings::PERM_CAT_CRONJOB => array (  
                                                TModuleAbstract::PERM_OP_VIEW,
                                                Mod_Sys_Settings::PERM_OP_EXECUTE
                                                ),
            Mod_Sys_Settings::PERM_CAT_THEMES => array (  
                                                TModuleAbstract::PERM_OP_VIEW,
                                                TModuleAbstract::PERM_OP_CHANGE
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
        return false;
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
        return 'settings';
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
