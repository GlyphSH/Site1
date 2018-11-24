<?php

 if (!defined('IMP'))
    {
	die();
    }
function template_forum_topic_init()
{
    global $app_name, $app_ver, $app_author;
echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			<link rel="stylesheet" type="text/css" href="sources/views/css/forum_topic.css" />
			<title>Forum Topic</title>
			</head>
			
			<body>
				<p>Coming Soon. ';
if (isset($_SESSION['name'])){
    echo'
<form name="logout" method="post" action="/logout">
  <label>
  <input name="submit" type="submit" id="submit" value="logout">
  </label>
</form>';
}

    //echo dirname(__FILE__);

    echo '
                                </p>
<table id="t01">
  <tr>
    <th>Topic</th>
    <th>Original Poster</th>
    <th>Posts</th> 
  </tr>
  <tr>
    <td>Hello World!</td>
    <td>Lughid</td>
    <td>1</td>
  </tr>
  <tr>
    <td>New Topic1</td>
    <td>username</td>
    <td>1</td>
  </tr>
</table>


				<p class="optimize">
				  '.$app_name.' '.$app_ver.' <small>by '.$app_author.'</small>
				</p>
			</body>
		</html>
';
}
?>