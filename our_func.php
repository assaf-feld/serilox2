<?php

function secure($session_index, $dbh)
{
	$our_time = time(); $time_diff = $our_time - 1200;
	file_this("select * from session where is_valid = 'yes' and session_index = '$session_index' and last_contact >= '$time_diff'", "main_page_1");
	$sth = $dbh->query("select * from session where is_valid = 'yes' and session_index = '$session_index' and last_contact >= '$time_diff'"); 
	$session_check = $sth->fetch();
	if ($session_check[code]) 
	{
		$dbh->exec("update session set last_contact = '$our_time' where code = '$session_check[code]'"); 
		$sth = $dbh->query("select * from users where code = '$session_check[usercode]'"); $user = $sth->fetch();
		$session_check[fullname] = $user[fullname];
		return $session_check; 
	}
	else
	{
		print <<<ENDLINE
		<!DOCTYPE html>
		<html>
			<head>
				<title>מערכת ניהול לקוחות</title>
				<meta http-equiv="Content-Type" content="text/html; charset=windows-1255">
				<style type="text/css">
					table { margin: auto; }
					body { background: url('Pictures/manage4copy3.png'); background-color: #000; color: #ffffff; text-align: center; font-family: sans-serif; }
					td { font-family: tahoma; font-size: 12px; }
					input { font-family: tahoma; font-size: 12px; }
				</style>
			</head>
		
			<body onload="document.forms[0].username.focus();">
				<p style="padding: 50px;"><img src="Pictures/serilox_temporary_logo.png" style="border: 0; padding: 30px;"></p>
				<h3 style="color: #ff0;">Session expired</h3>
				<form method="post" action="checkuser.php"><p><table width="400" border="0" cellpadding="2" cellspacing="2">
					<tr>
						<td>Username</td>
						<td><input type="text" size="20" name="username" dir="ltr"></td>
					</tr>
					<tr>
						<td>Password</td>
						<td><input type="password" size="20" name="password" dir="ltr"></td>
					</tr>
			</table></p><p><input type="submit" name="bn14" value="Entrance"></form>
			</body>
		</html>
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
	$our_string = preg_replace('/[^0-9a-zA-Z _\"\'\-\,\.]/', '', $our_string);
	return $our_string;
}

function secure_extended($our_string)
{
	$our_string = preg_replace('/[^0-9a-zA-Z _\"\'\-\,\.\@]/', '', $our_string);
	return $our_string;
}

function secure_numbers($our_string)
{
	$our_string = preg_replace('/[^0-9]/', '', $our_string);
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




?>
