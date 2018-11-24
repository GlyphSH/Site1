<?php
namespace lib\database;
use settings as settings;
require_once (settings\settings::incDir('lib').'/error.php');
use lib\error as errors;
use \PDO;
class database {
    private $dbHandle;
    private $dbStmt;
    
    public function __construct()
            {
                // Set DSN
                $dsn = 'pgsql:host=' . settings\settings::Database('server') . ';dbname=' . settings\settings::Database('name');

                try {
                    $this->dbHandle = new \PDO($dsn, settings\settings::Database('user'), settings\settings::Database('pass'));
                }
                // Catch any errors
                catch (PDOException $e) {
                    $objError = new errors\uhoh();
                    $Errors = array("DATABASE",$e->getMessage(),"DATABASE");
                    $objError->setErrors('DATABASE', $Errors, 3);
                    unset ($objError);
                    echo $e->getMessage();

                }
            }

    public function query($query)
            {
                $this->dbStmt = $this->dbHandle->prepare($query);
            }
    
    public function dbBind($param, $value, $type = null)
            {
                if (is_null($type))
                    {
                        switch (true)
                            {
                                case is_int($value):
                                        $type = \PDO::PARAM_INT;
                                        break;
                                case is_bool($value):
                                        $type = \PDO::PARAM_BOOL;
                                        break;
                                case is_null($value):
                                        $type = \PDO::PARAM_NULL;
                                        break;
                                default:
                                        $type = \PDO::PARAM_STR;
                            }
                    }
                    $this->dbStmt->bindValue($param, $value, $type);
    }
    
    public function dbExecute(){
        return $this->dbStmt->execute();
    }
    
    public function resultset(){
        $this->dbExecute();
        return $this->dbStmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function single(){
        $this->dbExecute();
        return $this->dbStmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    public function rowCount(){
        return $this->dbStmt->rowCount();
    }
    
    public function lastInsertId(){
        return $this->dbHandle->lastInsertId();
    }
    
    public function beginTransaction(){
        return $this->dbHandle->beginTransaction();
    }
    
    public function endTransaction(){
        return $this->dbHandle->commit();
    }
    
    public function cancelTransaction(){
        return $this->dbHandle->rollBack();
    }
    
    public function debugDumpParams(){
        return $this->dbStmt->debugDumpParams();
    }
}
?>