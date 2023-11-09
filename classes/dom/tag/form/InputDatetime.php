<?php
namespace dr\classes\dom\tag\form;


/**
 * A tag for a datetime
 * 
 * this is a regular <input type="text"> because the <input type="date"> is deprecated by the html consortium
 *
 * This tag renderer attaches automatically a datetimepicker, so you don't have to look after that.
 * 
 */
class InputDateTime extends InputText
{
        private $sPHPDateFormat;
            
	public function __construct($sPHPDateFormat = '')
	{
                parent::__construct();
                //$this->setType('datetime');  --> it's a regular <input type="text"> which is inhertied from InputText

                $this->sPHPDateFormat = $sPHPDateFormat;
	}

        public function getPHPDateFormat()
        {
                return $this->sPHPDateFormat;
        }
        
        public function setPHPDateFormat($sPHPDateFormat = '')
        {
                $this->sPHPDateFormat = $sPHPDateFormat;
        }
}

?>