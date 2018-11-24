<?php
namespace sub\index;
if (!defined('IMP'))
{
    die();
}
use controllers\main as controllerMain;

class index{
    public static function start(){
        controllerMain\header::redirect('forum');
    }
}
?>