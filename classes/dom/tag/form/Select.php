<?php
namespace dr\classes\dom\tag\form;

use dr\classes\dom\tag\form\Option;

/**
 * de select tag
 *
 * <select>
 *  <option>optie1</optio>
 *  <option>optie2</option>
 * </select>
 *
 *
 *
 * code example :
 *
 *  $objSelect = new select();
 $objSelect->setName('edtSelect');
 $objOption = new option();
 $objOption->setText('hallo meneer1');
 $objOption->setValue('1');
 $objSelect->addNode($objOption);
 $objOption = new option();
 $objOption->setText('hallo meneer2');
 $objOption->setValue('2');
 $objSelect->addNode($objOption);
 $objForm->addNode($objSelect);
 *
 * of het volgende is sneller:
 *
 * example normal:
 *     $objSelect = new select();
 $objSelect->setName('edtSelect');
 $objOption = new option();
 $objOption->setText('hallo meneer1');
 $objOption->setValue('1');
 $objSelect->addNode($objOption);
 $objOption = new option();
 $objOption->setText('hallo meneer2');
 $objOption->setValue('2');
 $objSelect->addNode($objOption);
 $objForm->addNode($objSelect);
 *
 * example short:
 *
 *  $objSelect = new select();
 $objSelect->setName('edtSelect2');
 $objSelect->addOption(1, 'hallo meneer1');
 $objSelect->addOption(2, 'hallo meneer2');
 $objForm->addNode($objSelect);
 * 
 * 9 jan 2020: Select(): setSelectedOption() added
 * 27 nov 2021: Select(): setSelectedOption() returns boolean if found or not +
 * 27 nov 2021: Select(): setSelectedOption(): speed increase: if found, loop is terminated
 * 14 nov 2022: Select(): added: setValue($sValue) wrapper for setSelectedOption($sHTMLOptionValue)
 */

class Select extends FormInputAbstract
{
	private $sOnChange = '';

	public function __construct()
	{
		parent::__construct();
		$this->setTagName('select');
	}

	public function setOnchange($sOnChangeEvent)
	{
		$this->sOnChange = $sOnChangeEvent;
	}

	public function getOnchange()
	{
		return $this->sOnChange;
	}

	public function addOption($sValue, $sText, $bDisabled = false)
	{
		$objOption = new Option();
		$objOption->setText($sText);
		$objOption->setValue($sValue);
		$objOption->setDisabled($bDisabled);        
		$this->addNode($objOption);
	}

    /**
     * set Option() with $sHTMLOptionValue as selected="true"
     * It looks at child nodes and sets the child node as selected that 
     * has the value $sHTMLOptionValue assigned
     * 
     * FROR EXAMPLE
     * in this scenario:
     * <select name="selFirstName">
     *  <option value="1">john</optio>
     *  <option value="2">harry</option>
     *  <option value="3">mary</option>
     *  <option value="4">stefany</option>
     * </select>
     * 
     * when calling $objSelect->setSelectedOption(3)
     * this function will set Mary as selected
     * <select name="selFirstName">
     *  <option value="1">john</optio>
     *  <option value="2">harry</option>
     *  <option value="3" selected="true">mary</option>
     *  <option value="4">stefany</option>
     * </select>
     * 
     * 
     * @param string $sHTMLOptionValue
     * @param bool found yes or no
     */
    public function setSelectedOption($sHTMLOptionValue)
    {
        $objNode = null;
        $iCountNodes = 0;
        $iCountNodes = $this->countNodes();
    
        //going through all nodes in search of the Option node with value == $sHTMLOptionValue
        for ($iIndex = 0; $iIndex < $iCountNodes; $iIndex++) 
        {
            $objNode = $this->getNode($iIndex);
            if ($objNode instanceof Option)
            {
                //found node with right value?
                if ($objNode->getValue() == $sHTMLOptionValue)
                {
                    $objNode->setSelected(true); 
                    return true;                          
                }
                else //else don't select
                {
                    $objNode->setSelected(false);
                }
            }
        }

        return false;
    }


    /**
     * wrapper for setSelectedOption() to be consistent with all the other html elements
     */
    public function setValue($sValue)
    {
        return $this->setSelectedOption($sValue);
    }


// 	public function getXMLNodeSpecificToNode_OLD(DOMElement $objXMLElement)
// 	{
// 	}

	public function renderHTMLNodeSpecific()
	{
		$sAttributes = '';
		 
		$sAttributes .= $this->addAttributeToHTML('onchange', $this->getOnchange());

		return $sAttributes;
	}      
}


?>