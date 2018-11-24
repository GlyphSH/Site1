<?php
namespace controllers\forum_main;
use settings as glob;
if (!defined('IMP'))
{
    die();
}else{
    require_once (glob\settings::incDir('controllers'). '/logauth.php');
}
use controllers\main as controllerMain;

class forum{
    public static function start(){
        controllerMain\loadTemplate('forum_main');
    }
}


?>