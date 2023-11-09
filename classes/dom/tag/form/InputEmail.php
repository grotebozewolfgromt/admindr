<?php
namespace dr\classes\dom\tag\form;

class InputEmail extends InputText
{
	public function __construct()
	{
		parent::__construct();
		$this->setType('email');
	}

}
?>