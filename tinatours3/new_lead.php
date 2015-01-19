<?php

include "func.php";
$dbh = new PDO("mysql:dbname=rinatours","root","");
$session = secure($dbh);

print <<<ENDLINE

<html>
<head>
<title>New Lead</title>
<meta charset="windows-1255">
<link rel="stylesheet" href="styles.css">
<script src="jquery-1.11.0.min.js"></script>
<script>
$(document).ready(function()
{
	$("#hide_button").click(function()
	{
		$("#customer_details_new_lead", window.parent.document).fadeOut(300);
	});
});
</script>
</head>

<body class="new_lead"><div align="center">
<form method="post" action="update_lead.php" target="update_lead_iframe">
<input type="hidden" name="session_index" value="$session[session_index]">
<input type="hidden" name="status" value="add">
<input type="hidden" name="customer_code" value="$session[customer_code]">
<table class="new_lead_table">
<tr><td>Scheduled For</td><td>Scheduled For (Time)</td><td>Activity</td><td>Interested In</td><td>Media Source</td></tr>
<tr><td><input type="date" name="bizua_date"></td>
<td><select name="bizua_hours"><option value=""></option>
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
<td><select name="activity"><option value=""></option>

ENDLINE;

$sth = $dbh->query("select * from table_details where table_code = \"3\""); while($details = $sth->fetch())
{
	print "<option value=\"$details[code]\">$details[value]</option>";
}

print <<<ENDLINE
</select></td>
<td><select name="interested_in"><option value=""></option>

ENDLINE;

$sth = $dbh->query("select * from table_details where table_code = \"4\""); while($details = $sth->fetch())
{
	print "<option value=\"$details[code]\">$details[value]</option>";
}

print <<<ENDLINE

</select></td>


<td><select name="media_source"><option value=""></option>

ENDLINE;

$sth = $dbh->query("select * from table_details where table_code = \"1\""); while($details = $sth->fetch())
{
	print "<option value=\"$details[code]\">$details[value]</option>";
}

print <<<ENDLINE

</select></td>

</tr>

<tr><td>Departure Date</td><td>Return Date</td><td>Dates Flexible?</td><td>People in Party</td><td>&nbsp;</td></tr>
<tr><td><input type="date" name="departure_date"></td>
<td><input type="date" name="return_date"></td>
<td>
	<table><td>Yes</td><td><input type="radio" name="dates_flexible" value="yes"></td></tr><tr>
	<td>No</td><td><input type="radio" name="dates_flexible" value="no"></td></tr></table>
</td>
<td><input type="text" name="people_in_party"></td><td>&nbsp;</td></tr>





<tr><td colspan="5">Summary</td></tr>
<tr><td colspan="5"><textarea name="free_text"></textarea></td></tr></table>
<p><input type="submit" name="bn3" class="customer_details_submit" value="Save"> 
<input type="button" id="hide_button" class="customer_details_submit" value="Hide"></p>
</form></div>
<iframe src="update_lead.php?session_index=$session[session_index]" style="border: 0; margin: 0; padding: 0;" name="update_lead_iframe"></iframe>
</body>
</html>

ENDLINE;
$dbh = NULL;
