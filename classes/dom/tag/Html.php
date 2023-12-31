<?php
namespace dr\classes\dom\tag;


/**
 * <html></html>
 */
class Html extends TagAbstract
{
	public function __construct()
	{
		parent::__construct();
		$this->setTagName('html');

		//according to the w3c standards: every html tag needs to have a head,
		//so we create that automatically for you:
		$objHead = new head();
		$this->addNode($objHead);

		//according to the w3c standards: every html tag needs to have a body,
		//so we create that automatically for you:
		$objBODY = new body();
		$this->addNode($objBODY);
	}

	/**
	 * returns the head object of the html tag
	 *
	 * @return head head object
	 */
	public function getHead()
	{
		return $this->getNode(0);
	}

	/**
	 * returns the body object of the html tag
	 *
	 * @return body body object
	 */
	public function getBody()
	{
		return $this->getNode(1);
	}

// 	public function getXMLNodeSpecificToNode_OLD(DOMElement $objXMLElement)
// 	{
// 	}


	/**
	 * return HTML5 code as string
	 *
	 * @return string
	 */
	public function renderHTML()
	{
		$sHTML = '<!DOCTYPE html>'."\n";
		$sHTML .= $this->renderHTMLNode();

		return $sHTML;
	}

	/**
	 * save rendered result to file
	 * @param string $sFileName path of the file
	 * @return bool file save success ?
	 */
	public function saveToFile($sFileName)
	{
		$sRendered = $this->renderHTML();
		return saveToFileString($sRendered, $sFileName);
	}

	public function renderHTMLNodeSpecific()
	{

	}

}

?>