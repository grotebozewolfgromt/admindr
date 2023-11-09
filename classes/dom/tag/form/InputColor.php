<?php
namespace dr\classes\dom\tag\form;

class InputColor extends InputText
{
	public function __construct()
	{
		parent::__construct();
		$this->setType('color');
	}

}
?>