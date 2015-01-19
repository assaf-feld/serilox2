<?php

include "func.php";
$dbh = new PDO("mysql:dbname=rinatours1","root","");
$session = secure($dbh);
$sth = $dbh->query("select * from assignments where code = '$session[lead_code]'"); $our_lead = $sth->fetch();
$sth = $dbh->query("select * from users where code = '$session[usercode]'"); $user = $sth->fetch();
$our_time = split(":", $our_lead[bizua_time]);
$ic = rand();

file_this("lead_code: $lead_code", "ald1");


print <<<ENDLINE

<html>
<head>
<title>Lead Details</title>
<meta charset="windows-1255">
<link rel="stylesheet" href="styles.css">
<script src="jquery-1.11.0.min.js"></script>
<script>
$(document).ready(function()
{
	$("#confirm_update_div").hide();
	$("#error_message_div").hide();
});
</script>
</head>

<body class="main_page">
<div class="serilox_main"></div>
<div class="rinatours_main"></div>
<div class="user_picture"><img src="users_photos/$user[file_name]" width="72" height="72"></div>
<div class="confirm_update_div_parent"><div class="confirm_update_div" id="confirm_update_div">Details Saved</div></div>
<div class="confirm_update_div_parent"><div class="error_message_div" id="error_message_div"></div></div>
<div align="center">
<h1>Lead Details</h1>
<form method="post" action="update_lead.php" target="update_lead_iframe">
<input type="hidden" name="session_index" value="$session[session_index]">
<input type="hidden" name="status" value="update_details">
<input type="hidden" name="lead_code" value="$our_lead[code]">
<input type="hidden" name="customer_code" value="$session[customer_code]">
<input type="hidden" name="customer_code" value="$our_lead[customer_code]">
<table class="new_lead_table">
<tr><td>Scheduled For</td><td>Scheduled For (Time)</td><td>Activity</td><td>Interested In</td><td>Media Source</td></tr>
<tr><td><input type="date" name="bizua_date" value="$our_lead[bizua_date]"></td>
<td><select name="bizua_hours"><option value=""></option>
ENDLINE;

print "<option value=\"05\""; if ($our_time[0] == 5) { print " selected"; } print ">05</option>";
print "<option value=\"06\""; if ($our_time[0] == 6) { print " selected"; } print ">06</option>";
print "<option value=\"07\""; if ($our_time[0] == 7) { print " selected"; } print ">07</option>";
print "<option value=\"08\""; if ($our_time[0] == 8) { print " selected"; } print ">08</option>";
print "<option value=\"09\""; if ($our_time[0] == 9) { print " selected"; } print ">09</option>";
print "<option value=\"10\""; if ($our_time[0] == 10) { print " selected"; } print ">10</option>";
print "<option value=\"11\""; if ($our_time[0] == 11) { print " selected"; } print ">11</option>";
print "<option value=\"12\""; if ($our_time[0] == 12) { print " selected"; } print ">12</option>";
print "<option value=\"13\""; if ($our_time[0] == 13) { print " selected"; } print ">13</option>";
print "<option value=\"14\""; if ($our_time[0] == 14) { print " selected"; } print ">14</option>";
print "<option value=\"15\""; if ($our_time[0] == 15) { print " selected"; } print ">15</option>";
print "<option value=\"16\""; if ($our_time[0] == 16) { print " selected"; } print ">16</option>";
print "<option value=\"17\""; if ($our_time[0] == 17) { print " selected"; } print ">17</option>";
print "<option value=\"18\""; if ($our_time[0] == 18) { print " selected"; } print ">18</option>";
print "<option value=\"19\""; if ($our_time[0] == 19) { print " selected"; } print ">19</option>";
print "<option value=\"20\""; if ($our_time[0] == 20) { print " selected"; } print ">20</option>";
print "<option value=\"21\""; if ($our_time[0] == 21) { print " selected"; } print ">21</option>";
print "<option value=\"22\""; if ($our_time[0] == 22) { print " selected"; } print ">22</option>";
print "<option value=\"23\""; if ($our_time[0] == 23) { print " selected"; } print ">23</option>";
print "</select> <select name=\"bizua_minute\"><option value=\"\"></option>";
print "<option value=\"05\""; if ($our_time[1] == 5) { print " selected"; } print ">05</option>";
print "<option value=\"10\""; if ($our_time[1] == 10) { print " selected"; } print ">10</option>";
print "<option value=\"15\""; if ($our_time[1] == 15) { print " selected"; } print ">15</option>";
print "<option value=\"20\""; if ($our_time[1] == 20) { print " selected"; } print ">20</option>";
print "<option value=\"25\""; if ($our_time[1] == 25) { print " selected"; } print ">25</option>";
print "<option value=\"30\""; if ($our_time[1] == 30) { print " selected"; } print ">30</option>";
print "<option value=\"35\""; if ($our_time[1] == 35) { print " selected"; } print ">35</option>";
print "<option value=\"40\""; if ($our_time[1] == 40) { print " selected"; } print ">40</option>";
print "<option value=\"45\""; if ($our_time[1] == 45) { print " selected"; } print ">45</option>";
print "<option value=\"50\""; if ($our_time[1] == 50) { print " selected"; } print ">50</option>";
print "<option value=\"55\""; if ($our_time[1] == 55) { print " selected"; } print ">55</option>";

print <<<ENDLINE

</select></td>
<td><select name="activity"><option value=""></option>

ENDLINE;

$sth = $dbh->query("select * from table_details where table_code = \"3\""); while($details = $sth->fetch())
{
	print "<option value=\"$details[code]\""; if ($our_lead[activity_type] == $details[code]) { print " selected"; } print ">$details[value]</option>";
}

print <<<ENDLINE
</select></td>
<td><select name="interested_in"><option value=""></option>

ENDLINE;

$sth = $dbh->query("select * from table_details where table_code = \"4\""); while($details = $sth->fetch())
{
	print "<option value=\"$details[code]\""; if ($our_lead[interested_in] == $details[code]) { print " selected"; } print ">$details[value]</option>";
}

print <<<ENDLINE

</select></td>


<td><select name="media_source"><option value=""></option>

ENDLINE;

$sth = $dbh->query("select * from table_details where table_code = \"1\""); while($details = $sth->fetch())
{
	print "<option value=\"$details[code]\""; if ($our_lead[media_source] == $details[code]) { print " selected"; } print ">$details[value]</option>";
}

print <<<ENDLINE

</select></td>

</tr>

<tr><td>Departure Date</td><td>Return Date</td><td>Dates Flexible?</td><td>People in Party</td><td>&nbsp;</td></tr>
<tr><td><input type="date" name="departure_date" value="$our_lead[departure_date]"></td>
<td><input type="date" name="return_date" value="$our_lead[return_date]"></td>
<td>

ENDLINE;
	print "<table><td>Yes</td><td><input type=\"radio\" name=\"dates_flexible\" value=\"yes\""; if ($our_lead[dates_flexible] == "yes") { print " checked"; } print "></td></tr><tr>
	<td>No</td><td><input type=\"radio\" name=\"dates_flexible\" value=\"no\""; if ($our_lead[dates_flexible] == "no") { print " checked"; } print "></td></tr></table>";

print <<<ENDLINE

</td>
<td><input type="text" name="people_in_party" value="$our_lead[people_in_party]"></td><td>&nbsp;</td></tr>

<tr><td colspan="5">Summary</td></tr>
<tr><td colspan="5"><textarea name="free_text">$our_lead[free_text]</textarea></td></tr></table>
<p><input type="submit" name="bn3" class="customer_details_submit" value="Save"> 
<input type="button" id="hide_button1" class="customer_details_submit" value="Main" onclick="window.location='main_page.php?session_index=$session[session_index]&ic=$ic';"></p>
</form></div>
<iframe src="update_lead.php?session_index=$session[session_index]" style="border: 0; margin: 0; padding: 0;" name="update_lead_iframe"></iframe>
</body>
</html>

ENDLINE;
$dbh = NULL;
