<?php

include "func.php";

$dbh = new PDO("mysql:dbname=rinatours","root","");
$session = secure($dbh);
$ic = rand();

if($_POST['lead_code']) { $lead_code = $_POST['lead_code']; $lead_code = secure_numbers($lead_code); }
else if($_GET['lead_code']) { $lead_code = $_GET['lead_code']; $lead_code = secure_numbers($lead_code); }
$sth = $dbh->query("select * from users where code = '$session[usercode]'"); $user = $sth->fetch();

if ($status == "update") { update_details(); }

$sth = $dbh->query("select * from assignments where code = '$lead_code'"); $lead = $sth->fetch();
$sth = $dbh->query("select * from customer where code = '$lead[customer_code]'"); $customer = $sth->fetch();
$sth = $dbh->query("select * from table_details where code = '$lead[media_source]'"); $media_source = $sth->fetch();
$sth = $dbh->query("select * from table_details where code = '$lead[interested_in]'"); $interested_in = $sth->fetch();
$ic = rand();

print <<<ENDLINE

<html>
<head>
<title>Lead Upadte</title>
<meta charset="utf-8">
<link rel="stylesheet" href="styles.css">
<script src="scripts.js"></script>
<script src="jquery-1.11.0.min.js"></script>
<script>
$(document).ready(function()
{
	$("#lead_details_customer_div").hide();
	$("#lead_details_order").hide();
	$("#lead_details_postpone").hide();
	$("#update_confirm").hide();
	$("#error_message_div").hide();
	$("#view_customer").click(function()
	{
		$("#lead_details_customer_div").fadeIn(300);
	});
	$("#hide_customer").click(function()
	{
		$("#lead_details_customer_div").fadeOut(300);
	});
	$("#lead_details_update_button").click(function()
	{
		$("#lead_details_update_div").fadeIn(300);
	});
	$("#postpone_button").click(function()
	{
		$("#lead_details_postpone").fadeIn(300);
		$("#lead_details_order").fadeOut(300);
	});
	$("#order_button").click(function()
	{
		$("#lead_details_order").fadeIn(300);
		$("#lead_details_postpone").fadeOut(300);
	});
	$("#postpone_hide_button").click(function()
	{
		$("#lead_details_postpone").fadeOut(300);
	});
	$("#order_hide_button").click(function()
	{
		$("#lead_details_order").fadeOut(300);
	});
});
</script>
<script>
function update_lead(sub_status)
{
	var our_ic, our_addr;
	our_ic = Math.random();
	our_addr = "update_lead.php?session_index=$session[session_index]&lead_code=$lead_code&status=update&sub_status=" + sub_status + "&ic=" + our_ic;
	document.update_lead_iframe.location = our_addr;
}

</script>
</head>

<body class="lead_details"><div class="serilox_main"></div>
<div class="rinatours_main"></div>
<div class="user_picture"><img src="users_photos/$user[file_name]" width="72" height="72"></div>
<div class="confirm_update_div_parent"><div class="confirm_update_div" id="update_confirm">Update Confirmed</div></div>
<div class="confirm_update_div_parent"><div class="error_message_div" id="error_message_div"></div></div>
<div class="lead_details_customer_details_parent">
	<div class="lead_details_customer_details" id="lead_details_customer_div">
		<table class="lead_details_table">
			<tr><td>First Name</td><td>Last Name</td><td>Email</td><td>Phone Number 1</td><td>Phone Number 2</td></tr>
			<tr><td>$customer[customer_name]</td><td>$customer[customer_last_name]</td><td>$customer[email_1]</td><td>$customer[phone_1]</td><td>$customer[phone_2]</td></tr>
			<tr><td>City</td><td>Address</td><td>Passport Number</td><td>Passport Exp. Date</td><td>Birth Date</td></tr>
			<tr><td>$customer[city]</td><td>$customer[address]</td><td>$customer[passport_number]</td><td>$customer[passport_exp_date]</td><td>$customer[birth_date]</td></tr>
			<td style="text-align: center;" colspan="5"><input type="button" class="customer_search_button" id="hide_customer" value="Hide"> 
			<input type="button" class="customer_search_button" value="Edit" onclick="window.location='customer_details.php?session_index=$session[session_index]&customer_code=$customer[code]&lead_code=$lead_code&ic=$ic';"></td></tr>
		</table>
	</div>
</div>

<div align="center">
<h1>Lead Update</h1>

<p><div class="lead_details_details">
	<table class="lead_details_table">
		<tr><td>Customer Name</td><td>Phone Number 1</td><td>Phone Number 2</td><td>Media Source</td><td>Interested In</td>
		<td rowspan="2" style="text-align: center;"><input type="button" class="lead_details_customer" value="Customer" id="view_customer">
		<tr><td>$customer[customer_name] $customer[customer_last_name]</td>
		<td>$customer[phone_1]</td>
		<td>$customer[phone_2]</td>
		<td>$media_source[value]</td>
		<td>$interested_in[value]</td>
		</tr>
	</table>
<div></p>




<div style="width: 900px;"><div class="main_new_leads">Update</div></div><p>&nbsp;</p>
<p><table class="lead_details_table">
<tr>
<td style="text-align: center;"><input type="submit" class="lead_details_update" value="No Answer" onclick="update_lead('no_answer');">
<td style="text-align: center;"><input type="submit" class="lead_details_update" value="Busy" onclick="update_lead('busy');"></td>
<td style="text-align: center;"><input type="button" class="lead_details_update" value="Postpone" id="postpone_button"></td>
<td style="text-align: center;"><input type="button" class="lead_details_update" value="Update Lead" id="order_button"></td>
</tr></table></p>








<div style="width: 900px;"><div class="main_new_leads">Recent Activities</div></div><p>&nbsp;</p>
<p><table class="lead_details_table">
<tr><td>Scheduled On</td><td>Scheduled For</td><td>Activity Type</td><td>User</td><td>Summary</td></tr>
ENDLINE;

$sth = $dbh->query("select * from assignments where customer_code = '$customer[code]' order by hitkash_unix desc limit 5"); while ($details = $sth->fetch())
{
	$sth1 = $dbh->query("select fullname from users where code = '$details[usercode]'"); $user = $sth1->fetch();
	$sth1 = $dbh->query("select value from table_details where code = '$details[activity_type]'"); $activity_type = $sth1->fetch();
	file_this("select value from table_details where code = '$details[activity_type]'", "ld1");
	print <<<ENDLINE

	<tr><td>$details[hitkash_date] $details[hitkash_time]</td>
	<td>$details[bizua_date] $details[bizua_time]</td>
	<td>$activity_type[value]</td>
	<td>$user[fullname]</td>
	<td style="text-align: left; font-size: 10px;">$details[free_text]</td>
	</tr>
ENDLINE;
}

print <<<ENDLINE
</table></p>

<p><input type="button" name="bn8" value="Main" class="main_button_main" onclick="window.location='main_page.php?session_index=$session[session_index]&ic=$ic';"></p>
<div class="lead_details_postpone_parent">
	<div class="lead_details_postpone" id="lead_details_postpone">
		<form method="post" action="update_lead.php" target="update_lead_iframe">
		<input type="hidden" name="session_index" value="$session[session_index]">
		<input type="hidden" name="status" value="update_postpone">
		<input type="hidden" name="lead_code" value="$lead[code]">
		<table class="lead_details_postpone">
		<tr><td>Scheduled For</td><td>Scheduled For (time)</td><td>Summary</td></tr>		
		<tr><td><input type="date" name="bizua_date" class="lead_details_date"></td>
		<td><select name="bizua_hour"><option value=""></option>
		<option value="05">05</option>
		<option value="06">06</option>
		<option value="07">07</option>
		<option value="08">08</option>
		<option value="09">09</option>
		<option value="10">10</option>
		<option value="11">11</option>
		<option value="12">12</option>
		<option value="13">13</option>
		<option value="14">14</option>
		<option value="15">15</option>
		<option value="16">16</option>
		<option value="17">17</option>
		<option value="18">18</option>
		<option value="19">19</option>
		<option value="20">20</option>
		<option value="21">21</option>
		<option value="22">22</option>
		<option value="23">23</option>
		</select> <select name="bizua_minute"><option value=""></option>
		<option value="05">05</option>
		<option value="10">10</option>
		<option value="15">15</option>
		<option value="20">20</option>
		<option value="25">25</option>
		<option value="30">30</option>
		<option value="35">35</option>
		<option value="40">40</option>
		<option value="45">45</option>
		<option value="50">50</option>
		<option value="55">55</option>
		</select></td>
		<td><textarea class="lead_details_textarea" name="free_text"></textarea></td></tr>
		</table>
		<p><input type="submit" name="bn7" value="Update" class="customer_search_submit">
		<input type="button" id="postpone_hide_button" class="customer_search_submit" value="Hide">
		</p></form>
	</div>
</div>

<div class="lead_details_order_parent">
	<div class="lead_details_order" id="lead_details_order">
		<form method="post" action="update_lead.php" target="update_lead_iframe">
		<input type="hidden" name="session_index" value="$session[session_index]">
		<input type="hidden" name="status" value="update_order">
		<input type="hidden" name="lead_code" value="$lead[code]">
		<table class="lead_details_order">
		<tr><td>Scheduled For</td><td>Scheduled For (time)</td><td>Summary</td></tr>		
		<tr><td><input type="date" name="bizua_date" class="lead_details_date"></td>
		<td><select name="bizua_hour"><option value=""></option>
		<option value="05">05</option>
		<option value="06">06</option>
		<option value="07">07</option>
		<option value="08">08</option>
		<option value="09">09</option>
		<option value="10">10</option>
		<option value="11">11</option>
		<option value="12">12</option>
		<option value="13">13</option>
		<option value="14">14</option>
		<option value="15">15</option>
		<option value="16">16</option>
		<option value="17">17</option>
		<option value="18">18</option>
		<option value="19">19</option>
		<option value="20">20</option>
		<option value="21">21</option>
		<option value="22">22</option>
		<option value="23">23</option>
		</select> <select name="bizua_minute"><option value=""></option>
		<option value="05">05</option>
		<option value="10">10</option>
		<option value="15">15</option>
		<option value="20">20</option>
		<option value="25">25</option>
		<option value="30">30</option>
		<option value="35">35</option>
		<option value="40">40</option>
		<option value="45">45</option>
		<option value="50">50</option>
		<option value="55">55</option>
		</select></td>
		<td><textarea class="lead_details_textarea" name="free_text"></textarea></td></tr>
		<tr><td>Interested In</td><td>Activity Type</td><td>Result</td></tr>
		<tr><td><select name="interested_in"><option value=""></option>
ENDLINE;
		$sth = $dbh->query("select * from table_details where table_code = \"4\""); while ($details = $sth->fetch())
		{
			print "<option value=\"$details[code]\">$details[value]</option>";
		}
		print "</select></td><td><select name=\"activity_type\"><option value=\"\"></option>";
		$sth = $dbh->query("select * from table_details where table_code = \"3\""); while ($details = $sth->fetch())
		{
			print "<option value=\"$details[code]\">$details[value]</option>";
		}
		print <<<ENDLINE
		</select></td><td><select name="as_result"><option value=""></option>
		<option value="price_quote">Price Quote</option>
		<option value="not_interested">Not Interested</option>
		<option value="order">Order</option>
		</select></td></tr>
		
		</table><p><input type="submit" name="bn7" value="Update" class="customer_search_submit"> 
		<input type="button" id="order_hide_button" class="customer_search_submit" value="Hide"></p></form>
	</div>
</div>

<iframe src="update_lead.php?session_index=$session[session_index]&ic=$ic" class="invisible_iframe" name="update_lead_iframe"></iframe>
</body></html>
ENDLINE;
$dbh = NULL;
