<?php

include "our_func.php";
include "assaftime.php";

if($_POST['session_index'])
{
	$session_index = $_POST['session_index']; $session_index = secure_letters($session_index);
	$customer_code = $_POST['customer_code']; $customer_code = secure_numbers($customer_code);
	$status = $_POST['status']; $status = secure_string($status);
}
else if($_GET['session_index'])
{
	$session_index = $_GET['session_index']; $session_index = secure_letters($session_index);
	$customer_code = $_GET['customer_code']; $customer_code = secure_numbers($customer_code);
	$status = $_GET['status']; $status = secure_string($status);
}

$dbh = new PDO("mysql:dbname=serilox","root","");
$session = secure($session_index, $dbh);

print <<<ENDLINE

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Last Connections</title>
<link rel="stylesheet" href="our_styles.css">
<script src="our_scripts.js"></script>
</head>

<body>
<p style="width: 170px; margin: auto; padding: 15px;"><input type="button" class="horizontal_button" name="bn1" value="Done" onclick="window.parent.fadeout_contacts();"></p>
<p><table class="last_assignments_table">
<tr><td>Date and Time</td><td>Bizua Date and Time</td><td>Username</td><td>In Charge</td><td>Assignment Type</td><td>Free text</td></tr>

ENDLINE;

$sth = $dbh->query("select * from assignments where customer_code = '$customer_code' order by hitkash_unix desc"); while ($details = $sth->fetch())
{
	$hitkash_date = mysql_to_presentable($details[hitkash_date], "long");
	$bizua_date = mysql_to_presentable($details[bizua_date], "long");
	$sth1 = $dbh->query("select value from table_details where code = '$details[assignment_type]'"); $assignment_type = $sth1->fetch();
	$sth1 = $dbh->query("select fullname from users where code = '$details[usercode]'"); $username = $sth1->fetch();
	
	print <<<ENDLINE
	<tr><td>$hitkash_date $details[hitkash_time]</td>
	<td>$bizua_date $details[$bizua_time]</td>
	<td>$username[fullname]</td>
	<td>$details[in_charge]</td>
	<td>$assignment_type[value]</td>
	<td>$details[free_text]</td>
	</tr>
ENDLINE;
}

print <<<ENDLINE

</table></p></body></html>

ENDLINE;

$dbh = NULL;
?>
