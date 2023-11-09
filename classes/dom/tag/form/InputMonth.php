<?php
namespace dr\classes\dom\tag\form;

class InputMonth extends InputText
{
	public function __construct()
	{
		parent::__construct();
		$this->setType('month');
	}

}

?>