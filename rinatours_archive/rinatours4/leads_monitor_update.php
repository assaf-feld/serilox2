<?php

include "func.php";
$dbh = new PDO("mysql:dbname=rinatours","root","");
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
	$as_result = secure_string($_POST[as_result]);

	if($_POST[update_default])
	{
		$dbh->exec("delete from leads_query where usercode = '$session[usercode]'");
		$dbh->exec("insert into leads_query values(NULL, '$date_type', '$start_date', '$end_date', '$media_1', '$media_2', '$interested_in', '$sales_rep', '$session[usercode]', '$as_result')");
		print <<<ENDLINE
		
		<html>
		<head>
		<title>Leads Monitor Results</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="styles.css">
		<script src="jquery-1.11.0.min.js"></script>
		<script>
		$(document).ready(function()
		{
			$("#leads_monitor_confirm_div", window.parent.document).fadeIn(300).delay(800).fadeOut(300);
		});
		</script>
		</head>
		
		<body></body></html>
ENDLINE;
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
		if ($interested_in)
		{
			$query .= " and interested_in = '$interested_in' ";
		}
		if ($sales_rep)
		{
			$query .= " and usercode = '$sales_rep' ";
		}
		if($as_result)
		{
			$query .= " and as_result = '$as_result' ";
		}
	}
}

file_this("query: $query", "lm1");


if ($_POST[show_results])
{
	print <<<ENDLINE
	
	<!DOCTYPE html>
	<html>
	<head>
	<title>Leads Monitor Results</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="styles.css">
	</head>
	
	<body>
	<p><table class="leads_monitor_results">
	<tr><td>Customer</td><td>Media Main</td><td>Media Secondary</td><td>Scheduled On</td><td>Scheduled For</td><td>Interested In</td><td>Status</td><td>&nbsp;</td></tr>
ENDLINE;

	$sth = $dbh->query($query); while($details = $sth->fetch())
	{
		$ic = rand();
		$sth1 = $dbh->query("select * from customer where code = '$details[customer_code]'"); $customer = $sth1->fetch();
		$sth1 = $dbh->query("select value from table_details where code = '$details[media_source]'"); $media_source = $sth1->fetch();
		$sth1 = $dbh->query("select value from table_details where code = '$details[media_source_2]'"); $media_source_2 = $sth1->fetch();
		$sth1 = $dbh->query("select value from table_details where code = '$details[interested_in]'"); $interested_in = $sth1->fetch();
		if ($details[as_result] == "price_quote") { $details[as_result] = "Price Quote"; }
		else if ($details[as_main_results] == "not_interested") { $details[as_main_results] = "Not Interested"; }
		else if ($details[as_main_results] == "order") { $details[as_main_results] = "Order"; }
		else if ($details[as_main_results] == "postopne") { $details[as_main_results] = "Order"; }
		else if ($details[as_main_results] == "busy") { $details[as_main_results] = "Order"; }
		if ($details[as_type] == "new_lead") { $details[as_main_results] = "New Lead"; }
		
		print <<<ENDLINE

		<tr><td>$customer[customer_name] $customer[customer_last_name]</td>
		<td>$media_source[value]</td>
		<td>$media_source_2[value]</td>
		<td>$details[hitkash_date]</td>
		<td>$details[bizua_date]</td>
		<td>$interested_in[value]</td>
		<td>$details[as_main_results]</td>
		<td><input type="button" class="customer_search_button" name="bn1" value="Show" onclick="window.parent.location='lead_details.php?lead_code=$details[code]&session_index=$session[session_index]&ic=$ic';"></td>
		</tr>
ENDLINE;
	}

	print "</table></p>";
}

print "</body></html>";
$dbh = NULL;

?>
