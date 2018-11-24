<?php

if (!defined('IMP'))
    {
	die();
    }
    
function template_logauth_init()
{
	global $app_name, $app_ver, $app_author;
	echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			<link rel="stylesheet" type="text/css" href="/sources/views/css/index.css" />
			<title>Login</title>
			</head>
			<body>
				<div class="wrapper">
				  <h1>Login to Your Account</h1>
				  <p>To login: provide us with your credentials using
				  the form below; please make sure to use valid information.</p>
				  <form class="form" method="post" action="/login2">
					<input type="text" class="email" name="email"  placeholder="555@example.com"/>
					<div>
					  <p class="email-help">Enter the email.</p>
					</div>
					<input type="password" class="password" name="password"  placeholder="password" />
					 <div>
					  <p class="password-help">Enter the password.</p>
					</div>
                                        <div>
                                        <p style = "text-align: right;">Remember Me: <input type="checkbox" name="rememberme" value="1"  checked disabled></p>
                                        </div>
					<input type="submit" class="submit" value="Login" />
				  </form>
                                  
                                  <p style = "text-align: right;">Need an account? Register <a href = "/register">here</a></p>
				  '.regErrors().'
                                      <p><small>Please note that: Emails must be valid.</small></p>
				</div>
                                
				<p class="optimize">
				  '.$app_name.' '.$app_ver.' <small>by '.$app_author.'</small>
				</p>

				<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
				<script type="text/javascript" src="sources/views/js/register.js"></script>
			</body>
		</html>';
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