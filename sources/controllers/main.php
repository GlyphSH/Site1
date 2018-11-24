<?php
namespace controllers\main;
if (!defined('IMP'))
{
    die();
}
require_once ('settings.php');
use settings;
require_once (settings\settings::incDir('lib').'/error.php');
require_once (settings\settings::incDir('models').'/main.php');
use lib\error as errors;
use models\main as modelMain;
//use controllers\logauth as controllerLogauth;

session_start();

$hmm = new action;

if (isset($_SESSION['name'])){
            //echo $_SESSION['name']; 
           // echo controllerLogauth\login2::$userArray[0]['name'];

            if ($hmm->current() != 'forum' AND $hmm->current() != 'logout' AND $hmm->current() != 'bnetapi' AND $hmm->current() != 'charsheet' AND $hmm->current() != 'forumtopic'){
                header("Location: ".settings\settings::URL('main').'forum');
                unset ($hmm);
                die();
            }
        unset ($hmm);
}else{
            if ($hmm->current() != 'login' AND $hmm->current() != 'register' AND $hmm->current() != 'login2' AND $hmm->current() != 'register2' AND $hmm->current() != 'charsheet'){
                header("Location: ".settings\settings::URL('main').'login');
                unset ($hmm);
                die();
            }
        unset ($hmm);
}


// Load a template!
function loadTemplate($template_name, $errFlag = FALSE, $errType = NULL, $fatal = true)
{
	global $loadErrors, $loadErrCount;
        //check for errors
        if ($errFlag == TRUE){
            switch ($errType){
                case 'registration':
                    $loadErrors = errors\uhoh::getErrors('registration');
                    $loadErrCount = errors\uhoh::getErrors('regCount');
                    break;
                case 'database':
                    $loadErrors = errors\uhoh::getErrors('database');
                    $loadErrCount = '3';
                    break;
                case 'boardindex':
                    $loadErrors = errors\uhoh::getErrors('boardindex');
                    $loadErrCount = '3';
                    break;
                case 'login':
                    $loadErrors = errors\uhoh::getErrors('login');
                    $loadErrCount = errors\uhoh::getErrors('logCount');
                    break;
                default:
                    $loadErrors = NULL;
                    $loadErrCount = NULL;
                    break;
            }
            }
	// No template to load?
	if ($template_name === false){
		return true;
        }
	
	$loaded = false;

		if (file_exists(settings\settings::incDir('views') .'/'.  $template_name . '.template.php'))
		{
			$loaded = true;
			//template_include($template_dir . '/' . $template_name . '.template.php', true);
                        template_include(settings\settings::incDir('views') .'/'. $template_name . '.template.php', true);
		}

	if ($loaded)
	{
		// If they have specified an initialization function for this template, go ahead and call it now.
		if (function_exists('template_' . $template_name . '_init'))
                        {
                            call_user_func('template_' . $template_name . '_init');
                            errors\uhoh::clearErrors();
                        }
	}
	// Cause an error otherwise.
	elseif ($template_name != 'Errors' && $template_name != 'index' && $fatal)
        {
            die();
		//echo ('TEMPLATE ERROR:: '.settings\settings::incDir('views') .'/'. $template_name . '.template.php');
        }elseif ($fatal)
        {
		die();
        }else
        {
		return false;
        }
}

function template_include($filename, $once = false)
{
	static $templates = array();

	// We want to be able to figure out any errors...
	//@ini_set('track_errors', '1'); //maybe later

	// Don't include the file more than once, if $once is true.
	if ($once && in_array($filename, $templates)){
		return;
	// Add this file to the include list, whether $once is true or not.
        }else{
		$templates[] = $filename;
		$file_found = file_exists($filename);
		if ($once && $file_found){
			require_once($filename);
                }elseif ($file_found){
			require($filename);
                }
        }
}

class header{
    public static function redirect($action = NULL){
        switch ($action){
            case 'forum':
                header("Location: ".settings\settings::URL('main').$action);
                die();
                break;
            case 'login':
                header("Location: ".settings\settings::URL('main').$action);
                die();
                break;
        }
    }
}

class action{
    public function current(){
        //grab current action=
        if ( isset( $_GET['action'] ) && !empty( $_GET['action'] ) ){
            return $_GET['action'];
        }else {
            header("Location: ".settings\settings::URL('main').'forum');
        }
    }
}
class subAction{
    public function current(){
        //grab current action=
        if ( isset( $_GET['sa'] ) && !empty( $_GET['sa'] ) ){
            return $_GET['sa'];
        }else {
            header("Location: ".settings\settings::URL('main').'forum');
        }
    }
}

?>