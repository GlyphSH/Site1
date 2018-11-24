<?php
 if (!defined('IMP'))
    {
	die();
    }
    
function template_register_init()
{
	global $app_name, $app_ver, $app_author;
	echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			<link rel="stylesheet" type="text/css" href="/sources/views/css/index.css" />
			<title>Register</title>
			</head>
			<body>
				<div class="wrapper">
                                    <h1>Register For An Account</h1>
                                    <p>To register: provide us with some basic information using
                                    the form below; please use valid information.</p>
                                    <form class="form" method="post" action="/register2">
					<input type="text" class="name" name="username"  placeholder="username"/>
					<div>
					  <p class="name-help">Enter the username you would like to create.</p>
					</div>
					<input type="password" class="password" name="password"  placeholder="password" />
					 <div>
					  <p class="password-help">Enter the password you would like to create.</p>
					</div>
					<input type="text" class="email" name="email"  placeholder="555@example.com"/>
					 <div>
					  <p class="email-help">Enter your email address.</p>
					</div>
					<input type="submit" class="submit" value="Register" />
                                    </form>
                                    <p style = "text-align: right;">Already have an account? Login <a href = "/login">here</a></p>
                                    '.regErrors().'
                                        <p><small>Please note that: Usernames and emails are filter protected.</small></p>
				</div>
				<p class="optimize">
				  '.$app_name.' '.$app_ver.' <small>by '.$app_author.'</small>
				</p>
				<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
				<script type="text/javascript" src="sources/views/js/register.js"></script>
			</body>
		</html>
';
}
function regErrors()
{
    global $loadErrors, $loadErrCount;
    $erros = NULL;
    if ($loadErrors != NULL AND $loadErrCount != NULL)
        {
            if ($loadErrCount >= 3){
                $realErrCount = $loadErrCount/3;
            }else{
                $realErrCount = $loadErrCount;
            }
            for ($i = 0; $i < $realErrCount;  $i++)
                {
                    $erros = $erros . '<b>'.strtoupper($loadErrors['field'.$i]) .' ERROR  #'.$i .'</b>:'. $loadErrors['rule'.$i] .' '. $loadErrors['param'.$i].'<br>';
                }
        }
        if ($erros != NULL){
            return '<p><b><font color="red">'.$erros.'</b></font></p>';
        }
}
?>