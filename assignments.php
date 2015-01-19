<?php

include "our_func.php";
include "assaftime.php";

if ($_POST['session_index'])
{
	$session_index = $_POST['session_index']; $session_index = secure_string($session_index);
	$customer_code = $_POST['customer_code']; $customer_code = secure_numbers($customer_code);
	$status = $_POST['status']; $status = secure_string($status);
}
else if ($_GET['session_index'])
{
	$session_index = $_GET['session_index']; $session_index = secure_string($session_index);
	$customer_code = $_GET['customer_code']; $customer_code = secure_numbers($customer_code);
	$status = $_GET['status']; $status = secure_string($status);
}

$ic = rand(); $update_flag = 0;
$dbh = new PDO("mysql:dbname=serilox","root","");
$session = secure($session_index, $dbh);	
if ($status == "update") { update_details(); }

if($update_flag == 1)
{
	print <<<ENDLINE

	<!DOCTYPE html>
	<html>
	<body onload='window.parent.fadeout_contacts();'></body>
	</html>
ENDLINE;
	$dbh = NULL;
	exit;
}

print <<<ENDLINE

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="our_styles.css">
<script src="our_scripts.js"></script>
</head>

<body style="padding: 20px;">
<form method="post" action="assignments.php">
<input type="hidden" name="session_index" value="$session_index">
<input type="hidden" name="customer_code" value="$customer_code">
<input type="hidden" name="status" value="update">
<div class="assignments_main">
	<div class="assignments_line">
		<div class="assignments_inner">
			<table>
				<tr><td>Date and Time</td><td><input type="date" name="bizua_date" class="assignment_date"></td></tr>
				<tr><td>&nbsp;</td><td><select name="bizua_hour"><option value=""></option>
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
			</select>
		
			<select name="bizua_minute"><option value=""></option>
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
		<div class="assignments_inner">
			In Charge <select name="in_charge"><option value=""></option>
			<option value="everyone">Everyone</option>
			<option value="only_me">Me Only</option>
			</select>
		</div>
	</div>
	<div class="assignments_line">
		<div class="assignments_inner">
			Assignments Type<br>
			<select name="assignment_type" style="margin-top: 10px;"><option value=""></option>
ENDLINE;
	
			$sth = $dbh->query("select * from table_details where table_code = '1'"); while ($details = $sth->fetch())
			{
				print "<option value='$details[code]'>$details[value]</option>";
			}
	
			print <<<ENDLINE
			</select>
		</div>
		<div class="assignments_inner">
			<textarea name="free_text" class="assignments_textarea" onfocus="remove_comment(this);">Comments...</textarea>
		</div>
	</div>
</div>

<div class="assignements_buttons">
	<input type="submit" class="horizontal_button" value="Update"> <input type="button" class="horizontal_button" name="bn2" value="Back" onclick="window.parent.fadeout_contacts();">
</div>

</body>
</html>
ENDLINE;

$dbh = NULL;

function update_details()
{
	global $dbh, $update_flag, $customer_code, $session_index, $session;
	$our_time = time();
	$bizua_date = $_POST['bizua_date']; $bizua_date = secure_date($bizua_date);
	$bizua_hour = $_POST['bizua_hour']; $bizua_hour = secure_numbers($bizua_hour);
	$bizua_minute = $_POST['bizua_minute']; $bizua_minute = secure_numbers($bizua_minute);
	$bizua_time = $bizua_hour . ":" . $bizua_minute . ":00";
	$bizua_unix = get_unixtime_from_mysql($bizua_date, $bizua_time);
	$free_text = $_POST['free_text']; $free_text = secure_string($free_text); $free_text = preg_replace("/'/", "/\\'/", $free_text);
	$in_charge = $_POST['in_charge']; $in_charge = secure_string($in_charge);
	$assignment_type = $_POST['assignment_type']; $assignment_type = secure_numbers($assignment_type);
	$dbh->exec("insert into assignments set customer_code = '$customer_code', hitkash_date = current_date, hitkash_time = current_time, hitkash_unix = '$our_time', bizua_date = '$bizua_date', bizua_time = '$bizua_time',
	bizua_unix = '$bizua_unix', assignment_type = '$assignment_type', free_text = '$free_text', in_charge = '$in_charge', session_index = '$session_index', usercode = '$session[usercode]'");
	file_this("insert into assignments set customer_code = '$customer_code', hitkash_date = current_date, hitkash_time = current_time, hitkash_unix = '$our_time', bizua_date = '$bizua_date', bizua_time = '$bizua_time',
        bizua_unix = '$bizua_unix', assignment_type = '$assignment_type', free_text = '$free_text', in_charge = '$in_charge', session_index = '$session_index', usercode = '$session[usercode]'", "ass_1");
	$update_flag = 1;
}

?>
