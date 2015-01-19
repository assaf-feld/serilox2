<?php

include "func.php";
include "rand_string.php";

$dbh = new PDO("mysql:dbname=rinatours1","root","");
$session = secure($dbh);
$rand_string = create_session_index(15);

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
	$as_main_results = secure_string($_POST[as_main_results]);
	$why_not_reason = secure_numbers($_POST[why_not_reason]);
	$start_date_now = secure_string($_POST[start_date_now]);
	$end_date_now = secure_string($_POST[end_date_now]);
	if ($start_date_now) { $sth = $dbh->query("select current_date"); $start_date_now_date = $sth->fetch(); $start_date = $start_date_now_date[current_date]; }
	if ($end_date_now) { $sth = $dbh->query("select current_date"); $end_date_now_date = $sth->fetch(); $end_date = $end_date_now_date[current_date]; }

	if($_POST[update_default])
	{
		$dbh->exec("delete from leads_query where usercode = '$session[usercode]'");
		$dbh->exec("insert into leads_query values(NULL, '$date_type', '$start_date', '$end_date', '$media_1', '$media_2', '$interested_in', '$sales_rep', '$session[usercode]', '$as_result', '$start_date_now', 
		'$end_date_now', '$as_main_results','$why_not_reason')");
		
		file_this("insert into leads_query values(NULL, '$date_type', '$start_date', '$end_date', '$media_1', '$media_2', '$interested_in', '$sales_rep', '$session[usercode]', '$as_result', '$start_date_now',
                '$end_date_now', '$as_main_results','$why_not_reason')", "lmd1");
		print <<<ENDLINE
		
		<html>
		<head>
		<title>Leads Monitor Results</title>
		<meta charset="windows-1255">
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
		if ($as_main_results)
		{
			$query .= " and as_main_results = '$as_main_results' ";
		}
		if ($why_not_reason)
		{
			$query .= " and why_not_reason = '$why_not_reason' ";
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
	<meta charset="windows-1255">
	<link rel="stylesheet" href="styles.css">
	</head>
	
	<body>
	<p><table class="leads_monitor_results">
	<tr><td>Customer</td><td>Media Main</td><td>Media Secondary</td><td>Scheduled On</td><td>Scheduled For</td><td>User Handeling</td><td>Lead Type</td><td>Status</td><td>Summary</td><td>Not Interested Reason</td>
	<td><input type="button" name="bn3" value="Excel" class="customer_search_button" onclick="window.open('files/results_$rand_string.csv','_blank','');"></td></tr>
ENDLINE;

	$fh = fopen("files/results_$rand_string.csv", "w");
	$file_line = <<<ENDLINE
"Customer Code","Title","First Name","Last Name","Phone","Email","Birth Date","Passport Number","Passport Expiration Date","Summary","Media Source","Media Source 2","Departure Date","Return Date","Dates Flexible","People In Party","Page Name","Page Address"\n
ENDLINE;
	fwrite($fh, $file_line);
	$sth = $dbh->query($query); while($details = $sth->fetch())
	{
		$ic = rand();
		$sth1 = $dbh->query("select * from customer where code = '$details[customer_code]'"); $customer = $sth1->fetch();
		$sth1 = $dbh->query("select value from table_details where code = '$details[media_source]'"); $media_source = $sth1->fetch();
		$sth1 = $dbh->query("select value from table_details where code = '$details[media_source_2]'"); $media_source_2 = $sth1->fetch();
		$sth1 = $dbh->query("select value from table_details where code = '$details[interested_in]'"); $interested_in = $sth1->fetch();
		$sth1 = $dbh->query("select value from table_details where code = '$details[why_not_reason]'"); $why_not_reason = $sth1->fetch();
		$sth1 = $dbh->query("select fullname from users where code = '$details[usercode]'"); $username = $sth1->fetch();

		if ($details[as_result] == "price_quote") { $details[as_result] = "Price Quote"; }
		if ($details[as_result] == "not_interested") { $details[as_result] = "Not Interested"; }
		if ($details[as_result] == "order") { $details[as_result] = "Order"; }

		if ($details[as_main_results] == "no_answer") { $details[as_main_results] = "No Answer"; }
		else if ($details[as_main_results] == "busy") { $details[as_main_results] = "Busy"; }
		else if ($details[as_main_results] == "postpone") { $details[as_main_results] = "Postpone"; }
		else if ($details[as_main_results] == "update_lead") { $details[as_main_results] = "Update Lead"; }
		
		if ($details[as_type] == "new_lead") { $details[as_type] = "New Lead"; }
		else if ($details[as_type] == "assignment") { $details[as_type] = "Assignment"; }
		
		print <<<ENDLINE

		<tr><td>$customer[customer_name] $customer[customer_last_name]</td>
		<td>$media_source[value]</td>
		<td>$media_source_2[value]</td>
		<td>$details[hitkash_date] $details[hitkash_time]</td>
		<td>$details[bizua_date] $details[bizua_time]</td>
		<td>$username[fullname]</td>
		<td>$details[as_type]</td>
		<td>$details[as_main_results] / $details[as_result]</td>
		<td style="text-align: left;">$details[free_text]</td>
		<td>$why_not_reason[value]</td>
		<td><input type="button" class="customer_search_button" name="bn1" value="Show" onclick="window.parent.location='lead_details.php?lead_code=$details[code]&session_index=$session[session_index]&ic=$ic';"></td>
		</tr>
ENDLINE;
		
		$details[free_text] = preg_replace("/\n$/", "", $details[free_text]); preg_replace("/\r$/", "", $details[free_text]);
		$details[page_name] = preg_replace("/\n$/", "", $details[page_name]); preg_replace("/\r$/", "", $details[page_name]);
		$details[page_address] = preg_replace("/\n$/", "", $details[page_address]); preg_replace("/\r$/", "", $details[page_address]);
		$customer[email_1] = preg_replace("/\n$/", "", $customer[email_1]); preg_replace("/\r$/", "", $customer[email_1]);
		$media_source[value] = preg_replace("/\n$/", "", $media_source[value]); preg_replace("/\r$/", "", $media_source[value]);
		$media_source_2[value] = preg_replace("/\n$/", "", $media_source_2[value]); preg_replace("/\r$/", "", $media_source_2[value]);


		$file_line = <<<ENDLINE
"$customer[code]","$customer[title]","$customer[customer_name]","$customer[customer_last_name]","$customer[phone_1]","$customer[email_1]","$customer[birth_date]","$customer[passport_number]","$customer[passport_exp_date]","$details[free_text]","$media_source[value]","$media_source_2[value]","$details[departure_date]","$details[return_date]","$details[dates_flexible]","$details[people_in_party]","$details[page_name]","$details[page_address]"\n
ENDLINE;
		fwrite($fh, $file_line);
	}

	fclose($fh);
	print "</table></p>";
}

print "</body></html>";
$dbh = NULL;

?>
