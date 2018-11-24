<?php
namespace models\register;
use settings as glob;
use index as ip;
use lib\database as database;
use lib\error as errors;

 if (!defined('IMP'))
    {
	die();
    }else{
        require_once (glob\settings::incDir('lib').'/database.php');
        require_once (glob\settings::incDir('lib').'/error.php');
    }

        class registration extends ip\UserIP
            {
                // Registration stuffs
                private $regUser = NULL;
                private $regPass = NULL;
                private $regEmail = NULL;
                //::CRITICAL SECURITY WARNING:: FILTER INPUT BEFORE YOU INSTANTIATE THIS CLASS!!
                //:: fix constructor
                final public function __construct($parName, $parPass, $parEmail)
                        {
                            //Set the class properties if they aren't NULL.
                            if (!empty($parName) AND !empty($parPass) AND !empty($parEmail))
                                {
                                    $this->regUser  = $parName;
                                    $this->regPass  = $parPass;
                                    $this->regEmail = $parEmail;
                                }
                        }
                public function register()
                        {
                            $database = new database\database();
                            //SELECT exists (SELECT email FROM USERS WHERE email = 'mail@mail.com' LIMIT 1);
                            //make sure we aren't double regging emails.
                            $database->beginTransaction();
                            $database->query('SELECT exists (SELECT email FROM USERS WHERE email = :checkemail LIMIT 1);');
                            $database->dbBind(':checkemail', $this->regEmail);
                            $database->dbExecute();
                            $email_exist = $database->resultset();
                            $database->endTransaction();
                            if (isset($email_exist[0]['exists'])){
                                if ($email_exist[0]['exists'] === FALSE){
                                    $database->beginTransaction();
                                    $database->query('INSERT INTO USERS(name, password_hash, email, accessLevel, stat, title, created_date, ipaddress) VALUES (:name, :password_hash, :email, :accessLevel, :stat, :title, CURRENT_TIMESTAMP, :ipaddress);');
                                    $database->dbBind(':name', $this->regUser);
                                    //Password hashing
                                    $timeTarget = 0.05; // 50 milliseconds 
                                    $cost = 8;
                                    $newPass = NULL;
                                    do {
                                        $cost++;
                                        $start = microtime(true);
                                        $newPass = password_hash($this->regPass, PASSWORD_BCRYPT, ["cost" => $cost]);
                                        $end = microtime(true);
                                    } while (($end - $start) < $timeTarget);
                                    $options = [ 'cost' => $cost ];
                                    $database->dbBind(':password_hash', $newPass);

                                    $database->dbBind(':email', $this->regEmail);
                                    $database->dbBind(':accessLevel', '1');
                                    $database->dbBind(':stat', '1');
                                    $database->dbBind(':title', 'Member');
                                    //Wonder if there's a better way of doing this without extending...
                                    $database->dbBind(':ipaddress', $this->get());
                                    $database->dbExecute();
                                    $database->endTransaction();
                                }else{
                                    unset($database);
                                    $objError = new errors\uhoh();
                                   $reason = array(
                                        'code' => '',
                                        'desc' => 'email_exists'
                                    );
                                    $Errors = array("email",$reason['code'],$reason['desc']);
                                    $objError->setErrors('database', $Errors, 3);
                                    unset ($objError);
                                    return FALSE;
                                }
                            }
                            unset($database);
                            return TRUE;
                        }
            }
?>
