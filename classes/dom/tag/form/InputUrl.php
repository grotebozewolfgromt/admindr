<?php
namespace dr\classes\dom\tag\form;


class InputUrl extends InputText
{
	public function __construct()
	{
		parent::__construct();
		$this->setType('url');
	}

}
?>