<?php
namespace controllers\register;
use settings as glob;
 if (!defined('IMP'))
    {
    }else{
        require_once (glob\settings::incDir('models').      '/register.php');
        require_once (glob\settings::incDir('lib').         '/security.php');
        require_once (glob\settings::incDir('controllers'). '/main.php');
    }
use lib\security as security;
use lib\error as errors;
use controllers\main as controllerMain;
use models\register as modelRegister;

class register
{
       public static function start()
        {
            //Load the front-end stuffs
            controllerMain\loadTemplate('register');
        } 
}
class register2
{
    public static function start()
        {
            $reqFields = array('username', 'password', 'email');
            $reqError = FALSE;

            foreach($reqFields as $field) 
                {
                    if (empty($_POST[$field]))
                        {
                            $reqError = TRUE;
                        }
                        unset($field);
                }
                //it's not fail so clean what they gave us
            if ($reqError == FALSE)
                {
                    $objClean = new security\clean;
                    $usrInfo = array(
                        'username' 		=> $_POST['username'],
                        'email'                 => $_POST['email']
                     );

                    $usrCleanInfo = $objClean->POST($usrInfo);
                    //did it not filter || clean correctly?
                    $regErrors = errors\uhoh::getErrors('registration');
                    $errCount = count($regErrors);

                    //Any database errors?
                    $dbErrors = errors\uhoh::getErrors('database');
                    $dbCount = count($dbErrors);

                    if ($errCount < 1 AND  $dbCount < 1)
                        {
                        //well it did everything correctly :)
                          $objReg = new modelRegister\registration($usrCleanInfo['username'],$_POST['password'],$usrCleanInfo['email']);
                           $objReg->register();
                            //redirect them to landing page and set sessions, etc..
                            //Might have to set sessions in the registration class... idk yet
                           //lets make sure no errors in registration
                            $dbtestErrors = errors\uhoh::getErrors('database');
                            $dbtestCount = count($dbtestErrors);

                           //no databse erros?
                          if ($dbtestCount < 1){
                            //Setup cookie information
                           /* $cookInfo = array(
                                            'id' => $id,
                                            'name' => $usrCleanInfo['username'],
                                            'login' => $usrCleanInfo['password'],
                                        );*/
                            //loadTemplate('BoardIndex');
                            controllerMain\header::redirect('login');
                            //Send cookie
                            //
                            //
                            //redirect to forum
                            //

                            //there were db errors...
                          }else{
                            //UH OH!
                            //let's try that again...
                            controllerMain\loadTemplate('register', TRUE, 'database');
                            unset($objReg);
                            return FALSE;
                          }
                          unset($objReg);
                        }else
                        {
                            //UH OH!
                            //let's try that again...
                            controllerMain\loadTemplate('register', TRUE, 'registration');
                            errors\uhoh::clearErrors();
                            unset($objReg);
                        }
                    unset($objClean);
                }else{
                     controllerMain\loadTemplate('register');
                     echo '<center><p><b><font color="red">Missing fields!</font></b></p></center>';
                }
        }
}
?>