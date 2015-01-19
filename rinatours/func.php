<?php

function secure($dbh)
{
	if ($_POST['session_index']) { $session_index = $_POST['session_index']; $session_index = secure_letters($session_index); }
	else if ($_GET['session_index']) { $session_index = $_GET['session_index']; $session_index = secure_letters($session_index); }
	$our_time = time(); $time_diff = $our_time - 1200;
	file_this("select * from session where is_valid = 'yes' and session_index = '$session_index' and last_contact >= '$time_diff'", "main_page_1");
	$sth = $dbh->query("select * from session where is_valid = 'yes' and session_index = '$session_index' and last_contact >= '$time_diff'"); 
	$session_check = $sth->fetch();
	if ($session_check[code]) 
	{
		$dbh->exec("update session set last_contact = '$our_time' where code = '$session_check[code]'"); 
		$sth = $dbh->query("select * from users where code = '$session_check[usercode]'"); $user = $sth->fetch();
		$session_check[fullname] = $user[fullname];
		if ($_POST['status']) { $session_check[status] = secure_string($_post['status']); }
		else if ($_GET['status']) { $session_check[status] = secure_string($_post['status']); }
		foreach ($_POST as $param_name => $param_val)
		{
			$session_check[$param_name] = secure_extended($param_val);
		}
		foreach ($_GET as $param_name => $param_val)
		{
			$session_check[$param_name] = secure_extended($param_val);
		}
			
		return $session_check; 
	}
	else
	{

print <<<ENDLINE



<!--<?php

if($_GET[error_message])
{
	if ($_GET[error_message]) == "1")
	{
		$error_message = "Session Expired";
	}
	else if($_GET[error_message]) == "2")
	{
		$error_message]) == "Wrong Username/Password";
	}
}

?>-->

<!DOCTYPE html>
<head>
<title>Serilox CRM</title>
<meta charset="windows-1255">
<style>
body 
{
	background: -webkit-linear-gradient(left, #6A7174, #ABABAB); /* For Safari */
	background: -o-linear-gradient(left, #6A7174, #ABABAB); /* For Opera 11.1 to 12.0 */
	background: -moz-linear-gradient(left, #6A7174, #ABABAB); /* For Firefox 3.6 to 15 */
	background: linear-gradient(to left, #6A7174, #ABABAB); /* Standard syntax */
}
div.logo
{
	margin-top: 50px;
	border-radius: 15px;
	width: 670px;
	height: 320px;
	background: url('pictures/serilox_logo4.PNG') center center;
	box-shadow: 4px 4px 12px rgba(0, 0, 0, 0.7);
}

div.hand
{
	margin-top: 20px;
}

div.title
{
	width: 550px; 
	height: 40px;
	padding: 20px; 
	box-shadow: 4px 4px 12px rgba(0, 0, 0, 0.7);
	-webkit-text-shadow: 0 0 10px #fff;
        -o-text-shadow: 0 0 10px #fff;
        -moz-text-shadow: 0 0 10px #fff;
        text-shadow: 0 0 15px #ffffff;
	border-radius: 5px;
	background: -webkit-linear-gradient(left, #0C1C26, #183B50); /* For Safari */
        background: -o-linear-gradient(left, #0C1C26, #183B50); /* For Opera 11.1 to 12.0 */
        background: -moz-linear-gradient(left, #0C1C26, #183B50); /* For Firefox 3.6 to 15 */
        background: linear-gradient(to left, #0C1C26, #183B50); /* Standard syntax */
	font-family: arial;
	font-size: 28px;
	font-style: italic;
	color: #fff;
	margin-top: 20px;
	line-height: 35px;
}

table
{
	margin-top: 30px;
	width: 350px; 
	border: 1px solid rgba(255, 255, 255, 0.6);
	border-radius: 10px;
	padding: 20px;
	background-color: #rgba(36, 56, 68, 0.6);
	direction: rtl;
}

td
{
	# border: 1px solid rgba(255, 255, 255, 0.6);
	
	border-radius: 5px;
	padding: 5px;
	font-family: tahoma;
	font-size: 12px;
}

input.text
{
	background-color: #D0D4D5;
	padding: 4px;
	border: 1px solid #666;
	font-family: tahoma;
	font-size: 12px;
	font-weight: bold;
	border-radius: 4px;
	width: 150px;
	height: 22px;
	direction: ltr;
	padding-left: 10px;
}

input.submit
{
	width: 150px;
	height: 26px;
	background: -webkit-linear-gradient(bottom, #0C1C26, #183B50); /* For Safari */
        background: -o-linear-gradient(bottom, #0C1C26, #183B50); /* For Opera 11.1 to 12.0 */
        background: -moz-linear-gradient(bottom, #0C1C26, #183B50); /* For Firefox 3.6 to 15 */
        background: linear-gradient(to bottom, #0C1C26, #183B50); /* Standard syntax */
	font-family: tahoma;
	font-size: 14px;
	font-weight: bold;
	color: #fff;
	border: 1px solid #666;
	border-radius: 5px;
	margin-top: 15px;
}
	

</style>
</head>

<body onload="document.forms[0].username.focus();">
<div align="center">
<div class="logo"></div>

<div class="title">
	Operating business the friendly way
</div>

<form method="post" action="checkuser.php">

<h3 style="color: #314A07; font-family: arial; font-size: 16px;">Session Expired</h3>

<table style="direction: ltr">
	<tr><td>Username</td><td><input type="text" name="username" class="text"></td></tr>
	<tr><td>Password</td><td><input type="password" name="password" class="text"></td></tr>
	<tr><td colspan="2" style="text-align: center;"><input type="submit" class="submit" name="bn1" value="Sign In"></td></tr>
</table></form>
</div></body></html>


ENDLINE;







	$dbh->exec("update session set end_date = current_date, end_time = current_time, end_unix = '$our_time', is_valid = 'no' where session_index = '$session_index'");
	$dbh = NULL; exit;
	}
}

function file_this($our_string, $our_file)
{
	if(!$our_file) { $our_file = "file2"; }
	$our_string .= "\n";
	$our_file = fopen("/home/assaf/Material/check/$our_file","a");
	fwrite($our_file, $our_string);
	fclose($our_file);
}

function secure_string($our_string)
{
	$our_string = preg_replace('/[^0-9a-zA-Z\xe0-\xfa _\"\'\-\,\.]/', '', $our_string);
	return $our_string;
}

function secure_extended($our_string)
{
	$our_string = preg_replace('/[^0-9a-zA-Z\xe0-\xfa _\"\'\-\,\.\@]/', '', $our_string);
	return $our_string;
}

function secure_numbers($our_string)
{
	$our_string = preg_replace('/[^0-9\.]/', '', $our_string);
	return $our_string;
}

function secure_date($our_string)
{
	$our_string = preg_replace('/[^0-9\-]/', '', $our_string);
	return $our_string;
}

function secure_time($our_string)
{
	$our_string = preg_replace('/[^0-9\:]/', '', $our_string);
	return $our_string;
}

function secure_letters($our_string)
{
	$our_string = preg_replace('/[^0-9a-zA-Z]/', '', $our_string);
	return $our_string;
}

function fix_quotes($our_string)
{
	$our_string = preg_replace('/\"/', '/\"\"/', $our_string);
	return $our_string;
}

function print_footer()
{
	print "<footer class='main_footer'>© All right reserved to Serilox Software Solutions LTD. 2014</footer>";
}

function get_salesmen($dbh)
{
	$salesmen = array();
	$sth = $dbh->query("select * from users where is_salesman = 'yes'"); while ($details = $sth->fetch())
	{
		array_push($salesmen, $details);
	}
	
	return $salesmen;
}

function fix_time($our_time)
{
	$our_fields = split(":", $our_time);
	$our_fields[0] = sprintf("%02d", $our_fields[0]);
	$our_fields[1] = sprintf("%02d", $our_fields[1]);
	$new_time = "$our_fields[0]:$our_fields[1]:00";
	return $new_time;
}

