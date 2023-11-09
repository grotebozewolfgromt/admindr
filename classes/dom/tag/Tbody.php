<?php
namespace dr\classes\dom\tag;


/**
 * <tbody></tbody>
 */
class TBody extends TagAbstract
{
	public function __construct()
	{
		parent::__construct();
		$this->setTagName('tbody');
	}

// 	public function getXMLNodeSpecificToNode_OLD(DOMElement $objXMLElement)
// 	{
// 	}

	/**
	 * adds a row to the table
	 *
	 * @param array $arrColumnValues array met waardes voor in de rij
	 * @param string $sCSSClass css klasse
	 */
	public function addRow($arrColumnValues, $sCSSClass = '')
	{
		$objTR = new tr();
		$objTR->setClass($sCSSClass);

		foreach ($arrColumnValues as $sCol)
		{
			$objCol = new td();
			$objText = new text();
			$objText->setText($sCol);
			$objCol->addNode($objText);

			$objTR->addNode($objCol);
		}

		$this->addNode($objTR);
	}

	public function renderHTMLNodeSpecific()
	{

	}
}

?>