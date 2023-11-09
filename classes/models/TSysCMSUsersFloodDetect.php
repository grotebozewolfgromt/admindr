<?php

namespace dr\classes\models;

use dr\classes\models\TModel;


/**
 * @created 16 jan 2020 drenirie
 */

class TSysCMSUsersFloodDetect extends TUsersFloodDetectAbstract
{
	/**
	 * de child moet deze overerven
	 *
	 * @return string naam van de databasetabel
	*/
	public static function getTable()
	{
		return GLOBAL_DB_TABLEPREFIX.'SysCMSUsersFloodDetect';
	}
	

} 
?>