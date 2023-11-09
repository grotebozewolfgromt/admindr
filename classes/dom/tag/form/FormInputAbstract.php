<?php
namespace dr\classes\dom\tag\form;

use dr\classes\patterns\TObjectList;
use dr\classes\dom\tag\TagAbstract;
use dr\classes\dom\validator\ValidatorAbstract;
use dr\classes\dom\validator\FormInputContents;
/**
 * parent klasse voor alle formulier input
 * deze klasse verkrijgt de waardes die gesubmit zijn en kan deze waardes
 * controleren
 *
 * Deze klasse leest de waardes uit de $_POST of $_GET array bij de setName()
 * procedure (dan pas weet je hoe deze variabele heet en dan pas kun je 'm voor
 * het eerst uitlezen)
 * 
 * 29 apr 2015 FORMINPUT allow html toegevoegd
 * 8 juli 2015: FORINPUT bugfix in getContentsSubmitted() vergelijk post method
 * 24 apr 2016: FormInputAbstract autofocus toegevoegd
 * 2 mei 2019: setName() heeft extra parameter waarmee tegelijk ook de id geset kan worden
 * 23 jun 2021: getContentsSubmitted() doesn't use prefixes anymore
 */
abstract class FormInputAbstract extends TagAbstract
{
	protected $objContentsInit = null; //FormInputContents
	protected $objContentsSubmitted = null; //FormInputContents
	private $objValidators = null;//TObjectlist
	private $bDisabled = false;
	private $bReadOnly = false;
	private $bRequired = false; //in html elements you can specify a 'required'. Not every browser supports this!
	private $bShowValuesOnReloadForm = true; //voor bijvoorbeeld wachtwoorden is het niet wenselijk om deze bij een reload van het formulier weer te geven
	private $iAllowHTML = FILTERXSS_ALLOW_HTML_NONE; //default no HTML allowed
	private $bAutoFocus = false;
        
	public function __construct()
	{
		parent::__construct();
		$this->objContentsInit = new FormInputContents($this);
		$this->objContentsSubmitted = new FormInputContents($this);
		$this->objValidators = new TObjectList();
	}

	public function  __destruct()
	{
		unset($this->objContentsInit);
		unset($this->objContentsSubmitted);
		unset($this->objValidators);
	}
        
        
	/**
	 * when form loads the field will be automatically focussed
	 * 
	 * @param boolean $bAutoFocus
	 */
	public function setAutofocus($bAutoFocus = true)
	{
		$this->bAutoFocus = $bAutoFocus;
	}
	
	/**
	 * when form loads the field will be automatically focussed
	 *
	 * @param boolean $bAutoFocus
	 */	
	public function getAutofocus()
	{
		return $this->bAutoFocus;
	}
	
	/**
	 * set if field is allowed to contain html code
	 * (for preventing cross site scripting)
	 * 
	 * @param int $iAllowHTML  i.e. FILTERXSS_ALLOW_HTML_NONE 
	 */
	public function setAllowHTML($iAllowHTML = FILTERXSS_ALLOW_HTML_NONE)
	{
		$this->iAllowHTML = $iAllowHTML;
	}
	
	
	/**
	 * get if field is allowed to contain html code
	 * (for preventing cross site scripting)
	 *
	 * @return int i.e. FILTERXSS_ALLOW_HTML_NONE 
	 */	
	public function getAllowHTML()
	{
		return $this->iAllowHTML;
	}
	
	/**
	 * set if values are displayed on reload form
	 * (voor wachtwoorden is het bijvoorbeeld onwenselijk om deze opnieuw weer te geven omdat ze dat plat in de html tekst staan)
	 *
	 * @param boolean $bDontShow
	 */
	public function setShowValuesOnReloadForm($bShowValues)
	{
		$this->bShowValuesOnReloadForm = $bShowValues;
	}

	/**
	 * get if values are displayed on reload form
	 * (voor wachtwoorden is het bijvoorbeeld onwenselijk om deze opnieuw weer te geven omdat ze dat plat in de html tekst staan)
	 *
	 * @return boolean
	 */
	public function getShowValuesOnReloadForm()
	{
		return $this->bShowValuesOnReloadForm;
	}

                

	/**
	 * getting the contents object
	 * this object contains the initial values form the form
     * 
	 * @return FormInputContents
	 */
	public function getContentsInit()
	{
		return $this->objContentsInit;
	}

	/**
	 * getting the contents object
	 * this object contains the values when the form is submitted
	 *
	 * @param $sFormMethod read the $_POST or $_GET array? use constant Form::METHOD_POST
	 * @return FormInputContents
	 */
	public function getContentsSubmitted($sFormMethod = Form::METHOD_POST)
	{

		//read the values from the proper array
		if ($sFormMethod == Form::METHOD_POST)
		{
			if (isset($_POST[$this->getName()]))  //only when exists at all
				$this->objContentsSubmitted->setValue(filterXSS($_POST[$this->getName()]), $this->getAllowHTML());

			if (isset($_FILES[$this->getName()])) //only when exists at all
				$this->objContentsSubmitted->setFileArray($_FILES[$this->getName()]);
		}
		elseif ($sFormMethod == Form::METHOD_GET)
		{
			if (isset($_GET[$this->getName()]))
				$this->objContentsSubmitted->setValue(filterXSS($_GET[$this->getName()]), $this->getAllowHTML());
//			if (isset($_GET[$this->getName()]))
//				$this->objContentsSubmitted->setValue($_GET[$this->getName()]);
		}

		return $this->objContentsSubmitted;
	}


	/**
	 * add a formvalidator object to this element.
	 * When submitting the form, the form generator (FormGenerator)
	 * checks the content of the input value by requesting the added validators.
	 * 
	 * bare in mind that standard no html is allowed
	 *
	 * @param ValidatorAbstract $objValidator validator object
	 */
	public function addValidator(ValidatorAbstract $objValidator)
	{
		$this->objValidators->add($objValidator);
	}

	/**
	 * return the number of added validators
	 *
	 * @return int
	 */
	public function countValidators()
	{
		return $this->objValidators->count();
	}

	/**
	 * requesting a validator by supplying the index of the added validator
	 *
	 * @param int $iValidatorIndex index of the validator
	 * @return ValidatorAbstract
	 */
	public function getValidator($iValidatorIndex)
	{
		return $this->objValidators->get($iValidatorIndex);
	}

	/**
	 * set object disabled (or not)
	 *
	 * @param bool $bDisabled
	 */
	public function setDisabled($bDisabled)
	{
		if (is_bool($bDisabled))
		{
			$this->bDisabled = $bDisabled;
		}
	}

	/**
	 * is object disabled ?
	 *
	 * @return bool
	 */
	public function getDisabled()
	{
		return $this->bDisabled;
	}

	/**
	 * set object read only (or not)
	 *
	 * @param bool $bReadOnly
	 */
	public function setReadOnly($bReadOnly)
	{
		if (is_bool($bReadOnly))
		{
			$this->bReadOnly = $bReadOnly;
		}
	}

	/**
	 * is object read only
	 *
	 * @return bool
	 */
	public function getReadOnly()
	{
		return $this->bReadOnly;
	}

	/**
	 * setting the required property for the HTML tag
	 * bijvoorbeeld:  <input type="text" required="required">
	 *
	 * @param boolean $bRequired
	 */
	public function setRequired($bRequired)
	{
		$this->bRequired = $bRequired;
	}

	/**
	 * getting the required property of this HTML tag
	 * bijvoorbeeld: <input type="text" required="required">
	 *
	 * @return bool
	 */
	public function getRequired()
	{
		return $this->bRequired;
	}

	/**
	 * specific attributes for this element
	 * @param DOMElement $objXMLElement
	 */
// 	public function getXMLNodeSpecificToNode_OLD(DOMElement $objXMLElement)
// 	{
// 		//required toevoegen
// 		if ($this->getRequired() == true)
// 			$objXMLElement->setAttribute('required', 'required');

// 		//readonly toevoegen
// 		if ($this->getRequired() == true)
// 			$objXMLElement->setAttribute('readonly', 'readonly');

// 		//disabled toevoegen
// 		if ($this->getRequired() == true)
// 			$objXMLElement->setAttribute('disabled', 'disabled');


// 	}


	public function renderHTMLNodeSpecific()
	{
		$sAttributes = '';

		//required toevoegen
		if ($this->getRequired())
			$sAttributes .= $this->addAttributeToHTML('required', 'required');

		//readonly toevoegen
		if ($this->getReadOnly())
			$sAttributes .= $this->addAttributeToHTML('readonly', 'readonly');

		//disabled toevoegen
		if ($this->getDisabled())
			$sAttributes .= $this->addAttributeToHTML('disabled', 'disabled');
		
		//autofocus toevoegen
		if ($this->getAutofocus())
			$sAttributes .= $this->addAttributeToHTML('autofocus', 'true');
					
		 

		return $sAttributes;
	}
}


?>