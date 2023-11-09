<?php
namespace dr\classes\dom\tag\form;

class InputRange extends InputNumber
{
	public function __construct()
	{
		parent::__construct();
		$this->setType('range');
	}

}

?>