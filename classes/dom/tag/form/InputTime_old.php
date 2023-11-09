<?php
namespace dr\classes\dom\tag\form;

/**
 * A tag for a time
 * 
 * this is a regular <input type="text"> because the <input type="date"> is deprecated by the html consortium
 *
 * This tag renderer attaches automatically a datetimepicker, so you don't have to look after that.
 * 
 */
class InputTime_old extends InputDatetime
{
    
	public function __construct($sPHPDateFormat = '')
	{
		parent::__construct($sPHPDateFormat);
		//$this->setType('time');--> it's a regular <input type="text"> which is inhertied from InputText
	}



}
?>