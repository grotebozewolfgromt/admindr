<?php
namespace dr\classes\dom\tag\form;

class InputSearch extends InputText
{
	public function __construct()
	{
		parent::__construct();
		$this->setType('search');
	}

}
?>