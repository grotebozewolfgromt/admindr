<?php
/**
 * A multi purpose controller for 1 page with integrated caching.
 * - It supports caching in files and session!
 * - It supports partial caching with late binding variables
 * This controller used for websites and some pages in the cms
 * 
 * TROUBLESHOOTING:
 * -no output to screen? 
 *      -Did you overload the __construct()?
 *          -don't forget to use parent::__construct(); in the __construct()
 * 
 * 
 * 
 * for performance reasons, existence of files and directories aren't checked and 
 * therefore would give a direct php error
 * 
 **** CACHING:
 * if you want to enable caching, you NEED to overload:
 *   1) getCacheFilePath() and return NOT ''
 *   2) getCacheTimeOutSeconds() and return > 0
 * IF ONE OR BOTH CONDITIONS ARE NOT MET, CACHING IS  D I S A B L E D !!!
 * 
 **** CACHETYPE:
 * You can cache in a file or in a session, caching to file is default.
 * 
 * FILE:    Caching in a file is the preferred option for pages that are the same for all users.
 *          For example, a blog post.
 * SESSION: But when pages are dynamically created for specific users or authorisation is required
 *          for specific to users, use session caching.
 *          For example, a application menu in a CMS.
 * If you want to cache in a session, you need to overload:
 *   getCacheLocation() and return TControllerAbstract::CACHETYPE_SESSION
 * 
 * 
 **** CACHE FILE & CACHE IDENTIFIER:
 * for caching an identifier is used.
 * This is often a url slug or numerical id retrieved from a url, 
 * i.e. in a blog post on www.example.com\articles\how-to-feed-your-dog 
 * the identifier is "how-to-feed-your-dog".
 * The identifier is added to the cache file
 * 
 * 
 **** PARTIAL CACHING with LATE BINDING and EARLY BINDING VARIABLES:
 * In some situations you don't want to cache an entire webpage, but only part(s) of the page.
 * This way you can add dynamicly generated parts in a cached page.
 * For example: 
 *          - a server-side generated countdown timer in an otherwise static page
 *          - loading only certain javascript files according to cookie settings (GDPR settings)
 *          - in a business name generator: You want to cache the page, but not the business names themselves because they are generated
 * 
 * EARLY BINDING variables are cached in cachefiles (and sessions)
 *              These variables are the regular php variables (like $sMyString) in templates.
 *              These variables are resolved by php (before result is cached), 
 *              and thus the result is cached
 * LATE BINDING variables are NOT CACHED!!
 *              These variables are variables in templates in the format: [mystring] 
 *              These variables are replaced "live" when rendering a template (whether it is cached or not)
 *              
 * 
 * @author drenirie
 * created dec 4th 2021
 * 6 dec 2021: verbeteringen
 * 7 dec 2021: werkt volledig 
 * 10 dec 2021: caching in session toegevoegd
 * 5 mrt 2022: TControllerAbstract: partial caching now possible with late binding variables
 * 5 mrt 2022: TControllerAbstract: bugfixes: partial caching
 * 11 mrt 2022: TControllerAbstract: renaming executeOnCacheMiss -> executeEarlyBinding()
 * 11 mrt 2022: TControllerAbstract: renaming executeAlways -> executeLateBinding()
 * 18 nov 2022: TControllerAbstract: flag bCachingEnabled toegevoegd
 */

 
namespace dr\classes\controllers;


abstract class TControllerAbstract
{
    private $bCachingEnabled = false;

    const CACHELOCATION_FILE = 0;
    const CACHELOCATION_SESSION = 1;

    const SESSION_ARRINDEX_CONTENTS = 0;
    const SESSION_ARRINDEX_TIMESTAMP = 1;

    /**
     * constructor
     */
    public function __construct()    
    {
        $this->render();
    }


    /**
     * set if cache is enabled or not.
     *      
     * 
     * if you want to enable caching, you NEED to overload:
     *   1) getCacheFilePath() and return NOT ''
     *   2) getCacheTimeOutSeconds() and return > 0
     * 
     * the cache status you can request via isCacheEnabled()
     */
    public function setCachedEnabled($bEnabled)
    {
        $this->bCachingEnabled = $bEnabled;
    }

    /**
     * this gets the flag if caching is enabled
     * 
     * isCacheEnabled() checks if conditions for caching are met if getCacheEnabled() is true:
     *   1) getCacheFilePath() and return NOT ''
     *   2) getCacheTimeOutSeconds() and return > 0
     */
    public function getCacheEnabled()
    {
        return $this->bCachingEnabled;
    }


    /**
     * starts rendering the controller and checks if file is cached
     * if not cached or old it runs execute()
     * this function records the output and saves it to a cache file
     *
     * @return bool success?
     */
    protected function render()
    {
        $sCacheFile = '';
        $iCacheType = 0;
        $sCacheFile = $this->getCacheFilePath();
        $iCacheType = $this->getCacheLocation();

        // if ($this->isCacheHit())
        //     vardump('cachehit'. $iCacheType );
        // else
        //     vardump('cachesaved'.$iCacheType );
        // vardump($_SESSION, 'flie');

        //take cache file or render?
        if ($this->isCacheHit())
        {
            $sCacheFileContents = '';

            if ($iCacheType == TControllerAbstract::CACHELOCATION_FILE)
            {
                $sCacheFileContents = file_get_contents($sCacheFile);

                //output to screen
                if ($sCacheFileContents)
                {
                    //late binding vars
                    $this->bindLateBindingVars($sCacheFileContents);

                    echo $sCacheFileContents;
                    return true;
                }
                else
                {
                    error_log(get_class($this).' ERROR: reading cachefile '.$sCacheFile.' FAILED!!!');
                    return false;
                }
            }
            elseif($iCacheType == TControllerAbstract::CACHELOCATION_SESSION)
            {
                echo $_SESSION[$sCacheFile][TControllerAbstract::SESSION_ARRINDEX_CONTENTS];
            }
        }
        else //render whole shizzle
        {   
            $sHTMLContentSkinWithTemplate = '';
            $arrVars = array();

            //variables for the template        
            $arrVars = $this->executeEarlyBinding(); //call the actions in child controller
            if ($arrVars) //if not null
                $arrVars = array_merge($GLOBALS, $arrVars); //ORDER OF PARAMETERS IS IMPORTANT -> we pick $GLOBALS as base and overwrite them with the variables from execute()
            else
                $arrVars = $GLOBALS;

            //render templates
            if ($this->getTemplatePath() != '') //only render if exists
                $arrVars['sHTMLContentMain'] = renderTemplate($this->getTemplatePath(), $arrVars); //add content template to the variables for the skin                        
            else
                $arrVars['sHTMLContentMain'] = '';

            if ($this->getSkinPath() != '') //only render if exists    
                $sHTMLContentSkinWithTemplate = renderTemplate($this->getSkinPath(), $arrVars);    
            else
                $sHTMLContentSkinWithTemplate = '';

            //write to cachefile
            if ($this->isCachingEnabled())
            {
                if ($iCacheType == TControllerAbstract::CACHELOCATION_FILE)
                {
                    if (!file_put_contents($sCacheFile, $sHTMLContentSkinWithTemplate))
                    {
                        error_log(get_class($this).' ERROR: writing cachefile '.$sCacheFile.' FAILED!!!');
                        return false;
                    }
                }
                elseif ($iCacheType == TControllerAbstract::CACHELOCATION_SESSION)
                {
                    $_SESSION[$sCacheFile][TControllerAbstract::SESSION_ARRINDEX_CONTENTS] = $sHTMLContentSkinWithTemplate;
                    $_SESSION[$sCacheFile][TControllerAbstract::SESSION_ARRINDEX_TIMESTAMP] = time();
                }
            }

            //late binding vars (after cache file write)
            $this->bindLateBindingVars($sHTMLContentSkinWithTemplate);

            //output to screen
            echo $sHTMLContentSkinWithTemplate;

            return true;
        }

        return true;
    }

    /**
     * bind the late-binding variables
     * variables are in the format: [variablename]
     * 
     * @param string $sContents
     * @return string
     */
    private function bindLateBindingVars(&$sContents)
    {
        $arrExplode = array();

        //we are going to do a string replace,
        $arrVars = $this->executeLateBinding(); //call actions in the child controller, replaces the vars in $arrVars
        if ($arrVars) //only if there are vars
        {
            $arrVarNames = array_keys($arrVars);
            $iCountVarNames = count($arrVarNames);
            for($iVarIndex = 0; $iVarIndex < $iCountVarNames; ++$iVarIndex)
            {
                //speedtests have shown that str_replace in this particular case is QUICKER than explode()
                $sContents = str_replace('['.$arrVarNames[$iVarIndex].']', 
                                $arrVars[$arrVarNames[$iVarIndex]], 
                                $sContents
                                        );
            }
        }        

        // return $sContents; //not nessesary to returns since parameter is by reference
    }    

    /**
     * is it a cache hit?
     * returns also false if cache file is expired
     * returns false if cache disabled or GLOBAL_CACHE_CLEARONRUN is true
     *
     * @return boolean
     */
    public function isCacheHit()
    {
        $iTimeStamp = 0;
        $iTimeStampExpires = 0;
        $sCacheFilePath = '';
        $iCacheType = 0;

        $sCacheFilePath = $this->getCacheFilePath();
        $iCacheType = $this->getCacheLocation();

        if (GLOBAL_CACHE_CLEARONRUN)
            return false;

        if (!$this->isCachingEnabled())
            return false;
        

        if ($iCacheType == TControllerAbstract::CACHELOCATION_FILE)
        {
            if (is_file($sCacheFilePath))
            {
                $iTimeStamp = filemtime($sCacheFilePath);
                if ($iTimeStamp === false) //ensure we have always an int (it can also return false)
                    $iTimeStamp = 0;
            }
        }
        elseif ($iCacheType == TControllerAbstract::CACHELOCATION_SESSION)
        {   
            if (isset($_SESSION[$sCacheFilePath][TControllerAbstract::SESSION_ARRINDEX_TIMESTAMP]))
                $iTimeStamp = $_SESSION[$sCacheFilePath][TControllerAbstract::SESSION_ARRINDEX_TIMESTAMP];
        }


        if ($iTimeStamp > 0)
        {
            $iTimeStampExpires = $iTimeStamp + $this->getCacheTimeOutSeconds();
        
            return !($iTimeStampExpires < time());
        }
        else //return cache as expired on failure, this way new data is always generated
            return false;        
    }




    /**
     * returns true if all conditions for caching are met AND if flag bCachingEnabled is set to true
     */

    public function isCachingEnabled()
    {
        if (!$this->getCacheEnabled())
            return false;

        if ($this->getCacheFilePath() == '')
            return false;
        
        if ($this->getCacheTimeOutSeconds() == 0)
            return false;

        return true;
    }

    /**
     * depending on the cache type:
     * returns path of the cache file or index in the session array
     * 
     * make sure to make separate files for separate id's
     * For example: 
     * for a blog post: make a separate file for how-to-feed-your-dog and how-to-clean-an-elephant
     *
     * 
     * CAUTION:
     * if you grab the id from the url, don't forget to filter it for directory traversal
     * and other security related issues
     * 
     * @return string
     */
    public function getCacheFilePath()
    {
        return '';
    }


    /**
     * return timeout in seconds
     * 
     * overload this function in child class to enable caching
     * When timeout is set to 0, caching is disabled
     *
     * @return int;
     */
    public function getCacheTimeOutSeconds()
    {
        return 0;
    }


    /**
     * Returns int with cache location (defined by class constant)
     * 
     * possible types are:
     * TControllerAbstract::CACHELOCATION_FILE
     * TControllerAbstract::CACHELOCATION_SESSION
     *
     * @return int
     */
    public function getCacheLocation()
    {
        return TControllerAbstract::CACHELOCATION_FILE;
    }


    /*****************************************
     * 
     *  ABSTRACT FUNCTIONS
     * 
     *****************************************/

  
    /**
     * This function adds EARLY BINDING variables to template, which are cached (if cache enabled)
     * (see description on top of this class for more info)
     * 
     * this is the gold-old-fashion way of doing php with regular php variables etc.
     *
     * executes the things you want to cache
     * this function is ONLY called on a cache miss 
     * (if caching enabled, if NOT enabled it's ALWAYS called).
     * This function generates content for the cache file and for displaying on-screen
     * 
     * this function is executed BEFORE executeLateBinding(), because it's early binding
     * 
     * @return array with variables, use: "return get_defined_vars();" to use all variables declared in the execute() function
     */
    abstract public function executeEarlyBinding();

    /**
     * This function adds LATE BINDING variables to template which are NOT cached 
     * (for more info: see description on top of this class)
     * 
     * executes the things you always want to execute, even on a cache miss
     * executeEarlyBinding() is executed first, then executeLateBinding()
     *  
     * These variables that aren't resolved by php in the cache file
     * This way you can add dynamic php code to an otherwise cached page
     * 
     * These late binding variables need to be in the following format in the template: [variablename]
     * (Otherwise PHP will resolve variables in thecachefile with the format: $variablename)
     * 
     * This function is executed AFTER executeEarlyBinding()
     * 
     * @return array with variables, use: "return get_defined_vars();" to use all variables declared in the execute() function
     */
    abstract public function executeLateBinding();


    /**
     * return path of the page template
     *
     * @return string
     */
    abstract public function getTemplatePath();

    /**
     * return path of the skin template
     * 
     * return '' if no skin
     *
     * @return string
     */
    abstract public function getSkinPath();



}



 
?>