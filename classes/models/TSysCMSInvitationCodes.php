<?php

namespace dr\classes\models;

use dr\classes\models\TModel;


/**
 * Invitation codes for cms accounts
 * Only users with a redemption code can create an account
 * 
 * created 4 maart 2022
 * 4 mrt 2022: TCMSInvitationCodes: 
 */

class TSysCMSInvitationCodes extends TRedemptionCodesAbstract
{

	/**
	 * the child has to inherit this
	 *
	 * @return string name of database table
	*/
	public static function getTable()
	{
		return GLOBAL_DB_TABLEPREFIX.'SysCMSInvitationCodes';
	}
} 
?>