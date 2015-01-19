<?php

include "our_func.php";
include "assaftime.php";

if ($_POST['session_index'])
{
	$session_index = $_POST['session_index']; $session_index = secure_letters($session_index);
	$customer_code = $_POST['customer_code']; $customer_code = secure_numbers($customer_code);
	$status = $_POST['status'];
}

else if ($_POST['session_index'])
{
	$session_index = $_GET['session_index']; $session_index = secure_letters($session_index);
	$customer_code = $_GET['customer_code']; $customer_code = secure_numbers($customer_code);
	$status = $_GET['status'];
}

$dbh = new PDO("mysql:dbname=serilox","root","");
$session = secure($session_index, $dbh);
$sth = $dbh->query("select * from customer where code = '$customer_code'"); $customer = $sth->fetch();
if ($status eq "update") { update_details(); }
$salesman = get_salesmen();

print <<<ENDLINE

<!DOCTYPE html>
<head>
<title>Meeting Setup</title>
<meta charset="utf-8">
<link rel="stylesheet" href="our_styles.css>
<script src="our_scripts.js"></script>
</head>

<body>
<div class="meeting_notification_div" id="id_meeting_notification_div"></div>
<form method="post" action="meeting_update.php">
<input type="hidden" name="session_index" value="$session_index">
<input type="hidden" name="customer_code" value="$customer_code">
<input type="hidden" name="status" value="update">
<input type="hidden" name="code" id="meeting_code">
<div class="meeting_main">
	<div class="meeting_line">
		<div class="meeting_label">Customer</div>
		<div class="meeting_label">$customer[customer_name]</div>
		<div class="meeting_label">Contact</div>
		<div class="meeting_item">
			<select style="meeting_select" name="contact_person"><option value=""></option>
ENDLINE;

		$sth = $dbh->query("select * from contact where customer_code = '$customer_code'"); while ($details = $sth->fetch())
		{
			print "<option value='$details[code]'>$details[contact_name]</option>";
		}

		print <<<ENDLINE
		
		</select></div>
	</div>
	<div class="meeting_line">
		<div class="meeting_label">Meeting Origin</div>
		<div class="meeting_item">
			<select class="meeting_select" name="meeting_origin"><option value=""></option>
			<option value="nichnas">Nichnas</option>
			<option value="yazum">Yazum</option>
			</select>
		</div>
		<div class="meeting_label">Sales person</div>
		<div class="meeting_item"><select class="meeting_select" name="salesman"><option value=""></option>
ENDLINE;
		foreach ($salesmen as $item)
		{
			print "<option value="$item[code]">$item[fullname]</option>";
		}
		print <<<ENDLINE
		</select></div>
	</div>
	<div class="meeting_line">
		<div class="meeting_label">Section</div>
		<div class="meeting_item"><select class="meeting_select" name="section"><option value=""></option>
ENDLINE;
		$sth = $dbh->query("select * from table_details where table_code = '4'"); while ($details = $sth->fetch())
		{
			print "<option value="$details[code]">$details[value]</option>";
		}
		print <<ENDLINE
		</select></div>
		<div class="meeting_label">Sub Section A</div>
		<div class="meeting_item"><select class="meeting_select" name="sub_section_a"><option value=""></option>
ENDLINE;
		$sth = $dbh->query("select * from table_details where table_code = '5'"); while ($details = $sth->fetch())
		{
			print "<option value="$details[code]">$details[value]</option>";
		}
		print <<ENDLINE
		</select></div>
	</div>
	<div class="meeting_line">
		<div class="meeting_label">Sub Section B</div>
		<div class="meeting_item"><select class="meeting_select" name="sub_section_b"><option value=""></option>
ENDLINE;
		$sth = $dbh->query("select * from table_details where table_code = '5'"); while ($details = $sth->fetch())
		{
			print "<option value="$details[code]">$details[value]</option>";
		}
		print <<ENDLINE
		</select></div>
		<div class="meeting_label">Set By</div>
		<div class="meeting_item"><select class="meeting_select" name="set_by"><option value=""></option>
ENDLINE;
		$sth = $dbh->query("select * from table_details where table_code = '9'"); while ($details = $sth->fetch())
		{
			print "<option value="$details[code]">$details[value]</option>";
		}
		print <<ENDLINE
		</select></div>
	</div>
	<div class="meeting_line">
		<div class="meeting_label">Mikum</div>
		<div class="meeting_item"><select class="meeting_select" name="mikum"><option value=""></option>
ENDLINE;
		$sth = $dbh->query("select * from table_details where table_code = '8'"); while ($details = $sth->fetch())
		{
			print "<option value="$details[code]">$details[value]</option>";
		}
		print <<ENDLINE
		</select></div>
		<div class="meeting_label">Status Item</div>
		<div class="meeting_item"><select class="meeting_select" name="status_item"><option value=""></option>
ENDLINE;
		$sth = $dbh->query("select * from table_details where table_code = '7'"); while ($details = $sth->fetch())
		{
			print "<option value="$details[code]">$details[value]</option>";
		}
		print <<ENDLINE
		</select></div>
	</div>
	<div class="meeting_line">
		<div class="meeting_label">Meeting Date & Time</div>
		<div class="meeting_item>
			<table>
			<tr><td colspan="2"><input type="date" name="meeting_date"></td></tr>
			<tr><td><select name="meeting_hour" class="meeting_select"><option value=""></option>
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
			</select></td><td><select class="meeting_select" name="meeting_minute"><option value=""></option>
			<option value="00">00</option>
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
			</select></td></tr></table>
		</div>
	</div>
	<div class="meeting_line">
		<div class="meeting_item"><textarea class="meeting_textarea" name="perut">Details...</textarea></div>
		<div class="meeting_item"><textarea class="meeting_textarea" name="comments">Comments...</textarae></div>
	</div>
</div>
<div class="meeting_buttons">
	<input type="submit" class="horizontal_button" name="update_only" value="Update Details"> 
	<input type="button" class="horizontal_button" name="update_notify" value="Update & Notify"> 
	<input type="button" class="horizontal_button" name="bn3" value="Done">
</div></body></html>

ENDLINE;

$dbh = NULL;
