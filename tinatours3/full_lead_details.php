<?php

include "func.php";
$dbh = new PDO("mysql:dbname=rinatours","root","");
$session = secure($dbh);

$sth = $dbh->query("select * from assignments where code = '$session[lead_code]'"); $our_lead = $sth->fetch();
if ($our_lead[as_type] == "new_lead") { $as_type = "New Lead"; } else if ($our_lead[as_type] == "assignment") { $as_type = "Assignment"; }
$sth = $dbh->query("select value from table_details where code = '$our_lead[media_source]'"); $media_source = $sth->fetch();
$sth = $dbh->query("select value from table_details where code = '$our_lead[media_source_2]'"); $media_source_2 = $sth->fetch();
$sth = $dbh->query("select value from table_details where code = '$our_lead[interested_in]'"); $interested_in = $sth->fetch();
$sth = $dbh->query("select value from table_details where code = '$our_lead[activity_type]'"); $activity_type = $sth->fetch();
if ($our_lead[as_result] == "price_quote") { $as_result = "Price Quote"; }
else if ($our_lead[as_result] == "not_interested") { $as_result = "Not Interested"; }
else if ($our_lead[as_result] == "Order") { $as_result = "Order"; }
if ($our_lead[as_main_results] == "no_answer") { $as_main_results = " - No Answer"; }
else if ($our_lead[as_main_results] == "busy") { $as_main_results = " - Busy"; }
else if ($our_lead[as_main_results] == "postpone") { $as_main_results = " - Postpone"; }
else if ($our_lead[as_main_results] == "update_lead") { $as_main_results = " - Update Lead"; }
if ($our_lead[dates_flexible] == "yes") { $dates_flexible = "Yes"; }
else if ($our_lead[dates_flexible] == "no") { $dates_flexible = "No"; }

print <<<ENDLINE

<html>
<head>
<title>Lead Details</title>
<meta charset="windows-1255">
<link rel="stylesheet" href="styles.css">
</head>

<body>
<table class="main_lead_details_table">
<tr><td>Scheduled On</td><td>Scheduled For</td><td colspan="2">Summary</td></tr>
<tr><td>$our_lead[hitkash_date] $our_lead[hitkash_time]</td>
<td>$our_lead[bizua_date] $our_lead[bizua_time]</td>
<td colspan="2">$our_lead[free_text]</td></tr>

<tr><td>Lead Type</td><td>Media Sourse</td><td>Media Source 2</td><td>Interested In</td></tr>
<tr><td>$as_type</td>
<td>$media_source[value]</td>
<td>$media_source_2[value]</td>
<td>$interested_in[value]</td>


<tr><td>Activity Type</td><td>Result</td><td>Departure Date</td><td>Return Date</td></tr>
<tr><td>$activity_type[value]</td>
<td>$as_result $as_main_results</td>
<td>$our_lead[departure_date]</td>
<td>$our_lead[return_date]</td></tr>


<tr><td>Dates Flexible</td><td>People In Party</td><td colspan="2">Page Address</td></tr>
<tr><td>$dates_flexible</td>
<td>$our_lead[people_in_party]</td>
<td colspan="2">$our_lead[page_address]</td></tr>
</table></body></html>
ENDLINE;

$dbh = NULL;
