<?php

include "func.php";

$dbh = new PDO("mysql:dbname=rinatours1","root","");
$session = secure($dbh);
$ic = rand();

if($_POST['lead_code']) { $lead_code = $_POST['lead_code']; $lead_code = secure_numbers($lead_code); }
else if($_GET['lead_code']) { $lead_code = $_GET['lead_code']; $lead_code = secure_numbers($lead_code); }

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
<meta charset="windows-1255">
<link rel="stylesheet" href="styles.css">
<script src="scripts.js"></script>
<script src="jquery-1.11.0.min.js"></script>
<script>
$(document).ready(function()
{
	$("#lead_details_customer_div").hide();
	$("#lead_details_update_div").hide();
	$("#update_confirm").hide();
	$("#update_confirm").hide();
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
});
</script>
<script>
function update_lead(sub_status)
{
	var our_ic, our_addr;
	our_addr = "update_lead.php?lead_code=$lead_code&status=update&sub_statue=" + sub_stabus + "&ic=" + our_ic;
	document.update_lead_iframe.location = our_addr;
}
</script>
</head>

<body class="lead_details"><div class="serilox_main"></div>
<div class="confirm_update_div" id="update_confirm">Update Confirmed</div>
<div class="lead_details_customer_details_parent">
	<div class="lead_details_customer_details" id="lead_details_customer_div">
		<table class="lead_details_table">
			<tr><td>Name</td><td>Email</td><td>Phone Number 1</td><td>Phone Number 2</td></tr>
			<tr><td>$customer[customer_name]</td><td>$customer[email_1]</td><td>$customer[phone_1]</td><td>$customer[phone_2]</td></tr>
			<tr><td>City</td><td>Address</td><td>Passport Number</td><td>Passport Exp. Date</td></tr>
			<tr><td>$customer[city]</td><td>$customer[address]</td><td>$customer[passport_number]</td><td>$customer[passport_exp_date]</td></tr>
			<tr><td>Birth Date</td><td colspan="3">&nbsp;</td></tr>
			<tr><td>$customer[birth_date]</td><td colspan="2">&nbsp;</td>
			<td style="text-align: center;"><input type="button" class="customer_search_button" id="hide_customer" value="Hide"></td></tr>
		</table>
	</div>
</div>

<div align="center">
<h1>Lead Update</h1>
<p><div class="lead_details_details">
	<table class="lead_details_table">
		<tr><td>Customer</td><td>Phone Number 1</td><td>Phone Number 2</td><td>Media Source</td><td>Product</td>
		<td rowspan="2"><input type="button" class="lead_details_customer" value="Customer" id="view_customer">
		<tr><td>$customer[code]</td>
		<td>$customer[phone_1]</td>
		<td>$customer[phone_2]</td>
		<td>$media_source[value]</td>
		<td>$interested_in[value]</td>
		</tr>
	</table>
<div></p>
<div style="width: 800px;"><div class="main_new_leads">Activity</div></div><p>&nbsp;</p>
<p><table class="lead_details_table">
<tr><td>Date</td><td>Activity Type</td><td>User</td><td>Summary</td></tr>
ENDLINE;

$sth = $dbh->query("select * from assignments where customer_code = '$customer[code]' order by hitkash_unix desc limit 5"); while ($details = $sth->fetch())
{
	$sth1 = $dbh->query("select fullname from users where code = '$lead[usercode]'"); $user = $sth1->fetch();
	$sth1 = $dbh->query("select value from table_details where code = '$lead[activity_type]'"); $activity_type = $sth1->fetch();
	print <<<ENDLINE

	<tr><td>$details[hitkash_date]</td>
	<td>$activity_type[value]</td>
	<td>$user[fullname]</td>
	<td style="text-align: left; font-size: 10px;">$details[free_text]</td>
	</tr>
ENDLINE;
}

print <<<ENDLINE
</table></p>

<div style="width: 800px;"><div class="main_new_leads">Update</div></div><p>&nbsp;</p>
<div class="lead_details_update_div_main">
<p><form method="post" action="update_lead_details.php" target="lead_details_update_iframe">
<input type="hidden" name="session_index" value="$session[session_index]">
<input type="hidden" name="lead_code" value="$lead_code">
<input type="hidden" name="status" value="update">
<div id="lead_details_update_div">
	<table class="lead_details_update_details">
	<tr><td>Price Quote &nbsp; <input type="radio" name="as_results" value="price_quote"></td>
	<td>Not Interested &nbsp; <input type="radio" name="as_results" value="not_interested"></td>
	<td>Order &nbsp; <input type="radio" name="as_results" value="order"></td>
	</tr></table>
</div>

	<table class="lead_details_table">
	<tr>
	<td style="text-align: center;"><input type="submit" class="lead_details_update" value="No Answer" name="no_answer" onclick="update_lead('no_answer');">
	<td style="text-align: center;"><input type="submit" class="lead_details_update" value="Busy" name="busy" onclick="update_lead('busy');"></td>
	<td style="text-align: center;"><input type="submit" class="lead_details_update" value="Postpone" name="postpone" onclick="show_update_screen('postpone');"></td>
	<td style="text-align: center;"><input type="submit" class="lead_details_update" value="Update Lead" name="update_lead" id="lead_deatils_update_button" onclick="show_update_screen('order');"></td>
	</tr></table>
</form></p></div></div>
<iframe src="update_lead.php?session_index=$session[session_index]&ic=$ic" class="invisible_iframe" name="update_lead_iframe"></iframe>
</body></html>
ENDLINE;
$dbh = NULL;
