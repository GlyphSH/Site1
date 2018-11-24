<?php
namespace controllers\logauth;
use settings as glob;
 if (!defined('IMP'))
    {
	die();
    }else{
        require_once (glob\settings::incDir('controllers'). '/main.php');
        require_once (glob\settings::incDir('models'). '/main.php');
        require_once (glob\settings::incDir('lib').         '/security.php');
        require_once (glob\settings::incDir('models').      '/logauth.php');
    }
use lib\error as errors;
use lib\security as security;
use controllers\main as controllerMain;
use models\main as modelMain;
use models\logauth as modelLogauth;

class login
{
    
    public static function start(){
            controllerMain\loadTemplate('logauth');
       }
       
 }
 
 class login2 
 {
    public static $userArray = array('NULL');
    public static function start(){
           $reqFields = array('password', 'email');
           $reqError = FALSE;

           foreach($reqFields as $field) 
               {
                   if (empty($_POST[$field]))
                       {
                           $reqError = TRUE;
                       }
                       unset($field);
               }

           if ($reqError == FALSE)
               {
                   $objClean = new security\cleanLogin;
                   $usrInfo = array(
                       'email'             => $_POST['email']
                    );


                   $usrCleanInfo = $objClean->POST($usrInfo);
                   //did it not filter || clean correctly?
                   $regErrors = errors\uhoh::getErrors('login');
                   $errCount = count($regErrors);
                   //Any database errors?
                   $dbErrors = errors\uhoh::getErrors('database');
                   $dbCount = count($dbErrors);
                   if ($errCount < 1 AND $dbCount < 1)
                       {
                           $objReg = new modelLogauth\login($_POST['password'],$usrCleanInfo['email']);
                           $validPassword = $objReg->start();
                           //check for erros here/
                           $regErrors2 = errors\uhoh::getErrors('login');
                           $errCount2 = count($regErrors2);
                           if ($errCount2 < 1){
                               if ($validPassword){
                                  session_start();

                                  //start updating session stuffs
                                  //first grab the user variables from the main controller
                                  //
                                   //well it did everything correctly :)
                                   //
                                  //Initiate the session variables here
                                  $dump = new userSettings($usrCleanInfo['email']);
                                  /*
                                   *   name
                                       email
                                       accesslevel
                                       stat
                                       title
                                       created_date
                                       ipaddress
                                   */

                                  echo 'Welcome, '.$_SESSION['name'].'!<br>';
                                  echo 'You are a '.$_SESSION['title'];
                                  

                                  //update user session
                                  controllerMain\loadTemplate('forum_main');
                               }else{
                              $objError = new errors\uhoh();
                              $reason = array(
                                   'code' => '',
                                   'desc' => 'Invalid Password'
                               );
                               $Errors = array("email",$reason['code'],$reason['desc']);
                               $objError->setErrors('database', $Errors, 3);
                               unset ($objError);
                                   controllerMain\loadTemplate('logauth', TRUE, 'database');
                               }
                           }else{
                               controllerMain\loadTemplate('logauth', TRUE, 'database');
                           }
                       }else{
                           controllerMain\loadTemplate('logauth', TRUE, 'login');
                       }
                   }else{
                        controllerMain\loadTemplate('logauth', TRUE, 'login');
                        echo '<center><p><b><font color="red">Missing fields!</font></b></p></center>';
                   }
       //check login info
       //use database.
          }
 }
 
 class logout{
     public function start(){
         session_start();
         if (isset($_SESSION['name'])){
             // Initialize the session.

            // Unset all of the session variables.
            $_SESSION = array();

            // If it's desired to kill the session, also delete the session cookie.
            // Note: This will destroy the session, and not just the session data!
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }

            // Finally, destroy the session.
            session_destroy();
           controllerMain\loadTemplate('logauth');
            return TRUE;
         }else{
             controllerMain\loadTemplate('logauth');
             return FALSE;
         }
     }
 }
 
 //fix classname
class UserSettings{
    private $userEmail;
    public $mainArray = array();
    public function __construct($userInfo)
            {
                $this->userEmail = $userInfo;
                $allObj = new modelMain\main();
                $allUser = $allObj ->populate($this->userEmail);
                $this->mainArray = $allUser;
                                  session_start();
                                  $_SESSION['name'] = $this->mainArray[0]['name'];
                                  $_SESSION['title'] = $this->mainArray[0]['title'];
                                  $_SESSION['email'] = $this->mainArray[0]['email'];
                return $this->mainArray;
               // var_dump($allUser);
                //Set up some stuff!
               //select all relevent user data from the database and throw it in an array for the user.
               //return to logauth controller and let main model handle session.
        
       
            }
}
?>