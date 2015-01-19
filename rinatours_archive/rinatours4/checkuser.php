<?php

include "rand_string.php";
include "func.php";

$username = $_POST['username'];
$password= $_POST['password'];

$username = preg_replace("/[^0-9a-zA-Z]/", "", $username);
$password = preg_replace("/[^0-9a-zA-Z]/", "", $password);

file_this("username: $username, password: $password", "main_page_1");
$dbh = new PDO("mysql:host=localhost;dbname=rinatours","root","");
$sth = $dbh->query("select * from users where username = '$username' and password = '$password'"); $user_check = $sth->fetch();
file_this("the code is: $user_check[code]", "main_page_1");
if ($user_check[code])
{
	$our_time = time(); $ic = rand();
	$session_index = create_session_index(15);
	$dbh->exec("insert into session values(NULL, '$user_check[code]', '$session_index', current_date, current_time, '$our_time', NULL, NULL, NULL, 'yes', '$our_time', NULL, NULL)");
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

<h3 style="color: #314A07; font-family: arial; font-size: 16px; display: block; width: 400px; height: 30px; border-radius: 5px; background-color: #EDEDBF; line-height: 31px;">Wrong Username/Password</h3>

<table style="direction: ltr;">
	<tr><td>Username</td><td><input type="text" name="username" class="text"></td></tr>
	<tr><td>Password</td><td><input type="password" name="password" class="text"></td></tr>
	<tr><td colspan="2" style="text-align: center;"><input type="submit" class="submit" name="bn1" value="Entrance"></td></tr>
</table></form>
</div></body></html>














ENDLINE;
	$dbh = NULL; exit;
}

?>
