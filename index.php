<?php
namespace index;
 
define('IMP', 1);

require_once('settings.php');
use settings as glob;
require_once(glob\settings::incDir('controllers') .'/main.php');
require_once(glob\settings::incDir('lib') .'/gump.php');

use lib\gump as gump;


class main{
            public static $actionArray = array(
                    'index' => array('index.php', 'start', 'sub\index'),
                    'login' => array('logauth.php', 'start', 'controllers\logauth'),
                    'login2' => array('logauth.php', 'start', 'controllers\logauth'),
                    'logout' => array('logauth.php', 'start', 'controllers\logauth'),
                    'forum' => array('forum_main.php','start', 'controllers\forum_main'),
                    'forumtopic' => array('forum_topic.php','start', 'controllers\forum_topic'),
                    'register' => array('register.php', 'start' ,'controllers\register'),
                    'register2' => array('register.php','start', 'controllers\register')
            );
            public $class_name = 'index';
            public $namespace = 'sub\index\\';
            public $class_method = 'start';
            public $class_inc = 'index.php';
            
            
    public function autoload($action)
            {
                if ($action != 'index'){
                    $this->class_name = $action;
                    $this->namespace = main::$actionArray[$_REQUEST['action']][2].'\\';
                    $this->class_method = main::$actionArray[$_REQUEST['action']][1];
                    $this->class_inc = main::$actionArray[$_REQUEST['action']][0];
                    include glob\settings::incDir('controllers') . '/' .$this->class_inc;
                }else{
                    include glob\settings::incDir('controllers') . '/index.php';
                }
                return;
            }
}


if (isset($_REQUEST['action']))
{
    if (isset(main::$actionArray[$_REQUEST['action']][0])){
        autocall($_REQUEST['action']);
    }else{
        autocall('index');
    }
}else{
    autocall('index');
}


function autocall($getAction){
    $mainObj = new main();
    $mainObj->autoload($getAction);
    $test1=$mainObj->namespace;
    $test2=$mainObj->class_name;
    $test3=$mainObj->class_method;
        return call_user_func($test1.$test2.'::'. $test3);
    
    unset ($mainObj);
}

abstract class UserIP{
    
    // Method to get the client ip address
    public function get() {

    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP'])){
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    }
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else if(isset($_SERVER['HTTP_X_FORWARDED'])){
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    }
    else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])){
        $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    }
    else if(isset($_SERVER['HTTP_FORWARDED_FOR'])){
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    }
    else if(isset($_SERVER['HTTP_FORWARDED'])){
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    }
    else if(isset($_SERVER['REMOTE_ADDR'])){
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    }
    else{
        $ipaddress = 'UNKNOWN';
    }
    $validator = new gump\GUMP();
    $arrData = array(
        'usrIP' => $ipaddress
    );
	$arrIP= array('userIP' => $ipaddress);
    $usrIP= $validator->sanitize($arrIP);
        $rules = array(
                'userIP'    => 'required|valid_ip'
        );
        $filters = array(
                'userIP' 	  => 'trim|sanitize_floats'
        );
        $usrIP = $validator->filter($usrIP, $filters);
        $validated = $validator->validate(
                $usrIP, $rules
        );
        if($validated === TRUE){
        }else{
            $ipaddress = NULL;
        }
    return $ipaddress;
    }
}
?>
