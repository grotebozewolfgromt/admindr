<?php
namespace dr\classes\dom\tag;


use dr\classes\dom\tag\Text;
use dr\classes\dom\tag\form\InputDate;
use dr\classes\dom\tag\form\InputDateTime;
use dr\classes\dom\tag\form\InputDateTimelocal;
use dr\classes\dom\tag\form\InputTime;
use dr\classes\locale\TCountrySettings;

/**
 * parent klasse voor alle HTMLDOM klasses.
 * Heeft onder andere een TObject list in zich voor subnodes
 * 
 * the $bIsArray is implemented here (and not in the form tags) because we need $bIsArray when generating the html node
 * 
 * =============== ATTENTION ==================
 * tags with an EMPTY tagname is not rendered! 
 * But childs of the tag ARE!!!
 * ============================================
 * 
 * 20 apr 2016: TagAbstract: $objSubnodes (TObjectList) replaced by a faster array structure (no extra expensive object allocation anymore)
 * 6 jan 2020: TagAbstract: isArray added
 * 9 jan 2020: TagAbstract: resetNodespointer() added
 * 28 may 2020: TagAbstract: contentEditable added
 * 6 nov 2020: TagAbstract: getNodeByID added
 * 27 nov 202: TagAbstract: removed prefixes
 */
abstract class TagAbstract
{
	private $sStyle = '';
	private $sCSSClass = ''; //the css class
	private $arrSubNodes = array(); //the subnodes of this node
	private $sTagName = '';//name of the tag: bijvoorbeeld: html (voor de <html> -tag)
	private $sName = ''; //bijvoorbeeld: frmContact in  <form name="frmContact">
	private $sID = '';//the id of the tag: edtEmail in <input id="edtEmail>
	private $sOnclick = ''; //javascript: onclick="doeIetsLeuks()";
	private $sOnKeyDown = '';//javascript: onkeydown="doeIetsLeuks()";
    private $bContentEditable = false;
	private $bHasClosingTag = true; //some HTML tags don't have closing tags such as <br> and <img>
	private $bSourceFormattingNewlineAfterOpenTag = false; //enter na open tag?
	private $bSourceFormattingNewLineAfterCloseTag = false; //enter na closing tag?
	private $bSourceFormattingIdentForOpenTag = false; //inspringen voor open tag?
	private $bSourceFormattingIdentForCloseTag = false; //inspringen voor closing tag?
	private $iSubNodesPointer = 0;
	private $iCacheCountSubNodes = 0;
    private $bIsArray = false; //is the html name is an array: <input type="text" name="edtName[]"> .PHP converts the brackets [] after submitting to an array and CHANGES THE HTML NAME to the name without the brackets, so 'edtName[]' becomes 'edtName'. this gives problems when reading the values from the _GET and _POST array

	public function __construct()
	{
        $this->resetNodePointer();
	}

	public function  __destruct()
	{
		unset($this->arrSubNodes);
	}

	public function resetNodePointer()
	{
		$this->iSubNodesPointer = 0;
	}
       
    protected function getHasClosingTag()
	{
		return $this->bHasClosingTag;
	}

	protected function setHasClosingTag($bCloseTag)
	{
		$this->bHasClosingTag = $bCloseTag;
	}

	protected function getSourceFormattingNewLineAfterOpenTag()
	{
		return $this->bSourceFormattingNewlineAfterOpenTag;
	}

	protected function setSourceFormattingNewLineAfterOpenTag($bNewline)
	{
		$this->bSourceFormattingNewlineAfterOpenTag = $bNewline;
	}

	protected function getSourceFormattingNewLineAfterCloseTag()
	{
		return $this->bSourceFormattingNewLineAfterCloseTag;
	}

	protected function setSourceFormattingNewLineAfterCloseTag($bNewline)
	{
		$this->bSourceFormattingNewLineAfterCloseTag = $bNewline;
	}

	protected function getSourceFormattingIdentForOpenTag()
	{
		return $this->bSourceFormattingIdentForOpenTag;
	}

	protected function setSourceFormattingIdentForOpenTag($bIdent)
	{
		$this->bSourceFormattingIdentForOpenTag = $bIdent;
	}

	protected function getSourceFormattingIdentForCloseTag()
	{
		return $this->bSourceFormattingIdentForCloseTag;
	}

	protected function setSourceFormattingIdentForCloseTag($bIdent)
	{
		$this->bSourceFormattingIdentForCloseTag = $bIdent;
	}


	/**
	 * setting the name of the HTML tag
	 * bijvoorbeeld: html (voor de <html> -tag)
	 *
	 * @param string $sName
	 */
	public function setTagName($sName)
	{
		$this->sTagName = $sName;
	}

	/**
	 * getting the name of this HTML tag
	 * bijvoorbeeld: html (voor de <html> -tag)
	 *
	 * @return string
	 */
	public function getTagName()
	{
		return $this->sTagName;
	}

	/**
	 * setting the name for the HTML tag
	 * bijvoorbeeld: frmContact in  <form name="frmContact">
	 *
	 * @param string $sName
	 */
	public function setName($sName)
	{
		$this->sName = $sName;
        $this->bIsArray = false;
	}
        
        
	/**
	 * getting the name of this HTML tag
	 * example: frmContact in  <form name="frmContact">
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->sName;
	}

	/**
	 * setting the name for the HTML tag, but indicates it as an array
         * (only needed in forms)
         * 
         * PHP converts tag names given as names with brackets to an array after 
         * form submission, this results in problems reading the _GET and _POST 
         * array, because it reads 'edtName[]' (which not exists) instead of 'edtName'
         * 
	 * example: edtFirstName in  <input type="text" name="edtFirstName[]">
         * 
        * @param string $sName name of the html tag WITHOUT BRACKETS, so setName('edtFirstName'), this will render as 'edtFirstName[]'
	 */        
	public function setNameArray($sName)
	{
		$this->setName($sName);                                
                $this->bIsArray = true;
	}        
        
    /**
	* setting the name for the HTML tag as an array
	* (only needed in forms)
	* 
	* PHP converts tag names given as names with brackets to an array after 
	* form submission, this results in problems reading the _GET and _POST 
	* array, because it reads 'edtName[]' (which not exists) instead of 'edtName'
	* 
	* example: edtFirstName in  <input type="text" name="edtFirstName[]">
	* 
	* @param bool $bArray
	*/
	public function setIsArray($bArray)
	{
		$this->bIsArray = $bArray;
	}
        
    /**
	* getting the if the element is an array
	* (only needed in forms)
	* 
	* PHP converts tag names given as names with brackets to an array after 
	* form submission, this results in problems reading the _GET and _POST 
	* array, because it reads 'edtName[]' (which not exists) instead of 'edtName'
	* 
	* example: edtFirstName in  <input type="text" name="edtFirstName[]">
	*
	* @return bool
	*/
	public function getIsArray()
	{
			return $this->bIsArray;
	}

	/**
	 * setting the name for the HTML form tag.
	 * example: frmContact in  <form name="frmContact">
     * 
	 *
	 * @param string $sName name of the html tag
     * @param boolean $bSetAlsoIDWithName this function sets also setID($sName) if true
	 */
	public function setNameAndID($sName)
	{
		$this->setName($sName);                
        $this->setID($sName);               
	}        
        
	/**
	 * setting the javascript onclick property for the HTML tag
	 * bijvoorbeeld:  <input type="button" onclick="doeIets()">
	 *
	 * @param string $sOnclickEvent
	 */
	public function setOnclick($sOnclickEvent)
	{
		$this->sOnclick = $sOnclickEvent;
	}

	/**
	 * getting the javascript onclick property of this HTML tag
	 * bijvoorbeeld: <input type="button" onclick="doeIets()">
	 *
	 * @return string
	 */
	public function getOnclick()
	{
		return $this->sOnclick;
	}

	/**
	 * setting the javascript onkeydown property for the HTML tag
	 * bijvoorbeeld:  <input type="button" onkeydown="doeIets()">
	 *
	 * @param string $sOnkeydownEvent
	 */
	public function setOnkeydown($sOnkeydownEvent)
	{
		$this->sOnKeyDown = $sOnkeydownEvent;
	}
	
	/**
	 * getting the javascript onclick property of this HTML tag
	 * bijvoorbeeld: <input type="button" onkeydown="doeIets()">
	 *
	 * @return string
	 */
	public function getOnkeydown()
	{
		return $this->sOnKeyDown;
	}
        
	/**
	 * <p contenteditable="true">This is an editable paragraph.</p>
	 * @param bool $bEditable 
	 */
	public function setContentEditable($sEditable)
	{
			
			$this->bContentEditable = (bool)$sEditable;
	}
        
	/**
	 * is content editable?
	 * <p contenteditable="true">This is an editable paragraph.</p>
	 * @return bool
	 */
	public function getContentEditable()
	{
			return $this->bContentEditable;
	}
        

    

	/**
	 * @deprecated
	 * 
	 * getting the prefix for an element/tagname such as
	 * 'frm' in frmLogin (form) or 'edt' in edtUsername for an input type=text box
	 * @return string
	 * 
	 */
	public function getNamePrefix()
	{
		return '';
	}

	/**
	 * setting the id for the HTML tag
	 * bijvoorbeeld: edtEmail in <input id="edtEmail>
	 *
	 * @param string $sName
	 */
	public function setID($sID)
	{
		$this->sID = $sID;
	}

	/**
	 * getting the id of this HTML tag
	 * bijvoorbeeld: edtEmail in <input id="edtEmail>
	 *
	 * @return string
	 */
	public function getID()
	{
		return $this->sID;
	}

	/**
	 * add a node to this node
	 *
	 * @param THTMLTr $objRow
	 */
	public function addNode(TagAbstract &$objSubNode)
	{
		$this->arrSubNodes[] = $objSubNode;
		++$this->iCacheCountSubNodes;
	}


	/**
	 * aliast for  addTextNode
	 *
	 * @param type $sText
	 */
	public function addText($sText, $bConvertToHTMLSpecialChars = true)
	{
		$this->addTextNode($sText, $bConvertToHTMLSpecialChars);
	}

	public function addTextNode($sText, $bConvertToHTMLSpecialChars = true)
	{
		$objText = new Text();
		$objText->setText ( $sText, $bConvertToHTMLSpecialChars );
		$this->addNode ( $objText );
	}
	
	/**
	 * this function adds clones of all internal nodes from a
	 * custom_ABSTRACT object to this object
	 * 
	 * @param custom_ABSTRACT $objCustom        	
	 */
// 	public function addCustomClones(custom_ABSTRACT $objCustom) {
// 		for($iTeller = 0; $iTeller < $objCustom->count (); $iTeller ++) {
// 			$objNodeFromCustom = $objCustom->get ( $iTeller );
// 			$objNodeFromCustomClone = clone $objNodeFromCustom;
// 			$this->addNode ( $objNodeFromCustomClone );
// 		}
// 	}
	
	/**
	 * get the node at index $iIndex
	 *
	 * @param int $iIndex        	
	 * @return TagAbstract
	 */
	public function getNode($iIndex) 
    {
		return $this->arrSubNodes[$iIndex];
	}
	
	/**
	 * return element with id $sHTMLElementID
	 * returns null if not found
	 * the php version of getElementById()
	 * 
	 * this function is recursive
	 */
	public function getNodeByID($sHTMLElementID)
	{
		$objTempNode = null;
		tracepoint('volendam:'. $sHTMLElementID)			;
		vardump($this->arrSubNodes);
		foreach ($this->arrSubNodes as $objNode)
		{
			if ($objNode->getID() == $sHTMLElementID)
			{
				return $objNode;
			}
			else //if not find dive deeper
			{
				$objTempNode = $objNode->getNodeByID($sHTMLElementID);
				if ($objTempNode)
					return $objTempNode;
			}
		}

		return null;
	}

	/**
	 * get next node
	 * (so you can easily use it in a while loop)
	 * 
	 * returns false if THE END IS NEAR!
	 * @return TagAbstract
	 */
	public function getNodeNext()
	{		
		if ($this->iSubNodesPointer < $this->iCacheCountSubNodes)
		{
			$objReturn = null;
			$objReturn = $this->arrSubNodes[$this->iSubNodesPointer];
			++$this->iSubNodesPointer;
			return $objReturn;
		}
		else
		{
			$this->iSubNodesPointer = 0;
			return false;
		}
	}
	
	/**
	 * count the number of subnodes
	 *
	 * @return int number of nodes
	 *        
	 */
	public function countNodes() 
        {
		return $this->iCacheCountSubNodes;
	}
	
	/**
	 * setting the style
	 *
	 * @param string $sStyle        	
	 */
	public function setStyle($sStyle) 
        {
		$this->sStyle = $sStyle;
	}
	
	/**
	 * getting the style of the component
	 *
	 * @return string  
	 */
	public function getStyle() 
        {
		return $this->sStyle;
	}
	
	/**
	 * set the CSS class
	 * 
	 * @param string $sClass        	
	 */
	public function setClass($sClass) 
        {
		$this->sCSSClass = $sClass;
	}
	
	/**
	 * get the CSS class
	 * 
	 * @return string
	 */
	public function getClass() 
        {
		return $this->sCSSClass;
	}
	

	
	/**
	 * OLD METHOD WITH DOMDocument
	 * 
	 * @deprecated since 26 sept 2012
	 *             renders the XML/HTML document and returns it in a string
	 * @return string
	 */
	public function render_OLD() {
		try {
			$sReturn = '';
			$objXMLDoc = new DOMDocument ();
			$objXMLDoc->encoding = 'utf-8';
			$objXMLDoc->formatOutput = true;
			$objXMLDoc->appendChild ( $this->getXMLNode_OLD ( $objXMLDoc ) );
			
			// adding to the head of the html page:
			if (get_class ( $this ) == 'html')
				$sReturn .= '<!DOCTYPE html>'; // '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
			
			$sReturn .= $objXMLDoc->saveHTML ();
			
			return $sReturn;
		} catch ( Exception $objException ) {
			$this->error ( $objException );
			return '';
		}
	}
	
	/**
	 * het maken van html code voor deze node
	 *
	 * @param int $iLevel
	 *        	leveldepth of the node (for formatting the html code)
	 * @return string html code van deze node
	 */
	public function renderHTMLNode($iLevel = 0) 
    {
        global $objCountrySettings;
		$sHTML = '';                
                		
		// opening tag
		if ($this->getTagName () != '') 
        {
			// ident
			if ($this->getSourceFormattingIdentForOpenTag ())
				for($iIdentCounter = 0; $iIdentCounter < $iLevel; $iIdentCounter ++)
					$sHTML .= "\t";
				
			//open
			$sHTML .= '<' . htmlspecialchars ( $this->getTagName () );
			
			//go through attributes (there dont exist attributes without tagname)
			$sHTML .= $this->addAttributeToHTML ( 'class', $this->getClass () ); // CSS class toevoegen
			$sHTML .= $this->addAttributeToHTML ( 'onclick', $this->getOnclick () ); // onclick toevoegen
			$sHTML .= $this->addAttributeToHTML ( 'onkeydown', $this->getOnkeydown() ); // onkeydown toevoegen
                        if ($this->getContentEditable())
                                $sHTML .= $this->addAttributeToHTML ( 'contenteditable', 'true' ); // contenteditable toevoegen
			$sHTML .= $this->addAttributeToHTML ( 'style', $this->getStyle() ); // style
			
			if (strlen ( $this->getID () ) > 0) // door de prefixen kan de addAttribute() niet goed detecteren of de id leeg is
				$sHTML .= $this->addAttributeToHTML ( 'id', $this->getNamePrefix () . $this->getID () ); // id toevoegen
			if (strlen ( $this->getName () ) > 0) // door de prefixen kan de addAttribute() niet goed detecteren of de naam leeg is
			{
				if ($this->bIsArray)
					$sHTML .= $this->addAttributeToHTML ( 'name', $this->getNamePrefix () . $this->getName ().'[]' ); // name toevoegen
				else
					$sHTML .= $this->addAttributeToHTML ( 'name', $this->getNamePrefix () . $this->getName () ); // name toevoegen
			}
					                                                                                     
			// disabled toevoegen--> 3 mrt 2014 verwijderd, ik zag dat FORMINPUT->renderHTMLNodeSpecific() dit ook al doet
					                                                                                     // if (method_exists($this, 'getDisabled'))
					                                                                                     // {
					                                                                                     // if ( $this->getDisabled() )
					                                                                                     // $sHTML.= $this->addAttributeToHTML('disabled', 'yes');
					                                                                                     // }
					                                                                                     
			// readonly toevoegen--> 3 mrt 2014 verwijderd, ik zag dat FORMINPUT->renderHTMLNodeSpecific() dit ook al doet
					                                                                                     // if (method_exists($this, 'getReadOnly'))
					                                                                                     // {
					                                                                                     // if ( $this->getReadOnly() )
					                                                                                     // $sHTML.= $this->addAttributeToHTML('readonly', 'yes');
					                                                                                     // }
					                                                                                     
			// add specific elements
			$sHTML .= $this->renderHTMLNodeSpecific ();
			
			// sluiten
			$sHTML .= '>';
			
			if ($this->getSourceFormattingNewLineAfterOpenTag ())
                                $sHTML .= "\n";
                        
                        
                        
                        
                        
		}
		
		// loop child tags
		for($iChildCounter = 0; $iChildCounter < $this->countNodes (); $iChildCounter ++) 
        {
			$objChild = $this->getNode ( $iChildCounter );
			$sHTML .= $objChild->renderHTMLNode ( $iLevel + 1 );
		}
		
		// closing tag
		if (($this->getTagName () != '') && ($this->getHasClosingTag ())) 
        {
			// ident
			if ($this->getSourceFormattingIdentForCloseTag ())
				for($iIdentCounter = 0; $iIdentCounter < $iLevel; $iIdentCounter ++)
					$sHTML .= "\t";
			
			$sHTML .= '</' . htmlspecialchars ( $this->getTagName () ) . '>';
			
			if ($this->getSourceFormattingNewLineAfterCloseTag ())
				$sHTML .= "\n";
		}
		
                //=====================================================================================                
                //          (JQuery) user interface shissle
                //          attach datetimepickers/timepickers/colorpickers etc                 
                //=====================================================================================
                
                //attach jQuery date picker
                if ($this instanceof InputDate)
                {   
                        $sDateFormat = '';
                        $sDateFormat = $this->getPHPDateFormat();

                        if ($sDateFormat == '')
                        {
                                $sDateFormat = $objCountrySettings->getCountrySetting(TCountrySettings::DATEFORMAT_DEFAULT);
                        }

                        
                        $sHTML.= '<script>'."\n";
                        $sHTML.= '                                    
                                    $( function() 
                                    {
                                        $( "#'.$this->getID().'" ).datepicker(
                                        {
                                            dateFormat: \''.dateformatPHPtojQueryUI($sDateFormat).'\',
                                            changeMonth: true,
                                            changeYear: true
                                        });
                                        
                                    } );
                                 ';
                        $sHTML.= '</script>'."\n";
                        
                }
                
                //attach jQuery time spinner
                /*
                if ($this instanceof InputTime)
                {
                        $sDateFormat = '';
                        $sDateFormat = $this->getPHPDateFormat();

                        if ($sDateFormat == '')
                        {
                                $sDateFormat = $objCountrySettings->getCountrySetting(TCountrySettings::TIMEFORMAT_DEFAULT);
                        }

                        
                        $sHTML.= '<script>'."\n";
                        $sHTML.= '                                    
                                    $.widget( "ui.timespinner", $.ui.spinner, {
                                        options: {
                                          // seconds
                                          step: 60 * 1000,
                                          // hours
                                          page: 60
                                        },

                                        _parse: function( value ) {
                                          if ( typeof value === "string" ) {
                                            // already a timestamp
                                            if ( Number( value ) == value ) {
                                              return Number( value );
                                            }
                                            return +Globalize.parseDate( value );
                                          }
                                          return value;
                                        },

                                        _format: function( value ) {
                                          return Globalize.format( new Date(value), "t" );
                                        }
                                    });

                                    Globalize.culture("de-DE");
                                    $("#'.$this->getID().'").timespinner();
                                    ';
                        $sHTML.= '</script>'."\n";                    
                }
                */
                
                
		return $sHTML;
	}
	
	/**
	 * displays the rendered result of the render() function
	 */
	public function display() 
    {
		echo $this->renderHTMLNode ();
	}
	
	/**
	 * save rendered result to file
	 * 
	 * @param string $sFileName
	 *        	path of the file
	 * @return bool file save success ?
	 */
	public function saveToFile($sFileName) 
    {
		$sRendered = $this->renderHTMLNode ();
		return saveToFileString ( $sRendered, $sFileName );
	}
	
	/**
	 * internal function for easy and html-safe adding attributes to HTML string
	 * it checks if the attribute name is not empty (if empty, it will not add)
	 * 
	 * @param string $sAttributeName        	
	 * @param string $sAttributeValue        	
	 * @param boolean $bRequiredAttribute
	 *        	if true the attribute will allways be added (regardless if the value is empty)
	 * @return string
	 */
	protected function addAttributeToHTML($sAttributeName, $sAttributeValue, $bRequiredAttribute = false)
    {
		if (! $bRequiredAttribute) {
			if (strlen ( $sAttributeValue ) > 0)
				return ' ' . htmlspecialchars ( $sAttributeName ) . '="' . htmlspecialchars ( $sAttributeValue ) . '"';
			else
				return '';
		} else
			return ' ' . htmlspecialchars ( $sAttributeName ) . '="' . htmlspecialchars ( $sAttributeValue ) . '"';
	}
	
	/**
	 * OLD VERSION WITH DOMElement class
	 * 
	 * @deprecated since 26 sept 2012
	 *            
	 *             this function is called in getXMLNode() to add specific tags or properties to the node
	 *            
	 * @param DOMElement $objXMLElement        	
	 */
// 	abstract public function getXMLNodeSpecificToNode_OLD(DOMElement $objXMLElement);
	
	/**
	 * this function is called by renderHTMLNode() for rendering specific html code inside the tag
	 * (mostly adding attributes)
	 *
	 * @return string with html code in node
	 */
	abstract public function renderHTMLNodeSpecific();
}


?>