<?php
namespace dr\classes\controllers;

/**
 * Description of TCRUDOverviewController
 * Create Read Update Delete:
 * A controller for a record list overview
 * 
 * the goal of this class is to keep it lightweight due to OOP performance issues 
 * in PHP, so no parent class (it needs to be as flat as possible when it comes to parent classes depth)
 * 
 * @author drenirie
 * 
 *  created 6 jan 2020
 * 9 jan 2020 - TCRUDOverviewController: executeDB() heeft extra parameter voor join
 * 30 dec 2021 - TCRUDOverviewController erft niet meer over van TControllerAbstract
 * 15 nov 2022 - TCRUDOverviewController doet nu bNoRecordsToDisplay
 * 15 nov 2022 - TCRUDOverviewController executeDB() heeft mixed parameter voor level diepte
 */

use dr\classes\models\TModel;
use dr\classes\dom\TPaginator;
    
use dr\classes\dom\tag\Div;
use dr\classes\dom\tag\form\InputCheckbox;
use dr\classes\dom\tag\form\Form;
use dr\classes\dom\tag\Text;
use dr\classes\dom\tag\form\InputSubmit;
use dr\classes\dom\tag\form\Select;
use dr\classes\dom\tag\form\Option;
use dr\classes\dom\tag\form\InputText;
use dr\classes\patterns\TModuleAbstract;

abstract class TCRUDListController
{
    protected $objModel = null;
    private $sModule = '';
    
    private $objPaginator = null;
    private $bUseBulkDelete = true;
    //private $bUseBulkCheckout = true; --> can be retrieved from model
    //private $bUseBulkLock = true;--> can be retrieved from model
    
    const FIELD_QUICKSEARCH = 'edtQuickSearch';
    
    /**
     * 
     * @param TModel $objModel
     */
    public function  __construct()
    {               
        $this->sModule = getModuleFromURL();
        $this->objModel = $this->getNewModel();

        $this->objPaginator = new TPaginator(TPaginator::STYLE_NUMBEREDPAGES);   
        $this->objPaginator->setItemCountPerPage(getSetting(SETTINGS_MODULE_CMS, SETTINGS_CMS_PAGINATOR_MAXRESULTSPERPAGE));
    

        //check permissions
        if (!auth($this->sModule, $this->getAuthorisationCategory(), AUTH_OPERATION_VIEW))
        {
            showAccessDenied(transcms('message_noaccess_viewrecords', 'you don\'t have permission to view these records'));
            die();
        }

        $this->render();
    }

    /**
     * render to screen
     *
     * @return void
     */
    protected function render()
    {
        global $objCurrentModule;
        $sHTMLContentSkinWithTemplate = '';
        $arrVars = array();

        //variables for the template
        $arrVars = $this->execute(); //call the actions in child controller
        $arrVars['sTitle'] = $this->getTitle();
        $arrVars['sHTMLTitle'] = $arrVars['sTitle'];
        $arrVars['sHTMLMetaDescription'] = $arrVars['sTitle'];
        $arrVars['objCRUD'] = $this;
        $arrVars['objPaginator'] = $this->getPaginator();
        $arrVars['objSelectBulkActions'] = $this->getBulkSelect();
        $arrVars['sURLDetailPage'] = $this->getDetailPageURL();
        $arrVars['arrTabsheets'] = $objCurrentModule->getTabsheets();        
        $arrVars['bNoRecordsToDisplay'] = false;
        if ($this->objModel != null)
            if ($this->objModel->count() == 0)
                $arrVars['bNoRecordsToDisplay'] = true;
        $arrVars = array_merge($GLOBALS, $arrVars); //ORDER OF PARAMETERS IS IMPORTANT -> we pick $GLOBALS as base and overwrite them with the variables from execute()

        //render templates
        if ($this->getTemplatePath() != '') //only render if exists
            $arrVars['sHTMLContentMain'] = renderTemplate($this->getTemplatePath(), $arrVars); //add content template to the variables for the skin                        
        else
            $arrVars['sHTMLContentMain'] = '';

        if ($this->getSkinPath() != '') //only render if exists    
            $sHTMLContentSkinWithTemplate = renderTemplate($this->getSkinPath(), $arrVars);    
        else
            $sHTMLContentSkinWithTemplate = '';


        //output to screen
        echo $sHTMLContentSkinWithTemplate;
    }
    
    /**
     * set model object
     * @param TModel $objModel
     */
    public function setModel($objModel)
    {
        $this->objModel = $objModel;
    }
    
    /**
     * get module object
     * @return TModel
     */
    public function getModel()
    {       
        return $this->objModel;
    }
    

    /**
     * set authorisation category for auth() function
     */
    public function setAuthorisationCategory($sAuthorisationCategory)
    {
        $this->sAuthorisationCategory = $sAuthorisationCategory;
    }
    

    /**
     * set model object
     * @param TModel $objModel
     */
    public function setModule($sModule)
    {
        $this->sModule = $sModule;
    }
    
    /**
     * get module object
     * @return TModel
     */
    public function getModule()
    {
        return $this->sModule;
    }
    
    public function getPaginator()
    {
        return $this->objPaginator;
    }
  
    /**
     * use bulk delete?
     * 
     * @return boolean
     */
    public function getUseBulkDelete()
    {
        return $this->bUseBulkDelete;        
    }
    
    /**
     * use bulk delete ?
     * 
     * @param boolean $bDelete
     */
    public function setUseBulkDelete($bDelete)
    {
        $this->bUseBulkDelete = $bDelete;    
    }
          

    /**
     * returns the html select tag with all the bulk items
     * 
     * @return Select
     */
    public function getBulkSelect()
    {
        $objSelectBulkActions = new Select();
        $objSelectBulkActions->setNameAndID(BULKACTION_VARIABLE_SELECT_ACTION);

        $objNone = new Option();
        $objNone->setValue('');
        $objNone->setText(transcms('overview_bulkactions_title', '[BULK ACTIONS]'));
        $objSelectBulkActions->addNode($objNone);

        if ($this->bUseBulkDelete)
        {
            $objDelete = new Option();
            $objDelete->setValue(BULKACTION_VALUE_DELETE);
            $objDelete->setText(transcms('overview_bulkactions_delete', 'delete'));
            if (auth($this->sModule, $this->getAuthorisationCategory(), AUTH_OPERATION_DELETE))
                $objSelectBulkActions->addNode($objDelete);
            unset($objDelete);
        }

        if ($this->objModel->getTableUseCheckout() && auth($this->sModule, $this->getAuthorisationCategory(), AUTH_OPERATION_CHECKINOUT))
        {
            $objCheckout = new Option();
            $objCheckout->setValue(BULKACTION_VALUE_CHECKOUT);
            $objCheckout->setText(transcms('overview_bulkactions_checkout', 'check out for editing (lock record)'));
            $objSelectBulkActions->addNode($objCheckout);    
            unset($objCheckout);    

            $objCheckin = new Option();
            $objCheckin->setValue(BULKACTION_VALUE_CHECKIN);
            $objCheckin->setText(transcms('overview_bulkactions_checkin', 'check in (done editing & unlock record)'));
            $objSelectBulkActions->addNode($objCheckin);    
            unset($objCheckin);
        }

        if ($this->objModel->getTableUseLock() && auth($this->sModule,$this->getAuthorisationCategory(), AUTH_OPERATION_LOCKUNLOCK))
        {
            $objLock = new Option();
            $objLock->setValue(BULKACTION_VALUE_LOCK);
            $objLock->setText(transcms('overview_bulkactions_lock', 'lock'));
            $objSelectBulkActions->addNode($objLock);    
            unset($objLock);

            $objUnlock = new Option();
            $objUnlock->setValue(BULKACTION_VALUE_UNLOCK);
            $objUnlock->setText(transcms('overview_bulkactions_unlock', 'unlock'));
            $objSelectBulkActions->addNode($objUnlock);    
            unset($objUnlock);     
        }
        
        return $objSelectBulkActions;
    }
    
    /**
     * execute things like delete, lock etc.
     * 
     * @param mixed $mAutoJoinDefinedTablesLevels -1=unlimited, 0=none, false=0; 1=1level, true=1level
     */
    public function executeDB($mAutoJoinDefinedTablesLevels = 0)
    {
        //first destructive manipulation edits
        $this->executeChangeOrderUpDown();
        $this->executeBulkActions();
        
        //then record selection and display
        $this->executeQuicksearch();
        $this->executeSortColumns();        

        //actually execute
        $this->objModel->loadFromDB($mAutoJoinDefinedTablesLevels, $this->objPaginator);
    }
    
    /**
     * Change order records 1 up or down (switch 1 place with record above or below)
     */
    private function executeChangeOrderUpDown()
    {
        if (isset($_GET[ACTION_VARIABLE_ORDERONEUPDOWN]) && isset($_GET[ACTION_VARIABLE_ID]))    
        {        
            if ($_GET[ACTION_VARIABLE_ORDERONEUPDOWN] == ACTION_VALUE_ORDERONEUPDOWN)
            {
                if (auth($this->getModule(), $this->getAuthorisationCategory(), AUTH_OPERATION_CHANGEORDER))
                {
                    $this->objModel->orderChangeOneUpDownDB($_GET[ACTION_VARIABLE_ID], $_GET[ACTION_VARIABLE_ORDERONEUP], $_GET[ACTION_VARIABLE_SORTORDER]);
                }
            }
        } 
    }
    
    /**
     * quicksearch
     */
    private function executeQuicksearch()
    {
        if (isset($_GET[TCRUDListController::FIELD_QUICKSEARCH]))
            $this->objModel->findQuicksearch(array(), $_GET[TCRUDListController::FIELD_QUICKSEARCH], false); //temporary include foreigntables=false, until FROM bug is fixed (FROM field not added in quicksearches)
    }
    
    /**
     * sort
     */
    private function executeSortColumns()
    {
       
        $arrQBSelect = array();
        $arrQBSelect = $this->objModel->getQBSelectFrom();
        
        if (!$arrQBSelect) //select can be empty if no fields are defined via $objModel->selectFrom();
        {
            error_log ('column sorting doesnt work because no fields are defined via $objModel->select(). (fields are generated by TModel after this function is called). Error thrown in '.__METHOD__);
        }
        else //$arrQBSelect exists
        {
            if (isset($_GET[ACTION_VARIABLE_SORTCOLUMNINDEX])) //sort-column-index
            {
                $iSortColIndex = $_GET[ACTION_VARIABLE_SORTCOLUMNINDEX];
                $sSortOrder = '';
                $sSortColumn = '';
                $sSortTable = '';
                if (is_numeric($iSortColIndex)) //prevent injection
                {
                    if ($iSortColIndex < count($arrQBSelect)) //prevent indexes outside the scope of the array
                    {
                        $sSortColumn = $arrQBSelect[$iSortColIndex][TModel::QB_SELECTINDEX_FIELD];
                        $sSortTable = $arrQBSelect[$iSortColIndex][TModel::QB_SELECTINDEX_TABLE];
                    }
                }
                if (!isset($_GET[ACTION_VARIABLE_SORTORDER]))
                    $sSortOrder = SORT_ORDER_NONE;
                else
                    $sSortOrder = $_GET[ACTION_VARIABLE_SORTORDER];

                $this->objModel->sort($sSortColumn, $sSortOrder, $sSortTable);
            }
            else //no sort order selected? then pick sortorder column (if it exists)
            {
                if ($this->objModel->getTableUseOrderField())
                {
                    $this->objModel->sort(TModel::FIELD_ORDER);
                }
            }        
        }
    }
    
    /**
     * execute bulk actions
     */
    protected function executeBulkActions()
    {
        global $objLoginController;
        $bBulkSuccess = false;

        if (isset($_GET[BULKACTION_VARIABLE_SELECT_ACTION]) && isset($_GET[BULKACTION_VARIABLE_CHECKBOX_RECORDID]))
        {    
            $iCountIDs = count($_GET[BULKACTION_VARIABLE_CHECKBOX_RECORDID]);

            foreach($_GET[BULKACTION_VARIABLE_CHECKBOX_RECORDID] as $iID)
            {
                if (is_numeric($iID))
                {
                    //delete action?
                    if ($this->bUseBulkDelete)
                    {
                        if ($_GET[BULKACTION_VARIABLE_SELECT_ACTION] == BULKACTION_VALUE_DELETE)
                        {
                            if (auth($this->sModule, $this->getAuthorisationCategory(), AUTH_OPERATION_DELETE))
                            {
                                $this->objModel->clear(true);
                                $this->objModel->findID($iID);
                                if ($this->objModel->getTableUseLock())
                                    $this->objModel->find(TModel::FIELD_LOCKED, false);

                                if ($this->objModel->getTableUseCheckout())
                                {
                                    //@todo check if record is checked-out or checkout-date expired
                                }
                                    

                                if ($this->objModel->deleteFromDB(true, true))
                                    $bBulkSuccess = true;
                                else
                                    error_log('delete record with id '.$iID.' failed for '.$this->objModel::getTable());
                            }
                            else
                                error_log('auth() failed for bulk deleting records');
                        }
                    }

                    //checkin and checkout
                    if ($this->objModel->getTableUseCheckout())
                    {
                        //checkOUT action?
                        if ($_GET[BULKACTION_VARIABLE_SELECT_ACTION] == BULKACTION_VALUE_CHECKOUT)
                        {
                            if (auth($this->sModule, $this->getAuthorisationCategory(), AUTH_OPERATION_CHECKINOUT))
                            {
                                if ($this->objModel->checkoutNowDB($iID, $this->sModule.': records overview screen by user: '.$objLoginController->getUsers()->getUsername()))
                                    $bBulkSuccess = true;
                                else
                                    error('checkout with id '.$iID.' failed for '.$this->objModel::getTable());
                            }

                        }  

                        //checkIN action?
                        if ($_GET[BULKACTION_VARIABLE_SELECT_ACTION] == BULKACTION_VALUE_CHECKIN)
                        {
                            if (auth($this->sModule, $this->getAuthorisationCategory(), AUTH_OPERATION_CHECKINOUT))
                            {
                                if ($this->objModel->checkinNowDB($iID))
                                    $bBulkSuccess = true;
                                else
                                    error('checkin with id '.$iID.' failed for '.$this->objModel::getTable());
                            }

                        }     
                    }


                    //lock and unlock
                    if ($this->objModel->getTableUseLock())
                    {
                        //lock action?
                        if ($_GET[BULKACTION_VARIABLE_SELECT_ACTION] == BULKACTION_VALUE_LOCK)
                        {
                            if (auth($this->sModule, $this->getAuthorisationCategory(), AUTH_OPERATION_LOCKUNLOCK))
                            {
                                if ($this->objModel->lockNowDB($iID, $this->sModule.': records overview screen by user: '.$objLoginController->getUsers()->getUsername()))
                                    $bBulkSuccess = true;
                                else
                                    error('locking with id '.$iID.' failed for '.$this->objModel::getTable());
                            }

                        }               

                        //unlock action?
                        if ($_GET[BULKACTION_VARIABLE_SELECT_ACTION] == BULKACTION_VALUE_UNLOCK)
                        {
                            if (auth($this->sModule, $this->getAuthorisationCategory(), AUTH_OPERATION_LOCKUNLOCK))
                            {
                                if ($this->objModel->unlockNowDB($iID))
                                    $bBulkSuccess = true;
                                else
                                    error('unlocking with id '.$iID.' failed for '.$this->objModel->getTable());
                            }

                        }                  
                    }
                }
            }

            $sRefURL = '';
            $sRefURL = removeVariableFromURL(getURLThisScript(), BULKACTION_VARIABLE_SELECT_ACTION);
            $sRefURL = removeVariableFromURL($sRefURL, urlencode(BULKACTION_VARIABLE_CHECKBOX_RECORDID.'[]'));

            if ($bBulkSuccess)
            {
                if ($_GET[BULKACTION_VARIABLE_SELECT_ACTION] == '')                
                    $sRefURL = addVariableToURL ($sRefURL, 'cmsmessage', transcms('overview_bulkactions_empty', 'not selected a bulk action'));
                else
                    $sRefURL = addVariableToURL ($sRefURL, 'cmsmessage', transcms('overview_bulkactions_success', 'bulk actions completed succesfully'));
            }
            else
                $sRefURL = addVariableToURL ($sRefURL, 'cmserror', transcms('overview_bulkactions_error', 'execution bulk actions: FAILED!'));
            
            header('Location: '.$sRefURL);
        }        
    }


    /*****************************************
     * 
     *  ABSTRACT FUNCTIONS
     * 
     *****************************************/

  
    /**
     * executes the controller
     *
     * 
     * @return array with variables, use: "return get_defined_vars();" to use all variables declared in the execute() function
     */
    abstract public function execute();


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

    /**
     * return new TModel object
     * 
     * @return TModel;
     */
    abstract public function getNewModel();

    /**
     * return permission category 
     * =class constant of module class
     * 
     * for example: Mod_Sys_CMSUsers::PERM_CAT_USERS
     *
     * @return string
     */
    abstract public function getAuthorisationCategory();

     /**
     * returns the url for the detailpage for the browser to go to
     *
     * @return string
     */
    abstract public function getDetailPageURL();

    /**
     * return page title
     * It returns in the translated text in the current language of the user (it is not translated in the controller)
     * 
     * for example: "instellingen"
     *
     * @return string
     */
    abstract public function getTitle();

    /**
     * show tabsheets on top?
     *
     * @return bool
     */
    abstract public function showTabs();    
}
    

