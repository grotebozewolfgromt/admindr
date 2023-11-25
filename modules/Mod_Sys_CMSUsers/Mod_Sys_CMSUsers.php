<?php
namespace dr\modules\Mod_Sys_CMSUsers;

use dr\classes\models\TSysCMSUserAccounts;
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
class Mod_Sys_CMSUsers extends TModuleAbstract
{
    const PERM_CAT_USERS = 'users';
    const PERM_CAT_USERROLES = 'userroles';
    const PERM_CAT_USERACCOUNTS = 'useraccounts';
    const PERM_CAT_PERMISSIONSCOUNTRIES = 'permissionscountries';
    const PERM_CAT_INVITATIONCODES = 'invitationcodes';

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
        //     'list_users.php' => 'users', 
        //     'list_usersgroups.php' => 'user groups'            
        //     ) ;
        return array(
            array('list_users', Mod_Sys_CMSUsers::PERM_CAT_USERS, 'Users', 'Users of the cms'),
            array('list_usersaccounts', Mod_Sys_CMSUsers::PERM_CAT_USERACCOUNTS, 'User accounts', 'Manage user accounts'),
            array('list_usersroles', Mod_Sys_CMSUsers::PERM_CAT_USERROLES, 'Roles & permissions', 'Manage user roles and permissions'),
            array('list_permissionscountries', Mod_Sys_CMSUsers::PERM_CAT_PERMISSIONSCOUNTRIES, 'Permitted countries', 'Manage countries with access to the system'),
            array('list_invitationcodes', Mod_Sys_CMSUsers::PERM_CAT_INVITATIONCODES, 'Invite codes', 'Manage access invite codes for account creation in the system')
        );
    } 



    public function getCategoryDefault()
    {
        return TModuleAbstract::CATEGORYDEFAULT_SYSTEM;
    }

    public function handleCronJob() 
    {
        $bResult = true;

        //delete old login sessions from database
        logCronjob(__CLASS__.': '.__FUNCTION__.': '.__LINE__, 'deleting old login sessions from database ...');
        echo 'deleting old login sessions from database ... <br>';
        $objUsersSessions = new \dr\classes\models\TSysCMSUsersSessions();
        if (!$objUsersSessions->deleteOldSessionsFromDB(GLOBAL_COOKIE_EXPIREDAYS))
            $bResult = false;
        
        //delete old login attempt from database
        logCronjob(__CLASS__.': '.__FUNCTION__.': '.__LINE__, 'cleaning up user-flood logs from database ...');
        echo 'cleaning up user-flood logs from database ... <br>';
        $objUsersAttempts = new \dr\classes\models\TSysCMSUsersFloodDetect();
        if (!$objUsersAttempts->deleteOldLogsFromDB())
            $bResult = false;


        //updating permissions
        logCronjob(__CLASS__.': '.__FUNCTION__.': '.__LINE__, 'updating usergroup permissions');
        echo 'updating usergroup permissions ... <br>';
        $objPermissions = new \dr\classes\models\TSysCMSPermissions();
        if (!$objPermissions->updatePermissions())
            $bResult = false;
        unset($objPermissions);

        //delete expired email tokens
        logCronjob(__CLASS__.': '.__FUNCTION__.': '.__LINE__, 'delete expired email tokens');
        echo 'deleting expired email tokens from database ... <br>';
        $objUsers = new \dr\classes\models\TSysCMSUsers();
        if (!$objUsers->deleteEmailTokensExpired())
            $bResult = false;
        unset($objUsers);

        //delete expired users
        logCronjob(__CLASS__.': '.__FUNCTION__.': '.__LINE__, 'delete users that were scheduled for deletion');
        echo 'removing users that were scheduled for deletion ... <br>';
        $objUsers = new \dr\classes\models\TSysCMSUsers();
        if (!$objUsers->deleteUsersExpired())
            $bResult = false;
        unset($objUsers);

        //deleting user accounts (including all users in them)
        logCronjob(__CLASS__.': '.__FUNCTION__.': '.__LINE__, 'delete user accounts (including deleting all users in those accounts) that were scheduled for deletion');
        echo 'removing user accounts (including deleting all users in those accounts) that were scheduled for deletion ... <br>';
        $objAccounts = new TSysCMSUserAccounts();
        if (!$objAccounts->deleteDBUserAccountsExpired())
            $bResult = false;
        unset($objAccounts);




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
            Mod_Sys_CMSUsers::PERM_CAT_USERS => array (  
                                                TModuleAbstract::PERM_OP_VIEW,
                                                TModuleAbstract::PERM_OP_DELETE,
                                                TModuleAbstract::PERM_OP_CHANGE,
                                                TModuleAbstract::PERM_OP_CREATE,
                                                TModuleAbstract::PERM_OP_LOCKUNLOCK,
                                                TModuleAbstract::PERM_OP_CHECKINOUT
                                                ),
            Mod_Sys_CMSUsers::PERM_CAT_USERROLES => array (  
                                                TModuleAbstract::PERM_OP_VIEW,
                                                TModuleAbstract::PERM_OP_DELETE,
                                                TModuleAbstract::PERM_OP_CHANGE,
                                                TModuleAbstract::PERM_OP_CREATE,
                                                TModuleAbstract::PERM_OP_LOCKUNLOCK,
                                                TModuleAbstract::PERM_OP_CHECKINOUT,
                                                TModuleAbstract::PERM_OP_CHANGEORDER
                                                ),
            Mod_Sys_CMSUsers::PERM_CAT_USERACCOUNTS => array (  
                                                TModuleAbstract::PERM_OP_VIEW,
                                                TModuleAbstract::PERM_OP_DELETE,
                                                TModuleAbstract::PERM_OP_CHANGE,
                                                TModuleAbstract::PERM_OP_CREATE
                                                ),                                                
            Mod_Sys_CMSUsers::PERM_CAT_PERMISSIONSCOUNTRIES => array (  
                                                TModuleAbstract::PERM_OP_VIEW,
                                                TModuleAbstract::PERM_OP_DELETE,
                                                TModuleAbstract::PERM_OP_CHANGE,
                                                TModuleAbstract::PERM_OP_CREATE
                                                ),
            Mod_Sys_CMSUsers::PERM_CAT_INVITATIONCODES => array (  
                                                TModuleAbstract::PERM_OP_VIEW,
                                                TModuleAbstract::PERM_OP_DELETE,
                                                TModuleAbstract::PERM_OP_CHANGE,
                                                TModuleAbstract::PERM_OP_CREATE
                                                )                                                

            );    
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
        return 'Users + permissions';
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
