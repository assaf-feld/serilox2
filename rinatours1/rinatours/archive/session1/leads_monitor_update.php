<?php

include "func.php";
$dbh = new PDO("mysql:dbname=rinatours1","root","");
$session = secure($dbh);

if ($session[status] == "update")
{
	$date_type = secure_string($_POST[date_type]);
	$start_date = secure_date($_POST[start_date]);
	$end_date = secure_date($_POST[end_date]);
	$media_1 = secure_numbers($_POST[media_1]);
	$media_2 = secure_numbers($_POST[media_2]);
	$interested_in = secure_numbers($_POST[interested_in]);
	$sales_rep = secure_numbers($_POST[sales_rep]);

	if($_POST[update_default])
	{
		$dbh->exec("delete from leads_query where usercode = '$session[usercode]'");
		$dbh->exec("insert into leads_query values(NULL, '$date_type', '$start_date', '$end_date', '$media_1', '$media_2', '$interested_in', '$sales_rep', '$session[usercode]')");
	}
	else if($_POST[show_results])
	{
		$query = "select * from assignments where 1 = 1";
		if ($date_type == "scheduled_on")
		{
			if($start_date)
			{
				$query .= " and hitkash_date >= '$start_date' ";
			}
			if($end_date)
			{
				$query .= " and hitkash_date <= '$end_date' ";
			}
		}
		else if ($date_type == "scheduled_for")
		{
			if($start_date)
			{
				$query .= " and bizua_date >= '$start_date' ";
			}
			if($end_date)
			{
				$query .= " and bizua_date <= '$end_date' ";
			}
		}
		if($media_1)
		{
			$query .= " and media_source = '$media_1' ";
		}
		if ($media_2)
		{
			$query .= " and media_source_2 = '$media_2' ";
		}
		if ($intersted_in)
		{
			$query .= " and interested_in = '$interested_in' ";
		}
		if ($sales_rep)
		{
			$query .= " and sales_rep = '$sales_rep' ";
		}
	}
}

print <<<ENDLINE

<!DOCTYPE html>
<html>
<head>
<title>Leads Monitor Results</title>
<meta charset="windows-1255">
<link rel="stylesheet" href="styles.css">
<script src="jquery-1.11.0.min.js"></script>
<script src="scripts.js">
<script>
$(document).ready(function()
{
	$("#leads_monitory_confirm_div", window.parent.document).fadeIn(300).delay(3000).faedOut(300);
});
</script>
</head>

<body>

ENDLINE;

if ($_POST[show_results])
{
	print <<<ENDLINE

	<p><table class="leads_monitor_results">
	<tr><td>Customer</td><td>Media Main</td><td>Media Secondary</td><td>Scheduled On</td><td>Scheduled For</td><td>Interested In</td><td>&nbsp;</td></tr>
ENDLINE;

	$sth = $dbh->query($query); while($details = $sth->fetch())
	{
		$ic = rand();
		$sth = $dbh->query("select * from customer where code = '$details[customer_code]'"); $customer = $sth->fetch();
		$sth = $dbh->query("select value from table_details where code = '$details[media_source]'"); $media_source = $sth->fetch();
		$sth = $dbh->query("select value from table_details where code = '$details[media_source_2]'"); $media_source_2 = $sth->fetch();
		$sth = $dbh->query("select value from table_details where code = '$details[interested_in]'"); $interested_in = $sth->fetch();
		print <<<ENDLINE

		<tr><td>$customer[customer_name]</td>
		<td>$media_source[value]</td>
		<td>$media_source_2[value]</td>
		<td>$details[hitkash_date]</td>
		<td>$details[bizua_date]</td>
		<td>$interested_in[value]</td>
		<td><input type="button" class="leads_monitor_results_show" name="bn1" value="Show" onclick="window.location='lead_details.cgi?lead_code=$details[code]&session_index=$session[session_index]&ic=$ic';"></td>
		</tr>
ENDLINE;
	}

	print "</table></p>";
}

print "</body></html>";
$dbh = NULL;

?>
