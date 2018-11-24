<?php
namespace settings;

if (!defined('IMP'))
    {
    die();
    }

$app_name       = 	"My Website";
$app_author     = 	"Kevin Kessler";
$app_ver	    =	"ALPHA";
$cookiename     =   "IMPCookie89";


class settings
{
    static function URL($name)
            {
                switch ($name)
                {
                //Defensive programming (breaks)
                    case 'main':
                        return 'https://example.com/';
                        break;
                    case 'login':
                        return 'https://example.com/login';
                        break;
                    case 'views':
                        return 'https://example.com/';
                        break;
                    case 'models':
                        return 'https://example.com/';
                        break;
                    case 'controllers':
                        return 'https://example.com/';
                        break;
                    default:
                        return 'https://example.com/';
                        break;
                }
            }
    static function Database($option)
            {
                switch (strtoupper($option))
                {
                //Defensive programming (breaks)
                    case 'SERVER':
                        return 'localhost';
                        break;
                    case 'NAME':
                        return '';
                        break;
                    case 'USER':
                        return '';
                        break;
                    case 'PASS':
                        return '';
                        break;
                }
            }
    static function incDir($name)
            {
                switch (strtoupper($name))
                {
                //Defensive programming (breaks;)
                    case 'ROOT':
                        return __dir__;
                        break;
                    case 'CONTROLLERS':
                        return __dir__.'/sources/controllers';
                        break;
                    case 'MODELS':
                        return __dir__.'/sources/models';
                        break;
                    case 'VIEWS':
                        return __dir__.'/sources/views';
                        break;
                    case 'LIB':
                        return __dir__.'/sources/lib';
                        break;
                }
            }
}
?>
