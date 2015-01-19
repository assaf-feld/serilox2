<?php

include "func.php";

$dbh = new PDO("mysql:dbname=rinatours","root","");
$session = secure($dbh); $ic = rand();
file_this("select * from leads_monitor where usercode = '$session[usercode]'", "lm1");
$sth = $dbh->query("select * from leads_query where usercode = '$session[usercode]'"); $monitor = $sth->fetch();

print <<<ENDLINE

<html>
<head>
<title>Leads Monitor</title>
<meta charset="utf-8">
<link rel="stylesheet" href="styles.css">
<script src="scripts.js"></script>
<script src="jquery-1.11.0.min.js"></script>
<script>
$(document).ready(function()
{
	$("#leads_monitor_confirm_div").hide();
});
</script>
</head>

<body class="leads_monitor">
<div class="serilox_main"></div>
<div class="confirm_update_div_parent"><div class="confirm_update_div" id="leads_monitor_confirm_div">Details Saved</div></div>
<div align="center">
<h1>Leads Monitor</h1>

<form method="post" action="leads_monitor_update.php" target="leads_monitor_update_iframe">
<input type="hidden" name="session_index" value="$session[session_index]">
<input type="hidden" name="status" value="update">

	<div class="leads_monitor_fields"><table class="leads_monitor_table">
	<tr><td>Date Type</td><td>Start Date</td><td>End Date</td><td>Status</td></tr>
	<tr><td><select name="date_type"><option value=""></option>
	<option value="scheduled_on"
ENDLINE;
	if ($monitor[date_type] == "scheduled_on") { print " selected"; } print ">Scheduled On</option>	<option value='scheduled_for"; 
	if ($monitor[date_type] == "schedule_for") { print " selected"; } print "'>Scheduled For</option></select></td>";

	print <<<ENDLINE
	<td><input type="date" name="start_date" class="leads_monitor_date" value="$monitor[start_date]"></td>
	<td><input type="date" name="end_date" class="leads_monitor_date" value="$monitor[end_date]"></td>
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
	</select></td><td>&nbsp;</td></tr></table></p>
	<p><input type="submit" class="customer_search_submit" name="update_default" value="Set as my default"> 
	<input type="submit" class="customer_search_submit" name="show_results" value="Go">
	<input type="button" class="customer_search_submit" name="bn8" value="Main" onclick="window.location='main_page.php?session_index=$session[session_index]&ic=$ic';">
	</p>
	</form></div>

	<p><iframe src="leads_monitor_update.php?session_index=$session[session_index]&ic=$ic" class="leads_monitor_iframe" name="leads_monitor_update_iframe"></iframe></p>
</body></html>
ENDLINE;
$dbh = NULL;

?>
