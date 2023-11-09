<?php
namespace dr\classes\dom\tag\form;

use dr\classes\dom\tag\Text;
use dr\classes\types\TDateTime;

/**
 * A tag for a time
 * 
 * represents 2 pulldown comboboxes: 1 for the hour, 1 for the minutes
 * <input type="date"> is deprecated by the html consortium
 * 
 * you are able to set the minute increments with setIncrementMinutes()
 * for example: setIncrementMinutes(15) will produce a minute box with 4 values: 00, 15, 30 and 45.
 *
 * This tag renderer attaches automatically a datetimepicker, so you don't have to look after that.
 * 
 * 27-nov-2021: InputTime omgebouwd zodat deze 2 comboboxen voor uren en minuten weergeeft
 */
class InputTime extends InputDatetime
{
	private $iIncrementMinutes = 1; //the minute increments in the dropdown boxes. 1 min = default, 5 min, 10min or 15min could also be, depending on the usecase
	private $objSelHours = null;
	private $objSelMinutes = null;
	private $objTxtHourMinuteSeparator = null; //semicolon that separates minutes with hours

	public function __construct($sPHPDateFormat = '')
	{
		parent::__construct($sPHPDateFormat);	
		$this->setTagName(''); //we override the tagname because we don't want to render this tag, only its childs

		$this->objSelHours = new Select();
		$this->fillSelectHours();
		$this->addNode($this->objSelHours);

		$this->objTxtHourMinuteSeparator = new Text();
		$this->objTxtHourMinuteSeparator->setText(':');
		$this->addNode($this->objTxtHourMinuteSeparator);

		$this->objSelMinutes = new Select();
		$this->fillSelectMinutes();
		$this->addNode($this->objSelMinutes);
	}

	/**
	 * set how many minutes are time increments in pull down box.
	 * 1 = default
	 * 5, 10, or 15 minutes are pretty good values, depending on the usecase.
	 *
	 * @param integer $iMinutes how many minutes between items in pulldown box?
	 * @return void
	 */
	public function setIncrementMinutes($iMinutes = 1)
	{
		if (is_int($iMinutes))
		{
			if ($iMinutes > 0)
				$this->iIncrementMinutes = $iMinutes;
		}
	}

	/**
	 * get how many minutes are time increments in pull down box.
	 * 1 = default
	 * 5, 10, or 15 minutes are pretty good values, depending on the usecase.
	 *
	 * @return integer
	 */
	public function getIncrementMinutes()
	{
		return $this->iIncrementMinutes;
	}

	/**
	 * fill hours combobox with hours
	 *
	 * @return void
	 */
	private function fillSelectHours()
	{
		$objOption = null;

		//@todo support for american AM/PM notation

		for ($iHours = 0; $iHours < 24; ++$iHours) //from 0 to 23
		{
			$objOption = new Option();
			$objOption->setValue($iHours);
			$objOption->setText(str_pad($iHours, 2, '0', STR_PAD_LEFT)); //including trailing 0
			$this->objSelHours->addNode($objOption);
		}
	}

	/**
	 * fill minutes combobox with minutes
	 *
	 * @return void
	 */
	private function fillSelectMinutes()
	{
		$objOption = null;

		for ($iMinutes = 0; $iMinutes < 60; $iMinutes = $iMinutes + $this->iIncrementMinutes) //from 0 to 59
		{
			$objOption = new Option();
			$objOption->setValue($iMinutes);
			$objOption->setText(str_pad($iMinutes, 2, '0', STR_PAD_LEFT)); //including trailing 0
			$this->objSelMinutes->addNode($objOption);
		}
	}	


	/**
	 * sets name of this "tag" and child hours and minutes <select> boxes
	 *
	 * @param string $sName
	 * @return void
	 */
	public function setName($sName)
	{
		parent::setName($sName);

		$this->objSelHours->setName($this::getNameHoursElement($sName));
		$this->objSelMinutes->setName($this::getNameMinutesElement($sName));
	}

	/**
	 * sets id of this "tag" and child hours and minutes <select> boxes
	 *
	 * @param string $sName
	 * @return void
	 */
	public function setID($sID)
	{
		parent::setID($sID);

		$this->objSelHours->setID($this::getIDHoursElement($sID));
		$this->objSelMinutes->setID($this::getIDMinutesElement($sID));
	}	


	/**
	 * setting input value
	 * @param string $sValue
	 * @overriden
	 */
	public function setValue($sValue)
	{
		parent::setValue($sValue);

		$objDate = null;
		$iHours = 0;
		$iMinutes = 0;

		$objDate = date_create_from_format($this->getPHPDateFormat(), $sValue); //timestamp from time
		if ($objDate)  //can be null when $sValue = ""
		{
			$iHours = strToInt(date_format($objDate, 'H'));
			$iMinutes = strToInt(date_format($objDate, 'i'));//strToInt to get rid of eventual trailing zeros
		}
		$this->objSelHours->setSelectedOption(intToStr($iHours));
		$this->objSelMinutes->setSelectedOption(intToStr($iMinutes));		
	}	

	/**
	 * get values from _GET or _POST
	 *
	 * @param string $sFormMethod
	 * @return void
	* @overriden
	*/
	public function getContentsSubmitted($sFormMethod = Form::METHOD_POST)
	{
		$sNameHoursElement = '';
		$sNameMinutesElement = '';
		$iHours = 0;
		$iMinutes = 0;

		$sNameHoursElement = $this::getNameHoursElement($this->getName());
		$sNameMinutesElement =  $this::getNameMinutesElement($this->getName());

		//read the values from the proper array
		if ($sFormMethod == Form::METHOD_POST)
		{
			if (isset($_POST[$sNameHoursElement]))  //only when exists
				if (is_numeric($_POST[$sNameHoursElement])) //preventing any xss or whatever
					$iHours	= $_POST[$sNameHoursElement];
			
			if (isset($_POST[$sNameMinutesElement]))  //only when exists
				if (is_numeric($_POST[$sNameMinutesElement])) //preventing any xss or whatever
					$iMinutes = $_POST[$sNameMinutesElement];				
		}
		elseif ($sFormMethod == Form::METHOD_GET)
		{
			if (isset($_GET[$sNameHoursElement]))  //only when exists
				if (is_numeric($_GET[$sNameHoursElement])) //preventing any xss or whatever
					$iHours	= $_GET[$sNameHoursElement];
			
			if (isset($_GET[$sNameMinutesElement]))  //only when exists
				if (is_numeric($_GET[$sNameMinutesElement])) //preventing any xss or whatever
					$iMinutes = $_GET[$sNameMinutesElement];	
		}

		//setting the right time dateformat aware
		$objDate = new TDateTime();
		$objDate->setHour($iHours);
		$objDate->setMinute($iMinutes);
		$this->objContentsSubmitted->setValue($objDate->getDateAsString($this->getPHPDateFormat()));
		unset($objDate);

		return $this->objContentsSubmitted;
	}


	static function getNameHoursElement($sNameInputTime)
	{
		return $sNameInputTime.'_hours';
	}

	static function getNameMinutesElement($sNameInputTime)
	{
		return $sNameInputTime.'_minutes';
	}	

	static function getIDHoursElement($sNameInputTime)
	{
		return $sNameInputTime.'_hours';
	}

	static function getIDMinutesElement($sNameInputTime)
	{
		return $sNameInputTime.'_minutes';
	}		

}
?>