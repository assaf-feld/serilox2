<?php
//header('Content-Type: text/html; charset=utf-8');
include "func.php";
include "assaftime.php";

$dbh = new PDO("mysql:dbname=rinatours1;charset=utf8","root","");
$session = secure($dbh);
$sql = $dbh->prepare("SET NAMES UTF8");
$sql->execute();

file_this("status is: $session[status]", "ul1");
$ic = rand();

if ($session[status] == "add")
{
	$name = $_POST[name];
	$role = secure_string($session[role]);
	$phone = secure_numbers($session[phone]);
	$email = secure_extended($session[email]);
	
		$dbh_line = "insert into contact set customer_code = \"$session[customer_code]\", contact_name =\"$name\", role =\"$role\", phone =\"$phone\", email =\"$email\" ";
		/*file_this($dbh_line, "nl1"); */
		$dbh->exec($dbh_line);
		
		print <<<ENDLINE
	
		<html>
		<head>
		<script src="jquery-1.11.0.min.js"></script>
		<script>
		$(document).ready(function()
		{
			$("#customer_details_new_lead", window.parent.parent.document).fadeOut(300, function()
			{
				window.parent.parent.location = 'customer_details.php?customer_code=$session[customer_code]&session_index=$session[session_index]&ic=$ic';
			});
		});
		</script>
		</head>
	
		<body><script>alert("$name");</script></body></html>
ENDLINE;

}





$dbh = NULL;
