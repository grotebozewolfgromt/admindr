<?php
namespace dr\classes\dom\tag;

use dr\classes\dom\tag\Text;

class Script extends TagAbstract
{
	public function __construct()
	{
		parent::__construct();
		$this->setTagName('script');

		//you probably wanna have some text in the i tag
		//so we create that automatically for you:
		$objText = new Text();
		$this->addNode($objText);
	}

        /**
         * set text of script
         * @param string $sScript
         */
	public function setText($sScript)
	{
		$this->getNode(0)->setText($sScript, false);
	}
        
        /**
         * alias for setText
         * @param string $sScript
         */
	public function setScript($sScript)
	{
		$this->getNode(0)->setText($sScript, false);
	}        

	public function getText()
	{
		return $this->getNode(0)->getText();
	}

        /**
         * alias for getText()
         * 
         * @return string
         */
        public function getScript()
        {
                return $this->getNode(0)->getText();
        }
        
// 	public function getXMLNodeSpecificToNode_OLD(DOMElement $objXMLElement)
// 	{
// 	}

	public function renderHTMLNodeSpecific()
	{

	}
}

?>
