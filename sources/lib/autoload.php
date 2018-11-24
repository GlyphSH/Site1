<?php
//Autoload library for the entire website is here!!

$aLFiles = array(

'lib' => array(
'filter' => 'gump.php',
'database' => 'database.php',
'errors' => 'error.php',
'security' => 'security.php'),

'controllers' => array(
'forumMain' => 'forum.main.php',
'main' => 'main.php', //king controller
'register' => 'register.php'),

'models' => array(
'forumMain' => 'forum.main.php',
'register' => 'register.php'),

'views' => array(
'forumMain' => 'forum.main.template.php',
'register' => 'register.template.php')

);
/*
var_dump ($aLFiles);
foreach ($aLFiles as $value) {
    print_r ("Value: ".$value."<br />\n");
}
*/
foreach($aLFiles as $innerArray){

	foreach($innerArray as $value){
		echo $value."<br>";
	}
}

/*
function __autoload($className) {
      if (file_exists($className . '.php')) {
          require_once $className . '.php';
          return true;
      }
      return false;
} 
*/
?>