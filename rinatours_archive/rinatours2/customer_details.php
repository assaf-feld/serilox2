<?php

include "func.php";

$dbh = new PDO("mysql:dbname=rinatours","root","");
$session = secure($dbh);
$ic = rand();

if ($session[status] == "update") { update_details(); }

$sth = $dbh->query("select * from customer where code = '$session[customer_code]'"); $customer = $sth->fetch();
$customer[customer_name] = preg_replace("/\"/", '&quot;', $customer[customer_name]);
$customer[customer_last_name] = preg_replace("/\"/", '&quot;', $customer[customer_last_name]);
$customer[city] = preg_replace("/\"/", '&quot;', $customer[city]);

print <<<ENDLINE

<html>
<head>
<title>Customer Details</title>
<meta charset="utf-8">
<link rel="stylesheet" href="styles.css">
<script src="scripts.js"></script>
<script src="jquery-1.11.0.min.js"></script>
<script>
$(document).ready(function()
{
	$("#confirm_update_div").hide();
	$("#error_message_div").hide();
	$("#passport_exp_div").hide();
	$("#customer_details_new_lead").hide();
	$("#new_lead_button").click(function()
	{
		document.new_lead_iframe.location = "new_lead.php?session_index=$session[session_index]&customer_code=$session[customer_code]&ic=$ic";
		$("#customer_details_new_lead").fadeIn(300);
	});
});
</script>
</head>

<body class="customer_details">
<div class="customer_details_new_lead_parent" style="overflow: hidden;"><div class="customer_details_new_lead" id="customer_details_new_lead" style="overflow: hidden;">
<iframe src="new_lead.php?session_index=$session[session_index]" name="new_lead_iframe" class="customer_details_new_lead_iframe"></iframe></div></div>
<div class="confirm_update_div_parent"><div class="confirm_update_div" id="confirm_update_div">Details Saved</div></div>
<div class="confirm_update_div_parent"><div class="error_message_div" id="error_message_div"></div></div>
<div class="confirm_update_div_parent"><div class="passport_exp_div" id="passport_exp_div">Passport Expired</div></div>
<div class="serilox_main"></div>
<div align="center">
<h1>Customer View</h1>
<div style="width: 800px;"><div class="customer_details_title">General Info</div></div><p>&nbsp;</p>
<div class="customer_details_block">
<form method="post" action="update_customer.php" target="update_customer_iframe">
<input type="hidden" name="session_index" value="$session[session_index]">
<input type="hidden" name="customer_code" value="$session[customer_code]">
<input type="hidden" name="status" value="update">

<table class="customer_details_table">
<tr>
<td>Title</td>
<td>First Name</td>
<td>Last Name</td>
<td>Email</td>
</tr>
<tr>
<td><select name="title" class="customer_details_text"><option value=""></option>
ENDLINE;

$sth = $dbh->query("select * from table_details where table_code = '5'"); while ($item = $sth->fetch())
{
	print "<option value='$item[code]'"; if ($item[code] == $customer[title]) { print " selected"; } print ">$item[value]</option>";
}
print <<<ENDLINE
</select></td>
<td><input type="text" class="customer_details_text" name="customer_name" value="$customer[customer_name]"></td>
<td><input type="text" class="customer_details_text" name="customer_last_name" value="$customer[customer_last_name]"></td>
<td><input type="text" class="customer_details_text" name="email_1" value="$customer[email_1]"></td>
</tr>

<tr>
<td>Phone Nunmber 1</td>
<td>Phone Number 2</td>
<td>City</td>
<td>Birth Date</td>
</tr>
<tr>
<td><input type="text" class="customer_details_text" name="phone_1" value="$customer[phone_1]"></td>
<td><input type="text" class="customer_details_text" name="phone_2" value="$customer[phone_2]"></td>
<td><input type="text" class="customer_details_text" name="city" value="$customer[city]"></td>
<td><input type="date" class="customer_details_text" name="birth_date" value="$customer[birth_date]"></td>
</tr>
<tr>
<td>Passport Number</td>
<td>Passport Expiration Date</td>
</tr>
<tr>
<td><input type="text" class="customer_details_text" name="passport_number" value="$customer[passport_number]"></td>
<td><input type="date" class="customer_details_text" name="passport_exp_date" value="$customer[passport_exp_date]"></td>
</tr>
</table>
<p><input type="submit" name="bn1" value="Save" class="customer_details_submit"> 
<input type="button" name="bn1" value="New Lead" class="customer_details_submit" id="new_lead_button"> 
<input type="button" name="bn1a" value="Main" class="customer_details_submit" onclick="window.location='main_page.php?session_index=$session[session_index]&ic=$ic';"> 
<input type="button" name="bn1b" value="Customer Search" class="customer_details_submit" onclick="window.location='customer_search.php?session_index=$session[session_index]&ic=$ic';">
<input type="button" name="bn1b" value="New Customer" class="customer_details_submit" onclick="window.location='customer_details.php?session_index=$session[session_index]&ic=$ic';">
ENDLINE;
if ($session[lead_code])
{
	print <<<ENDLINE
	<input type="button" name="bn1b" value="Back To Lead" class="customer_details_submit" onclick="window.location='lead_details.php?session_index=$session[session_index]&lead_code=$session[lead_code]&ic=$ic';">
ENDLINE;
}

print <<<ENDLINE
</p>
</form></div>

<div style="width: 800px; margin-top: 15px;"><div class="customer_details_title">Recent Activities</div></div><p>&nbsp;</p>
<div class="customer_details_block">
<table class="customer_details_leads">
<tr><td>Date</td><td>Activity Type</td><td>User</td><td>Summary</td></tr>

ENDLINE;

$sth = $dbh->query("select * from assignments where customer_code = '$session[customer_code]' order by hitkash_unix desc limit 5"); while ($details = $sth->fetch())
{
	$sth1 = $dbh->query("select value from table_details where code = '$details[activity_type]'"); $activity_type = $sth1->fetch();
	$sth1 = $dbh->query("select fullname from users where code = '$details[usercode]'"); $username = $sth1->fetch();
	print "<tr><td>$details[hitkash_date]</td><td>$activity_type[value]</td><td>$username[fullname]</td><td>$details[free_text]</td></tr>";
}

print <<<ENDLINE
</table></div></div>
<iframe src="update_customer.php?session_index=$session[session_index]" width="0" height="0" border="0" name="update_customer_iframe"></iframe></body></html>;
ENDLINE;
$dbh = NULL;

function update_details()
{
	$session[customer_name] = preg_repalce('"', '\\"');
	$session[city] = preg_repalce('"', '\\"');
	if(!$session[customer_code])
	{
		$dbh->exec("insert into customer set customer_name = '$session[customer_name]', city = '$session[city]', phone_1 = '$session[phone_1]', phone_2 = '$session[phone_2]',
		email_1 = '$session[email_1]', birth_date = '$session[birth_date]', passport_number = '$session[passport_number]', passport_exp_date = '$session[passport_exp_date]'");
	}
	else
	{
		$dbh->exec("update customer set customer_name = '$session[customer_name]', city = '$session[city]', phone_1 = '$session[phone_1]', phone_2 = '$session[phone_2]',
		email_1 = '$session[email_1]', birth_date = '$session[birth_date]', passport_number = '$session[passport_number]', passport_exp_date = '$session[passport_exp_date]'
		where code = '$session[customer_code]'");
	}
}
