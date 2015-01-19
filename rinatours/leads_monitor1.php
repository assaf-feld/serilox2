<?php

include "func.php";

$dbh = new PDO("mysql:dbname=rinatours","root","");
$session = secure($dbh); $ic = rand();
file_this("select * from leads_monitor where usercode = '$session[usercode]'", "lm1");
$sth = $dbh->query("select * from leads_query where usercode = '$session[usercode]'"); $monitor = $sth->fetch();
$sth = $dbh->query("select * from users where code = '$session[usercode]'"); $user = $sth->fetch();
$dbh->exec("update session set in_lead = NULL where session_index = '$session[session_index]'");
if($monitor[start_date_now] == "yes") { $sth = $dbh->query("select current_date"); $start_date = $sth->fetch(); $monitor[start_date] = $start_date[current_date]; $start_now = " checked"; }
if($monitor[end_date_now] == "yes") { $sth = $dbh->query("select current_date"); $end_date = $sth->fetch(); $monitor[end_date] = $end_date[current_date]; $end_now = " checked"; }
file_this("start_date: $monitor[start_date], end_date: $monitor[end_date]", "lq1");

print <<<ENDLINE

<html>
<head>
<title>Leads Monitor</title>
<meta charset="windows-1255">
<link rel="stylesheet" href="styles.css">
<script src="scripts.js"></script>
<script src="jquery-1.11.0.min.js"></script>
<script>
$(document).ready(function()
{
	$("#leads_monitor_confirm_div").hide();
	$("#start_now_div").hide();
	$("#end_now_div").hide();
	$("#start_now_checkbox").mouseover(function()
	{
		$("#start_now_div").fadeIn(200);
	});
	$("#end_now_checkbox").mouseover(function()
	{
		$("#end_now_div").fadeIn(200);
	});
	$("#start_now_checkbox").mouseout(function()
	{
		$("#start_now_div").fadeOut(200);
	});
	$("#end_now_checkbox").mouseout(function()
	{
		$("#end_now_div").fadeOut(200);
	});
	$("#expand_button").click(function()
	{
		$("#div_leads_monitor_fields").animate({height: '250px'}, 400);
	});
	$("#button_go").click(function()
	{
		$("#div_leads_monitor_fields").animate({height: '20px'}, 400);
	});
});
</script>
</head>

<body class="leads_monitor">
<div class="serilox_main"></div>
<div class="rinatours_main"></div>
<div class="user_picture"><img src="users_photos/$user[file_name]" width="72" height="72"></div>
<div class="confirm_update_div_parent"><div class="confirm_update_div" id="leads_monitor_confirm_div">Details Saved</div></div>
<div align="center">
<h1>Leads Monitor</h1>

<form method="post" action="leads_monitor_update.php" target="leads_monitor_update_iframe">
<input type="hidden" name="session_index" value="$session[session_index]">
<input type="hidden" name="status" value="update">

	<div class="leads_monitor_fields" id="div_leads_monitor_fields"><table class="leads_monitor_table">
	<tr><td>Date Type</td><td>Start Date</td><td>End Date</td><td>Status</td></tr>
	<tr><td><select name="date_type"><option value=""></option>
	<option value="scheduled_on"
ENDLINE;
	if ($monitor[date_type] == "scheduled_on") { print " selected"; } print ">Scheduled On</option>	<option value='scheduled_for"; 
	if ($monitor[date_type] == "schedule_for") { print " selected"; } print "'>Scheduled For</option></select></td>";

	print <<<ENDLINE
	<td style="position: relative;"><input type="date" name="start_date" class="leads_monitor_date" style="width: 140px;" value="$monitor[start_date]"> 
	<input type="checkbox" name="start_date_now" value="yes" id="start_now_checkbox"$start_now>
	<div class="leads_monitor_checkbox_alt" id="start_now_div">Check for this date to be today</div></td>
	<td style="position: relative;"><input type="date" name="end_date" class="leads_monitor_date" style="width: 140px;" value="$monitor[end_date]"> 
	<input type="checkbox" name="end_date_now" value="yes" id="end_now_checkbox"$end_now>
	<div class="leads_monitor_checkbox_alt" id="end_now_div">Check for this date to be today</div></td>
	</td>
ENDLINE;
	print "<td><select name=\"as_result\"><option value=\"\"></option><option value=\"price_quote\""; 
	if ($monitor[as_result] == "price_quote") { print " selected"; } print ">Price Quote</option><option value=\"not_interested\""; 
	if ($monitor[as_result] == "not_interested") { print " selected"; } print ">Not Interested</option><option value=\"order\""; 
	if ($monitor[as_result] == "order") { print " selected"; } print ">Order</option>";
	print "</select></td>";

	print "</tr><tr><td>Media Source Main</td><td>Media Source Secondary</td><td>Interested In</td><td>Sales Rep</td></tr><tr>";
	print <<<ENDLINE
	<tr>
	<td><select name="media_1"><option value=""></option>
ENDLINE;
	$sth = $dbh->query("select * from table_details where table_code = '1'"); while ($details = $sth->fetch())
	{
		print "<option value='$details[code]'"; if ($details[code] == $monitor[media_1]) { print ' selected'; } print ">$details[value]</option>";
	}
	print "</select></td>";
	print "<td><select name=\"media_2\"><option value=\"\"></option>";

	$sth = $dbh->query("select * from table_details where table_code = '2'"); while ($details = $sth->fetch())
	{
		print "<option value='$details[code]'"; if ($details[code] == $monitor[media_2]) { print ' selected'; } print ">$details[value]</option>";
	}
	print "</select></td>";
	print <<<ENDLINE
	<td><select name="interested_in"><option value=""></option>
ENDLINE;

	$sth = $dbh->query("select * from table_details where table_code = '4'"); while ($details = $sth->fetch())
	{
		print "<option value='$details[code]'"; if ($details[code] == $monitor[interested_in]) { print ' selected'; } print ">$details[value]</option>";
	}
	print "</select></td>";
	print <<<ENDLINE
	<td><select name="sales_rep"><option value=""></option>
ENDLINE;
	$sth = $dbh->query("select * from users where is_salesman = 'yes'"); while ($details = $sth->fetch())
	{
		print "<option value='$details[code]'"; if ($details[code] == $monitor[sales_rep]) { print " selected"; } print ">$details[fullname]</option>";
	}
	print <<<ENDLINE
	</select></td><td>&nbsp;</td></tr>
	<tr><td>Sub Status</td><td>Not Interested Reason</td><td colspan="2">&nbsp;</td></tr>

	<tr><td><select name="as_main_results"><option value=""></option>
ENDLINE;
	print "<option value=\"no_answer\""; if ($monitor[as_main_results] == "no_answer") { print " selected"; } print ">No Answer</option>";
	print "<option value=\"busy\""; if ($monitor[as_main_results] == "busy") { print " selected"; } print ">Busy</option>";
	print "<option value=\"postpone\""; if ($monitor[as_main_results] == "postpone") { print " selected"; } print ">Postpone</option>";
	print "<option value=\"update_lead\""; if ($monitor[as_main_results] == "update_lead") { print " selected"; } print ">Update Lead</option>";
	print "</select></td><td><select name=\"why_not_reason\"><option value=\"\"></option>";

	$sth = $dbh->query("select * from table_details where table_code = '6'"); while ($details = $sth->fetch())
	{
		print "<option value='$details[code]'"; if ($details[code] == $monitor[why_not_reason]) { print ' selected'; } print ">$details[value]</option>";
	}
	print "</select></td><td colspan=\"2\"></select>";

	print <<<ENDLINE

	</table></p>
	<p><input type="submit" class="customer_search_submit" name="update_default" value="Set as my default"> 
	<input type="submit" class="customer_search_submit" name="show_results" value="Go" id="button_go">
	<input type="button" class="customer_search_submit" name="bn8" value="Main" onclick="window.location='main_page.php?session_index=$session[session_index]&ic=$ic';">
	<input type="button" class="customer_search_submit" name="bn8a" value="Expand" id="expand_button">
	</p>
	</form></div>

	<p><iframe src="leads_monitor_update.php?session_index=$session[session_index]&ic=$ic" class="leads_monitor_iframe" name="leads_monitor_update_iframe"></iframe></p>
</body></html>
ENDLINE;
$dbh = NULL;

?>
