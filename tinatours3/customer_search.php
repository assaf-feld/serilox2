<?php

include "func.php";

$dbh = new PDO ("mysql:dbname=rinatours","root","");
$session = secure($dbh);
$ic = rand();
$sth = $dbh->query("select * from users where code = '$session[usercode]'"); $user = $sth->fetch();
$dbh->exec("update session set in_lead = NULL where session_index = '$session[session_index]'");

print <<<ENDLINE

<html>
<head>
<title>Customer Search</title>
<meta charset="windows-1255">
<link rel="stylesheet" href="styles.css">
<script src="scripts.js"></script>
<script src="jquery-1.11.0.min.js"></script>
</head>

<body class="customer_search" onload="document.forms[0].name.focus();">
<div class="serilox_main"></div>
<div class="rinatours_main"></div>
<div class="user_picture"><img src="users_photos/$user[file_name]" width="72" height="72"></div>
<div align="center">
<h1>Customer Search</h1>
<div class="customer_search_fields">
	<form method="post" action="customer_search.php">
	<input type="hidden" name="session_index" value="$session[session_index]">
	<input type="hidden" name="status" value="update">
	<p><table class="customer_search_table">
	<tr><td>By Number</td><td>by Name</td><td>By Email</td><td>By Phone Number</td><tr></tr>
	<tr>
	<td><input type="text" class="customer_search_text" name="number" value="$session[number]"></td>
	<td><input type="text" class="customer_search_text" name="name" value="$session[name]"></td>
	<td><input type="text" class="customer_search_text" name="email" value="$session[email]"></td>
	<td><input type="text" class="customer_search_text" name="phone" value="$session[phone]"></td>
	</tr><tr>
	<td>Creation Date From</td><td>Creation Date To</td><td>Status</td><td>&nbsp;</td></tr>
	<tr><td><input type="date" class="customer_search_text" name="creation_date_from" value="$session[creation_date_from]"></td>
	<td><input type="date" class="customer_search_text" name="creation_date_to" value="$session[creation_date_to]"></td>
	<td><select name="customer_status" class="customer_search_text"><option value=""></option>
ENDLINE;

	print "<option value=\"active\""; if ($session[customer_status] == "active") { print " selected"; } print ">Active</option>";
	print "<option value=\"not_active\""; if ($session[customer_status] == "not_active") { print " selected"; } print ">Not Active</option>";
	print <<<ENDLINE
	</select></td><td>&nbsp;</td>
	</tr></table></p>
	<p><input type="submit" name="bn1" value="Go" class="customer_search_submit"> 
	<input type="button" name="bn1a" class="customer_search_submit" value="New Customer" onclick="window.location='customer_details.php?session_index=$session[session_index]&ic=$ic';">
	<input type="button" name="bn1b" class="customer_search_submit" value="Main" onclick="window.location='main_page.php?session_index=$session[session_index]&ic=$ic';"></p>
	</form>
</div>

ENDLINE;

if ($session[status] == "update")
{
	print <<<ENDLINE

	<div class="customer_search_results">
	<table class="customer_search_results">
	<tr><td>Customer Number</td><td>First Name</td><td>Last Name</td><td>Email</td><td>Phone Number</td><td>Phone 2</td><td>City</td><td>Password Exp. Date</td><td>&nbsp;</td></tr>

ENDLINE;

	# $name = $_POST[name]; $name = secure_string($name);
	# $email = $_POST[email]; $email = secure_extended($email);
	# $phone = $_POST[phone]; $phone = secure_string($name);
	# $city = $_POST[city]; $city = secure_string($city);
	
	$name = addslashes($session[name]);
	$email = addslashes($session[email]);
	$phone = addslashes($session[phone]);
	$city = addslashes($session[city]);
	$number = $session[number];
	$creation_date_from = addslashes($session[creation_date_from]);
	$creation_date_to = addslashes($session[creation_date_to]);
	$customer_status = addslashes($session[customer_status]);

	$query = "select * from customer where 1 = 1 ";
	if ($name) { $query .= " and (customer_name regexp '$name' or customer_last_name regexp '$name') "; }
	if ($email) { $query .= " and email_1 regexp '$email' "; }
	if ($phone) { $query .= " and (phone_1 regexp '$phone' or phone_2 regexp '$phone') "; }
	if ($city) { $query .= " and city regexp '$city' "; }
	if ($number) { $query .= " and code = '$number' "; }
	if ($creation_date_from) { $query .= " and create_date >= '$creation_date_from' "; }
	if ($creation_date_to) { $query .= " and create_date <= '$creation_date_to' "; }
	if ($customer_status) { $query .= " and status = '$customer_status' "; }

	# $query .= " order by hitkash_unix desc limit 6";

	file_this($query, "cs1");
	$sth = $dbh->query($query); while ($details = $sth->fetch())
	{
		print "<tr><td>$details[code]</td><td>$details[customer_name]</td><td>$details[customer_last_name]</td><td>$details[email_1]</td><td>$details[phone_1]</td><td>$details[phone_2]</td><td>$details[city]</td>
		<td>$details[passport_exp_date]</td><td style='text-align: center;'><input type='button' class='customer_search_button' value='Show' 
		onclick=\"window.location='customer_details.php?session_index=$session[session_index]&customer_code=$details[code]&ic=$ic';\"></td></tr>";
	}

	print "</table></div>";
}

print "</body></html>";
$dbh = NULL;

