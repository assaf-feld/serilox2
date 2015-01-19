<?php

include "rand_string.php";
include "our_func.php";

$username = $_POST['username'];
$password= $_POST['password'];

$username = preg_replace("/[^0-9a-zA-Z]/", "", $username);
$password = preg_replace("/[^0-9a-zA-Z]/", "", $password);

file_this("username: $username, password: $password", "main_page_1");
$dbh = new PDO("mysql:host=localhost;dbname=serilox","root","");
$sth = $dbh->query("select * from users where username = '$username' and password = '$password'"); $user_check = $sth->fetch();
file_this("the code is: $user_check[code]", "main_page_1");
if ($user_check[code])
{
	$our_time = time(); $ic = rand();
	$session_index = create_session_index(15);
	$dbh->exec("insert into session values(NULL, '$user_check[code]', '$session_index', current_date, current_time, '$our_time', NULL, NULL, NULL, 'yes', '$our_time')");
	$dbh = NULL;
	$ic = rand();
	print <<<ENDLINE
	<htm>
	<head>
	<meta http-equiv="refresh" content="0; url=main_page.php?session_index=$session_index&ic=$ic">
	</head>
	<body>
	</body>
	</html>
ENDLINE;
	$dbh = NULL;
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
				body { background: url('Pictures/manage4copy3.png'); background-color: #000; color: #ffffff; text-align: center; }
				td { font-family: tahoma; font-size: 12px; }
				input { font-family: tahoma; font-size: 12px; }
			</style>
		</head>
	
		<body onload="document.forms[0].username.focus();">
			<p style="padding: 50px;"><img src="Pictures/serilox_temporary_logo.png" style="border: 0; padding: 30px;"></p>
			<h3 style="color: #ff0;">Wrong username or password</h3>
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
	$dbh = NULL; exit;
}

?>
