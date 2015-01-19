<?php

include "func.php";
include "assaftime.php";

$dbh = new PDO("mysql:dbname=rinatours","root","");
$session = secure($dbh);
$status = $session[status];
$ic = rand();
$our_time = time();
$sth = $dbh->query("select * from users where code = '$session[usercode]'"); $user = $sth->fetch();
$sth = $dbh->query("select * from user_category where code = '$user[category_code]'"); $category = $sth->fetch();
$dbh->exec("update session set in_lead = NULL where session_index = '$session[session_index]'");
$dbh->exec("update administration set refresh_main = \"no\"");

print <<<ENDLINE

<html>
<head>
<title>RinaTours - Main Page</title>
<meta charset="windows-1255">
<link rel="stylesheet" href="styles.css">
<script src="jquery-1.11.0.min.js"></script>
<script src="scripts.js"></script>
<script>
var lead_general;
$(document).ready(function()
{
	$('body').css('display','none');
	$('body').fadeIn(300);
	$("#lead_occupied").hide();
	$(".main_lead_details_customer_details").hide();
	$("#hide_lead_details").click(function()
	{
		$(".main_lead_details_customer_details").fadeOut(300);
	});
});

function go_to_lead(lead_code)
{
	var our_ic, our_addr, our_xhr, our_check;
	our_ic = Math.random();
	lead_general = lead_code;
	our_addr = "someone_in_lead.php?session_index=$session[session_index]&lead_code=" + lead_code + "&ic=" + our_ic;
	our_xhr = new XMLHttpRequest();
	our_xhr.open("get", our_addr, false);
	our_xhr.send(null);
	our_check = our_xhr.responseText;
	// alert(our_check);
	if (our_check == "CLEAR")
	{
		our_addr = "lead_details.php?session_index=$session[session_index]&lead_code=" + lead_code + "&ic=" + our_ic;
		window.location = our_addr;
		return true;
	}
	else
	{
		$("#lead_occupied_by").text("This lead is occupied by " + our_check + ".");
		$("#lead_occupied").fadeIn(300);
	}
}

function run_over_lead()
{
	var our_ic, our_addr;
	our_ic = Math.random();
	our_addr = "lead_details.php?session_index=$session[session_index]&lead_code=" + lead_general + "&ic=" + our_ic;
	window.location = our_addr;
}

function hide_lead_occupied()
{
	$("#lead_occupied").fadeOut(300);
}

function show_lead_details(lead_code)
{
	var our_ic, our_addr;
	our_ic = Math.random();
	if(!lead_general) { lead_general = lead_code; }
	our_addr = "full_lead_details.php?session_index=$session[session_index]&lead_code=" + lead_code + "&ic=" + our_ic;
	document.lead_details_iframe.location = our_addr;
	$(".main_lead_details_customer_details").fadeIn(300);
}

function edit_lead_details(lead_code)
{
	var our_ic, our_addr;
	our_ic = Math.random();
	our_addr = "actual_lead_details.php?session_index=$session[session_index]&lead_code=" + lead_code + "&ic=" + our_ic;
	window.location = our_addr;
}

function update_lead()
{
	var our_ic, our_addr;
	our_ic = Math.random();
	our_addr = "actual_lead_details.php?session_index=$session[session_index]&lead_code=" + lead_general + "&ic=" + our_ic;
	window.location = our_addr;
}

</script>
</head>

<body class="main_page">
<div class="serilox_main"></div>
<div class="rinatours_main"></div>
<div class="user_picture"><img src="users_photos/$user[file_name]" width="72" height="72"></div>
<div class="lead_occupied_parent">
	<div class="lead_occupied" id="lead_occupied">
		<div class="lead_occupied_by" id="lead_occupied_by"></div>
		<input type="button" class="lead_occupied_left" value="Hide" onclick="hide_lead_occupied();">
		<input type="button" class="lead_occupied_right" value="Run Over" onclick="run_over_lead();">
	</div>
</div>
<div class="full_lead_details_parent">
	<div class="main_lead_details_customer_details">
		<iframe name="lead_details_iframe" style="width: 100%; height: 380px; border: 0;"></iframe>
		<input type="button" class="customer_search_button" id="hide_lead_details" style="position: absolute; bottom: 15px; left: 400px; margin: auto; text-align: center;" value="HIDE">
		<input type="button" class="customer_search_button" id="hide_lead_details1" style="position: absolute; bottom: 15px; left: 500px; margin: auto; text-align: center;" value="UPDATE" onclick="update_lead();">
	</div>
</div>
	
<div align="center">
<h1>Main</h1>
<p><table class="main_table_main">
	<tr>
ENDLINE;
	if ($category[leads_monitor] == "yes") 
	{ print "<td><input type=\"button\" name=\"bn1\" class=\"main_button_main\" value=\"Leads Monitor\" onclick=\"window.location='leads_monitor.php?session_index=$session[session_index]&ic=$ic';\"></td>"; }
	if ($category[customer_view] == "yes")
	{ print "<td><input type=\"button\" name=\"bn2\" class=\"main_button_main\" value=\"Customer Search\" onclick=\"window.location='customer_search.php?session_index=$session[session_index]&ic=$ic';\"></td>"; }
	if ($category[users_manager] == "yes")
	{ print "<td><input type=\"button\" name=\"bn3\" class=\"main_button_main\" value=\"Users Manager\" onclick=\"window.location='users_manager.php?session_index=$session[session_index]&ic=$ic';\"></td>"; }
	if ($category[tables_update] == "yes")
	{ print "<td><input type=\"button\" name=\"bn5\" class=\"main_button_main\" value=\"Tables\" onclick=\"window.location='tables.php?session_index=$session[session_index]&ic=$ic';\"></td>"; }
	print <<<ENDLINE
	</tr>
</table></p>

<div style="width: 1250px;"><div class="main_new_leads">New Leads</div></div><p>&nbsp;</p>
<div class="main_new_leads_details">
	<table class="main_new_leads_table">
	<tr style="text-align: center;"><td>Scheduled On</td><td>Customer Number</td><td>First Name</td><td>Last Name</td><td>Phone/Email</td><td>Media Source</td><td>Media Source 2</td><td>Scheduled For</td></td><td>Summary</td><td>User Handeling</td>
	<td>&nbsp;</td></tr>

ENDLINE;

$sth = $dbh->query("select * from assignments where as_type = \"new_lead\" and bizua_unix <= \"$our_time\" and (bapoal_date = \"0000-00-00\" or bapoal_date is NULL) 
order by bizua_unix desc limit 80"); while ($details = $sth->fetch())
{
	# $details[hitkash_date] = mysql_to_presentable($details[hitkash_date], "long");
	$presentable_date = $details[hitkash_date];
	$presentable_date = mysql_to_presentable($presentable_date, "long");
	$details[bizua_date] = mysql_to_presentable($details[bizua_date], "long");
	$sth1 = $dbh->query("select * from customer where code = '$details[customer_code]'"); $customer = $sth1->fetch();
	$sth1 = $dbh->query("select value from table_details where code = '$details[media_source]'"); $media = $sth1->fetch();
	$sth1 = $dbh->query("select value from table_details where code = '$details[media_source_2]'"); $media_2 = $sth1->fetch();
	$sth1 = $dbh->query("select fullname from users where code = '$details[im_handeling]'"); $im_handeling = $sth1->fetch();
	print <<<ENDLINE
	<tr>
	<td>$presentable_date $details[hitkash_time]</td>
	<td>$customer[code]</td>
	<td>$customer[customer_name]</td>
	<td>$customer[customer_last_name]</td>
	<td>$customer[phone_1]<br>$customer[email_1]</td>
	<td>$media[value]</td>
	<td>$media_2[value]</td>
	<td>$details[bizua_date] $details[bizua_time]</td>
	<td>$details[free_text]</td>
	<td>$im_handeling[fullname]</td>
	<td style="text-align: center;" nowrap>
	<input type="button" name="bn3" value="GO" class="customer_search_button" style="width: 50px;" onclick="go_to_lead('$details[code]');" shelly="$details[code]">
	<input type="button" name="bn3A" value="SHOW" class="customer_search_button" style="width: 50px;" onclick="show_lead_details('$details[code]');" shelly="$details[code]">
	<input type="button" name="bn3B" value="EDIT" class="customer_search_button" style="width: 50px;" onclick="edit_lead_details('$details[code]');" shelly="$details[code]">
	</td>
	</tr>
ENDLINE;
}

print <<<ENDLINE

</table></div></p>

<div style="width: 1250px;"><div class="main_new_leads">To Do List</div></div><p>&nbsp;</p>
<div class="main_new_leads_details">
	<table class="main_new_leads_table">
	<tr style="text-align: center;"><td>Scheduled On</td><td>Customer Number</td><td>First Name</td><td>Last Name</td><td>Phone/Email</td><td>Media Source</td><td>Media Source 2</td><td>Scheduled For</td>
	<td>Summary</td><td>User Handeling</td><!--<td>Result</td>--><td>&nbsp;</td></tr>

ENDLINE;

$sth = $dbh->query("select * from assignments where as_type = \"assignment\" and bizua_unix <= \"$our_time\" and (bapoal_date = \"0000-00-00\" or bapoal_date is NULL)
order by bizua_unix desc limit 80"); while ($details = $sth->fetch())
{
	# $details[hitkash_date] = mysql_to_presentable($details[hitkash_date]);
	$presentable_date = $details[hitkash_date];
	$presentable_date = mysql_to_presentable($presentable_date, "long");
	$details[bizua_date] = mysql_to_presentable($details[bizua_date], "long");
	$sth1 = $dbh->query("select * from customer where code = '$details[customer_code]'"); $customer = $sth1->fetch();
	$sth1 = $dbh->query("select value from table_details where code = '$details[media_source]'"); $media = $sth1->fetch();
	$sth1 = $dbh->query("select value from table_details where code = '$details[media_source_2]'"); $media_2 = $sth1->fetch();
	$sth1 = $dbh->query("select fullname from users where code = '$details[im_handeling]'"); $im_handeling = $sth1->fetch();
	print <<<ENDLINE
	<tr>
	<td>$presentable_date $details[hitkash_time]</td>
	<td>$customer[code]</td>
	<td>$customer[customer_name]</td>
	<td>$customer[customer_last_name]</td>
	<td>$customer[phone_1]<br>$customer[email_1]</td>
	<td>$media[value]</td>
	<td>$media_2[value]</td>
	<td>$details[bizua_date] $details[bizua_time]</td>
	<td>$details[free_text]</td>
	<td>$im_handeling[fullname]</td>
	<!--<td>$details[as_result]</td>-->
	<td nowrap><input type="button" name="bn3" value="GO" class="customer_search_button" style="width: 50px;" onclick="go_to_lead('$details[code]');" shelly="$details[code]">
	<input type="button" name="bn3A" value="SHOW" class="customer_search_button" style="width: 50px;" onclick="show_lead_details('$details[code]');" shelly="$details[code]">
	<input type="button" name="bn3A" value="EDIT" class="customer_search_button" style="width: 50px;" onclick="edit_lead_details('$details[code]');" shelly="$details[code]"></td>
	</tr>
ENDLINE;
}

print <<<ENDLINE
</table></div></p>
<iframe src="refresh_main.php?session_index=$session[session_index]&ic=$ic" style="width: 0; height: 0; border: 0;"></iframe></body></html>
ENDLINE;
$dbh = NULL;
