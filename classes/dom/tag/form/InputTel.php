<?php
namespace dr\classes\dom\tag\form;


class InputTel extends InputText
{
	public function __construct()
	{
		parent::__construct();
		$this->setType('tel');
	}

}

?>