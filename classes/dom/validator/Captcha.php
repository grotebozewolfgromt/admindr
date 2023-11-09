<?php
namespace dr\classes\dom\validator;

use dr\classes\dom\tag\form\FormInputAbstract;

/**
 * voor het valideren van een captcha (een afbeelding waarvan de gebruiker de
 * letters/cijfers over moet typen, zodat je zeker weet dat het geen geautomatiseerde
 * invoer is)
 */
class Captcha extends ValidatorAbstract
{
	private $objCaptcha = null;

	public function __construct($sErrorMessage, Captcha $objCaptcha)
	{
		$this->setCaptchaObject($objCaptcha);
		parent::__construct($sErrorMessage);
	}

	public function setCaptchaObject(Captcha $objCaptcha)
	{
		$this->objCaptcha = $objCaptcha;
	}

	public function isValid(FormInputAbstract $objUploadedFile)
	{
		try
		{
			if ($this->objCaptcha != null)
			{
				$objCaptcha = new TCaptcha();
				return $objCaptcha->isValid($objUploadedFile->getContentsSubmitted()->getValue());
			}
			else
				throw new Exception('isValid(): objCaptcha is null, heb je wel een captcha object geset met setCaptchaObject() ?');
		}
		catch (Exception $objEx)
		{
			error($objException, $objEx);
		}

	}

	public function filterValue(FormInputAbstract $objUploadedFile)
	{
		//
	}
}
?>