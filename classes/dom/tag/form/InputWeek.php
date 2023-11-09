<?php

namespace dr\classes\dom\tag\form;

class InputWeek extends InputText
{
	public function __construct()
	{
		parent::__construct();
		$this->setType('week');
	}

}
?>