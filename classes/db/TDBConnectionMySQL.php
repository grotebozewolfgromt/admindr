<?php
namespace dr\classes\db;


/**
 * specifieke database interpretatie voor mysql
 *
 * 24 sept 2012: TDBConnectionMySQL: basisopzet met mysqli ipv Zend met PDO
 * 24 sept 2012: TDBConnectionMySQL: character set is automatically set to utf8 upon connection
 * 9 mrt 2014: TDBConnectionMySQL: nasty bugsies, we hates them... na de commit werd de autocommit niet terug gezet
 * 9 jan 2020: TDBConnectionMySQL: forgot to include the port on connecting
 * 
 * @author dennis renirie
 */
class TDBConnectionMySQL extends TDBConnection
{
    public function connect()
    {
       
        try
        {       
            $objMySQL = new \mysqli($this->getHost(), $this->getUsername(), $this->getPassword(), $this->getDatabaseName(), $this->getPort());
                       
            if (!$objMySQL->set_charset('utf8')) /* change character set to utf8 */
                error('Error loading character set utf8: '.$objMySQL->error, $this);
            
            $this->setInternalDatabaseObject($objMySQL);
            
            //extra instelling
//            $objQuery = $this->getQuery();
//            $objQuery->executeQuery('SET sql_safe_updates=0');
//            if (!$objQuery->getExecutionSQLOK()) 
//            {
//               error('Error setting sql update option'.$objMySQL->error, $this);     
//            }
            
            return true;
        }
        catch (Exception $objException)
        {
            error($objException, $this);
            return false;
        }

    }

    /**
     * het uitvoeren van een sql query
     *
     * @param string $sSQL - SQL query om uit te voeren
     * @param int $iResultsPerPage - max aantal resultaten die query mag teruggeven)
     * @param int $iPage - start met recordnr (voor paginate functie)
     * @return TDBResultset object
     */
//     public function executeQuery($sSQL, $iResultsPerPage = 0, $iPage = 0)
//     {
//         try
//         {
//             $objQuery = new TDBQueryMySQL($this);
//             $objRst = $objQuery->executeQuery($sSQL, $iResultsPerPage, $iPage);
//             return $objRst;
//         }
//         catch (Exception $objException)
//         {
//             error($objException, $this);
//             error_log($objException->getMessage());
//             return null;
//         }
//     }

     /**
     * het verkrijgen van een query object
     *
     * @return TDBQuery object
     */
    public function getQuery()
    {
    		return new TDBPreparedStatementMySQL($this);
//         return new TDBQueryMySQL_OLD($this);
    }

    public function disconnect()
    {
        $objMySQL = $this->getAPIConnObject();
        $objMySQL->close();        
    }

    public function isConnected()
    {
        $objMySQL = $this->getAPIConnObject();
        return !$objMySQL->connect_errno();
    }

    /**
     * make a SQL prepared statement
     * 
     * @param string $sPreparedStatement
     * @return TDBPreparedStatementMySQL
     */
    public function prepare($sPreparedStatement)
    {
        $objPreparedStatement = new TDBPreparedStatementMySQL($this);
        $objPreparedStatement->prepare($sPreparedStatement);
        return $objPreparedStatement;
    }
    
    /**
     * commit a database transaction and return to autocommit state
     */    
    public function commit()
    {
//        logDev('commit');
        
        try
        {
            $objMySQL = $this->getAPIConnObject();
            $bAutoCommit = $objMySQL->autocommit(true);
            $bCommit = $objMySQL->commit();
            
            return ($bAutoCommit && $bCommit);
            
//            //rollback
//            $this->executeQuery('COMMIT');
//            if ($this->getExecutionSQLOK())
//            {
//                //autocommit weer terugzetten
//                $this->executeQuery('SET autocommit=1');                                
//                return $this->getExecutionSQLOK();
//            }
//            else
//            {
//                //ook als rollback mislukt is, dan autocommit terugzetten
//                $this->executeQuery('SET autocommit=1');
//                return false;
//            }
        }
        catch (Exception $objException)
        {	
        		error($objException, $this);            
            return false;
        }  
    }    
    
    
    /**
     * rollback a database transaction and return to autocommit state
     */    
    public function rollback()
    {
//        logDev('rollback');

        try
        {
            $objMySQL = $this->getAPIConnObject();
            $bRollback = $objMySQL->rollback();
            $bAutoCommit = $objMySQL->autocommit(true);
            
            
            return ($bRollback && $bAutoCommit);            
            
//            //rollback
//            $this->executeQuery('ROLLBACK');
//            if ($this->getExecutionSQLOK())
//            {
//                //autocommit weer terugzetten
//                $this->executeQuery('SET autocommit=1');                                /
//                return $this->getExecutionSQLOK();
//            }
//            else
//            {
//                //ook als rollback mislukt is, dan autocommit terugzetten
//                $this->executeQuery('SET autocommit=1');
//                return false;
//            }
        }
        catch (Exception $objException)
        {
            error($objException, $this);
            return false;
        }            
    }
    
    /**
     * disable autocommit and starting a database transaction
     */
    public function startTransaction()
    {
//        logDev('starttransaction');
        
        try
        {
            $objMySQL = $this->getAPIConnObject();
            return $objMySQL->autocommit(false);
            
//            //eerst autocommit uit
//            $this->executeQuery('SET autocommit=0');
//            if ($this->getExecutionSQLOK())
//            {
//                //database transactie starten
//                $this->executeQuery('START TRANSACTION');
//                
//                //als start transactie niet lukt, dan terug naar autocommit
//                if (!$this->getExecutionSQLOK())
//                {
//                    $this->executeQuery('SET autocommit=1');
//                    return false;
//                }
//                
//                return $this->getExecutionSQLOK();
//            }
//            else
//                return false;
        }
        catch (Exception $objException)
        {
            error($objException, $this);
            return false;
        }          
    }

    /**
     * get a new prepared statement
     * 
     * @return TDBPreparedStatementMySQL
     */
    public function getPreparedStatement()
    {
        return new TDBPreparedStatementMySQL($this);
    }


}
?>
