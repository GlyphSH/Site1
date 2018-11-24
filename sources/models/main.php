<?php
namespace models\main;
use settings as glob;
require_once (glob\settings::incDir('lib').'/database.php');
use lib\database as database;


class main{
 public function populate($mainEmail){
                    $database = new database\database();
                    $database->beginTransaction();
                    $database->query('SELECT * FROM users WHERE email = :allEmail;');
                    $database->dbBind(':allEmail', $mainEmail);
                    $database->dbExecute();
                    $mainAll = $database->resultset();
                    $database->endTransaction();
                    //start building the array.
                    return $mainAll;
 }
 public function lastUser(){
                    $database = new database\database();
                    $database->beginTransaction();
                    $database->query('SELECT currval(pg_get_serial_sequence(\'users\',\'id\'))');
                    $database->dbExecute();
                    $lastID = $database->resultset();
                    $database->endTransaction();
     return $lastID;
 }
}
?>
