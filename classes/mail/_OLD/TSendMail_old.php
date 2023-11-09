<?php

namespace dr\classes\mail;

use dr\classes\mime\TMimeMessage;
use dr\classes\mime\TMimePart;
use dr\classes\mime\TMime;

/**
 * Description of TSendMail
 * 
 * Voor het versturen van email.
 * Er wordt gefilterd op header injection
 * 
 * @TODO: als in chuncks opgedeeld (vereiste in email protocol) gebeurd dit niet midden in een html tag??
 *
 * 
 * 13 feb 2013: TSendMail: created. can send plain and html emails
 * 14 feb 2013: TSendMail: strengere filtering headers. de header directions worden volledig gefilterd
 * 14 feb 2013: TSendMail: het return-path wordt in de headers automatisch ingevuld. dit voorkomt dat free-mail accounts het bericht als spam zien
 * 14 feb 2013: TSendMail: als http_user_agent leeg is dan wordt er geen email verstuurd
 * 14 feb 2013: TSendMail: flood protection (30 seconden)
 * 1 mrt 2014: TSendMail: extra safe formatAdress()
 * 1 mrt 2014: TSendMail: flattenBracketsArray() added
 * 1 mrt 2014: TSendMail: added List-Unsubscribe header functionality to prevent email seen as spam by mail clients.
 * 1 mrt 2014: TSendMail: changed behaviour of the flood detection (when cookies disabled no flood detection) and if function returns TRUE = flood
 * 
 * 
 * 14 dec 2020: TSendmail: vervangen door wrapper PHPMailer
 * 
 * 
 * 
 * 
 * @todo rekening houden dat er niet midden in een html tag geknipt wordt -->tmimemessage->transformStringIntoLines70Chars()
 * 
 * 
 * 
 * 
 * @author drenirie
 */
class TSendMail_OLD extends TMailMessage
{
    const MESSAGE_ID = 'Message-ID';
    const FROM = 'From';
    const REPLY_TO = 'Reply-To';
    const RETURN_PATH = 'Return-Path';
    const CC = 'CC';
    const BCC = 'BCC';
    const LISTUNSUBSCRIBE = 'List-Unsubscribe';
    const EOL = "\r\n";
    
    private $sSESSIONVariableNameFloodProtectionTo = 'TSendMail_floodprotection_lastemail-to'; //de $_SESSION[] variabele waarin opgeslagen wordt naar wie de laatste email is verstuurd
    private $sSESSIONVariableNameFloodProtectionTimestamp = 'TSendMail_floodprotection_lastemail-timestamp'; //de $_SESSION[] variabele waarin opgeslagen wordt naar wanneer de laatste email is verstuurd
    
    public function  __construct()
    {
       $this->setFrom('noreply@'.getDomain());//default
       $this->setMessageID($this->generateMessageID());
       parent::__construct();
    }

    public function __destruct()
    {
       parent::__destruct();
    }
    

    /**
     * sets the senders email address
     * 
     * @param string $sEmailAddress
     * @param string $sName
     */
    public function setFrom($sEmailAddress, $sName = '')
    {
        parent::setFrom($this->getSafeEmailAddress($sEmailAddress), $this->getSafeName($sName));
    }

    /**
     * adds a receipient
     * 
     * @param string $sEmailAddress
     * @param string $sName
     */
    public function addTo($sEmailAddress, $sName)
    {
        parent::addTo($this->getSafeEmailAddress($sEmailAddress), $this->getSafeName($sName));
    }

    /**
     * adds a Carbon Copy receipient
     * 
     * @param string $sEmailAddress
     * @param string $sName
     */
    public function addCC($sEmailAddress, $sName = '')
    {
        parent::addCC($this->getSafeEmailAddress($sEmailAddress), $this->getSafeName($sName));
    }

    /**
     * adds a Blank Carbon Copy receipient
     * 
     * @param string $sEmailAddress
     * @param string $sName
     */
    public function addBCC($sEmailAddress, $sName = '')
    {
        parent::addBCC($this->getSafeEmailAddress($sEmailAddress), $this->getSafeName($sName));
    }

    /**
     * sets the reply-to emailaddress
     * 
     * @param string $sEmailAddress
     * @param string $sName
     */
    public function setReplyTo($sEmailAddress, $sName = '')
    {
        parent::setReplyTo($this->getSafeEmailAddress($sEmailAddress), $this->getSafeName($sName));
    }

    /**
     * set email subject
     * 
     * @param type $sSubject
     */
    public function setSubject($sSubject)
    {
        parent::setSubject($this->getSafeHeaderField($sSubject));
    }
    

    /**
     * send the email
     * utf-8 encoding
     * 
     * @return bool send mail correctly?
     */
    public function send()
    {         
        //flatten receipient array:
        $sTo = $this->flattenReceipientArray($this->getTo());
        
        //header maken
        $sHeader = $this->transformStringIntoLines70Chars($this->parseHeader());
        
        //subject maken
        $sSubject = $this->getSubject(); //unencoded
        if ($this->getMimeMessage()->getContentTransferEncoding() == TMime::ENCODING_BASE64)
            $sSubject = TMime::encodeBase64Header($this->getSubject(), $this->getCharset());
        if ($this->getMimeMessage()->getContentTransferEncoding() == TMime::ENCODING_QUOTEDPRINTABLE)
            $sSubject = TMime::encodeQuotedPrintable($this->getSubject(), $this->getCharset());
        $sSubject = $this->transformStringIntoLines70Chars($sSubject);

        //body maken
        $sBody = $this->transformStringIntoLines70Chars($this->getMimeMessage()->parseMessage(TSendMail::EOL));

// logDev($sBody);        
//logdev($sHeader, 'body lopper');
// tracepoint('flipperop');
        
//        vardump($sHeader);
//        vardump($sBody);
        
        
        //run necessary checks, in order of resource-intensiveness
        if (!$this->checkToField($sTo))
            return false;
        if (!$this->checkUserAgent())
            return false;
        if (!$this->checkHeaderLength($sHeader, $sTo, $sSubject))
            return false;
        if ($this->checkEmailFlood($sTo)) //flood detected ?
        {
            return false;
        }
        


        //mail-commando daadwerkelijk uitvoeren
        return mail($sTo, $sSubject, $sBody, $sHeader);        
        // return mail($sTo, $sSubject, $sBody);        
    }


    /**
     * generates and returns email header
     * 
     * @return string with header
     */
    private function parseHeader()
    {
        //flatten receipient arrays:
        $sCC = $this->flattenReceipientArray($this->getCC());
        $sBCC = $this->flattenReceipientArray($this->getBCC());
        $sListUnsubscribe = $this->flattenBracketsArray($this->getListUnsubscribe());
        
        
        //construct the header, and filter it AGAIN to be sure to avoid injection (maybe a programming error with the setters makes it vulnerable to injection)
        $sHeader = '';
        if ($this->getFrom() != '')
            $sHeader .= TSendMail::FROM.': '.$this->getSafeHeaderField($this->getFrom()).TSendMail::EOL;
        if ($this->getReplyTo() != '')
            $sHeader .= TSendMail::REPLY_TO.': '.$this->getSafeHeaderField($this->getReplyTo()).TSendMail::EOL;
        if ($this->getReplyTo() != '') //return path automatisch invullen, dit voorkomt dat het door hotmail, yahoo aol etc als spam wordt gezien
            $sHeader .= TSendMail::RETURN_PATH.': '.$this->getSafeHeaderField($this->getReplyTo()).TSendMail::EOL;//eerst de Reply-to pakken
        elseif ($this->getFrom() != '')
            $sHeader .= TSendMail::RETURN_PATH.': '.$this->getSafeHeaderField($this->getFrom()).TSendMail::EOL; //daarna pas de From
        if ($sCC != '')
            $sHeader .= TSendMail::CC.': '.$this->getSafeHeaderField($sCC).TSendMail::EOL;
        if ($sBCC != '')
            $sHeader .= TSendMail::BCC.': '.$this->getSafeHeaderField($sBCC).TSendMail::EOL;
        if ($this->getMessageID() != '')
            $sHeader .= TSendMail::MESSAGE_ID.': '.$this->getSafeHeaderField($this->getMessageID()).TSendMail::EOL;
        if ($sListUnsubscribe != '') 
            $sHeader .= TSendMail::LISTUNSUBSCRIBE.': '.$this->getSafeListUnsubscribeField($sListUnsubscribe).TSendMail::EOL; //no safe header field filtering because of 'to' and the colon ':' gets filtered
        
        $sHeader .= $this->getMimeMessage()->getHeaders(TSendMail::EOL); 
        
        //vardump($sHeader);
        
        return $sHeader;
    }
  
    /**
     * a uniform way to flatten a receipient array the email-way (according to email standard)
     * 
     * @param type $arrReceipients
     * @return type
     */
    private function flattenReceipientArray($arrReceipients)
    {
        if (is_array($arrReceipients))
            return implode(', ', $arrReceipients);        
        else
            return '';
    }
    
    /**
     * a uniform way to flatten an array with brackets (<>)
     * (uses flattenReceipientArray()-function)
     * 
     * @param array $arrReceipients
     * @return string
     */
    private function flattenBracketsArray($arrUrls)
    {
        //< in front and > after
        if ($arrUrls)
        {
            for ($iTeller = 0; $iTeller < count($arrUrls); $iTeller++)
            {
                $arrUrls[$iTeller] = '<'.$arrUrls[$iTeller].'>';
            }
        }
        
        return $this->flattenReceipientArray($arrUrls);
    }    
    
    /**
     * returns if header has correct length
     * 
     * @param string $sHeader
     * @param string $sTo
     * @param string $sSubject
     * @return boolean
     */
    private function checkHeaderLength($sHeader, $sTo, $sSubject)
    {
        //check header length
        $iHeaderLength = strlen($sHeader) + strlen($sTo) + strlen($sSubject) + 25; //25 is veiliheidsmarge, vanwege 'to: ' en 'subject: ' die de php mail functie er zelf nog achter plakt
        if ($iHeaderLength >= 998)
        {
            error('de emailheader is te lang (groter dan 998 karakters). kan daarom geen email versturen', $this);
            return false;
        }        
        else
            return true;
    }
    
    /**
     * flood protection , so you can't send emails in 30 seconds from each other to the same emailaddress
     * this function works with cookies, when cookies not enabled this function won't work (it returns FALSE)
     * 
     * @param string $sTo
     * @return boolean false = No flood, true = flood detected: don't send email
     */
    private function checkEmailFlood($sTo)            
    {
        //email flood protection 30 secs           
        if ((isset($_SESSION[$this->sSESSIONVariableNameFloodProtectionTo])) && (isset($_SESSION[$this->sSESSIONVariableNameFloodProtectionTimestamp])))
        {
            if ($_SESSION[$this->sSESSIONVariableNameFloodProtectionTo] == $sTo)
            {
                if (is_numeric($_SESSION[$this->sSESSIONVariableNameFloodProtectionTimestamp]))
                {
                    if (($_SESSION[$this->sSESSIONVariableNameFloodProtectionTimestamp] + 30) > time())
                    {
                        error('flood protection: binnen 30 seconden email geprobeerd te sturen aan dezelfde perso(o)n(en):'.htmlentities($sTo), $this);
                        return true; 
                    }
                }
                else
                {
                    error('flood protection: timestamp niet numeriek, kan daarom geen tijdvergelijking doen, daarom afgekeurd', $this);
                    true;
                }
            }
        }
        
        $_SESSION[$this->sSESSIONVariableNameFloodProtectionTo] = $sTo;
        $_SESSION[$this->sSESSIONVariableNameFloodProtectionTimestamp] = time();

        
        return false;
    }
    
    /**
     * check user agent (HTTP_USER_AGENT) for malicious content
     * 
     * @return boolean true = ok, false = weird useragent
     */
    private function checkUserAgent()
    {
        //check user agent, als leeg dan waarschijnlijk een injection, hack of spam attack
        if (empty($_SERVER['HTTP_USER_AGENT']))
        {
            error('HTTP_USER_AGENT is empty: possible injection, hack or spam attack', $this);
            return false;
        }        
        
        return true;
    }
    
    /**
     * checks if to: field is ok
     * 
     * @param string $sTo
     * @return boolean true=ok, false=not ok
     */
    private function checkToField($sTo)
    {
        //het To: emailadres moet minimaal 5 karakters lang zijn, anders is het geen emailadres        
        if (strlen($sTo) <= 5) 
        {
            error('the "to:"-field is empty. can not send email', $this);
            return false;
        }       
        
        return true;
    }
    
    
    /**
     * returns a random message id
     * 
     * @return string
     */
    private function generateMessageID()
    {
        $time = time();

        if ($this->getFrom() !== null) {
            $user = $this->getFrom();
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $user = $_SERVER['REMOTE_ADDR'];
        } else {
            $user = getmypid();
        }

        $rand = mt_rand();   

        if (isset($_SERVER["SERVER_NAME"])) {
            $hostName = $_SERVER["SERVER_NAME"];
        } else {
            $hostName = php_uname('n');
        }
        
        $sAtHostname = '@' . $hostName;
        $sID = sha1($time . $user . $rand);
        $iLengthHeaderMessageIDLine = strlen(TSendMail::MESSAGE_ID) + 4 + strlen($sID) + strlen ($sAtHostname); //2 vanwege ': ' en 2 extra voor de zekerheid
        
        if (($iLengthHeaderMessageIDLine) >= TMime::LINELENGTH) //voorkomen dat deze langer dan 70 karakters wordt, id inkorten
        {
            $iAllowedLengthGeneratedID = TMime::LINELENGTH - $iLengthHeaderMessageIDLine;
            $sID = substr($sID, 0, $iAllowedLengthGeneratedID); 
        }

        $sReturn = $sID.$sAtHostname;
        
        return $sReturn;
    }

}

?>
