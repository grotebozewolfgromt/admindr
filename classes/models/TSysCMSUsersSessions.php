<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace dr\classes\models;

/**
 * Description of TSysCMSUsersSessions
 *
 * @author drenirie
 */
class TSysCMSUsersSessions extends TUsersSessionsAbstract
{
     /**
     * for the function defineTable() we need a TUsersAbstract instantiated
     * object to define the database tables
     * 
     * @return TUsersAbstract user object
     */
    protected function getNewUsersModel(): TUsersAbstract 
    {
        return new TSysCMSUsers();
    }

    public static function getTable() 
    {
        return GLOBAL_DB_TABLEPREFIX.'SysCMSUsersSessions';
    }


}
