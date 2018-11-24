<?php
namespace models\logauth;
use settings as glob;
use index as ip;
 if (!defined('IMP'))
    {
	die();
    }else{
        require_once (glob\settings::incDir('lib').'/database.php');
        require_once (glob\settings::incDir('lib').'/error.php');
    }
use lib\error as error;
use lib\database as database;
    
class login extends ip\UserIP
    {
        private $Pass = NULL;
        private $Email = NULL;
        
        final public function __construct($Pass, $Email)
                {
                    //Set the class properties if they aren't NULL. (isset allows NULL!!)
                    if (!empty($Pass) AND !empty($Email))
                        {
                            $this->Pass  = $Pass;
                            $this->Email = $Email;
                        }
                }
        public function start()
                {
                    $database = new database\database();
                    //SELECT exists (SELECT email FROM USERS WHERE email = 'mail@mail.com' LIMIT 1);
                    //make sure we aren't double regging emails.
                    $database->beginTransaction();
                    $database->query('SELECT exists (SELECT email FROM USERS WHERE email = :checkemail LIMIT 1);');
                    $database->dbBind(':checkemail', $this->Email);
                    $database->dbExecute();
                    $email_exist = $database->resultset();
                    $database->endTransaction();
                    
                    if (isset($email_exist[0]['exists'])){
                        if ($email_exist[0]['exists'] === true){
                            unset($email_exist);
                            $database->beginTransaction();
                            $database->query('SELECT password_hash FROM users WHERE email=:userEmail LIMIT 1;');
                            $database->dbBind(':userEmail', $this->Email);
                            $database->dbExecute();
                            $userPassword = $database->resultset();
                            $database->endTransaction();
                            return password_verify($this->Pass, $userPassword[0]['password_hash']);
                        }else{
                            unset($database);
                            $objError = new error\uhoh();
                           $reason = array(
                                'code' => '',
                                'desc' => 'Email not registered'
                            );
                            $Errors = array("email",$reason['code'],$reason['desc']);
                            $objError->setErrors('database', $Errors, 3);
                            unset ($objError);
                            return FALSE;
                        }
                    }
                    unset($database);
                    return;
                }
    }
?>
