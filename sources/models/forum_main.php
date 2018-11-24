<?php
namespace models\forum_main;
require_once ($maindir.'/sources/lib/database.php');
require_once ($maindir.'/sources/lib/error.php');
use lib\database as database;
use lib\error as errors;

class boardindex extends index\getUserIP
{
    final public function __construct(){
    }
    
}
?>
