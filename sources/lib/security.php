<?php
namespace lib\security;
use settings as settings;
require_once (settings\settings::incDir('lib').'/gump.php');
use lib\gump as gump;
use \lib\error as errors;

  
 if (!defined('IMP'))
    {
        die();
    }

    class redir{
        public function GET($arrData)
        {
        $validator = new gump\GUMP();
        $usrSanGET = $validator->sanitize($arrData); // You don't have to sanitize, but it's safest to do so.
        $rules = array(
                'action'       => 'required|alpha_numeric'
        );
        $filters = array(
               // 'password'	  => '',
                'action'    	  => 'trim|sanitize_string'
        );
        $usrGET = $validator->filter($usrSanGET, $filters);
                $validated = $validator->validate(
                $usrGET, $rules
        );
                unset ($validator);
        if($validated === TRUE)
        {
                //Success
                return ($usrGET);
        }
        else
            {
            $errCount = count($validated);
            $errArray = array();
            for($i = 0; $i < $errCount; $i++) {
                $errArray = $errArray + array("field[".$i.']' => $validated[$i]['field'],
                                            "rule[".$i.']' => $validated[$i]['rule'],
                                            "param[".$i.']' => $validated[$i]['param']
                                        );
            }
            $objError = new errors\uhoh();
            $objError->setErrors('login', $errArray, $errCount);
            unset ($objError);
            return ($usrGET);
            }
        }
    }
//Let's sanitize user input...
class cleanLogin{
    public function POST($arrData)
    {
        $validator = new gump\GUMP();
        $usrSanPOST = $validator->sanitize($arrData); // You don't have to sanitize, but it's safest to do so.
        $rules = array(
                'email'       => 'required|valid_email'
        );
        $filters = array(
               // 'password'	  => '',
                'email'    	  => 'trim|sanitize_email'
        );
        $usrPOST = $validator->filter($usrSanPOST, $filters);
                $validated = $validator->validate(
                $usrPOST, $rules
        );
                unset ($validator);
        if($validated === TRUE)
        {
                //Success
                return ($usrPOST);
        }
        else
            {
            $errCount = count($validated);
            $errArray = array();
            for($i = 0; $i < $errCount; $i++) {
                $errArray = $errArray + array("field[".$i.']' => $validated[$i]['field'],
                                            "rule[".$i.']' => $validated[$i]['rule'],
                                            "param[".$i.']' => $validated[$i]['param']
                                        );
            }
            $objError = new errors\uhoh();
            $objError->setErrors('login', $errArray, $errCount);
            unset ($objError);
            return ($usrPOST);
            }
        }
}
class clean
{
    public function POST($arrData)
    {
        $validator = new gump\GUMP();
        $usrPOST = $validator->sanitize($arrData); // You don't have to sanitize, but it's safest to do so.
        $rules = array(
                'username'    => 'required|alpha_numeric|max_len,16|min_len,3',
                'email'       => 'required|valid_email'
        );
        $filters = array(
                'username' 	  => 'trim|sanitize_string',
                'email'    	  => 'trim|sanitize_email'
        );
        $usrPOST = $validator->filter($usrPOST, $filters);
                $validated = $validator->validate(
                $usrPOST, $rules
        );
                unset ($validator);
        if($validated === TRUE)
        {
                //Success
                return ($usrPOST);
        }
        else
            {
            $errCount = count($validated);
            $errArray = array();
            for($i = 0; $i < $errCount; $i++) {
                $errArray = $errArray + array("field[".$i.']' => $validated[$i]['field'],
                                            "rule[".$i.']' => $validated[$i]['rule'],
                                            "param[".$i.']' => $validated[$i]['param']
                                        );
            }
            $objError = new errors\uhoh();
            $objError->setErrors('registration', $errArray, $errCount);
            unset ($objError);
            }
        }
    }

// Emit some headers for some modicum of protection against nasties.
if (!headers_sent())
{
	// Future versions will make some of this configurable. This is primarily a 'safe' configuration for most cases for now.
	header('X-Frame-Options: SAMEORIGIN');
	header('X-XSS-Protection: 1');
	header('X-Content-Type-Options: nosniff');
        header('Content-Security-Policy: default-src \'none\'; script-src \'self\' *.cloudflare.com; connect-src \'self\'; img-src \'self\'; style-src \'self\';');
        //Force HTTPS
       // header('Strict-Transport-Security: max-age=31536000');
}

?>
