<?php

include "our_func.php";

if ($_POST['session_index'])
{
	$session_index = $_POST['session_index']; $session_index = secure_letters($session_index);
	$status = $_POST['status']; $status = secure_letters($status);
}
else if ($_GET['session_index'])
{
	$session_index = $_GET['session_index']; $session_index = secure_letters($session_index);
	$status = $_GET['status']; $status = secure_letters($status);
}

$dbh = new PDO("mysql:dbname=serilox","root","");
$session = secure($session_index, $dbh);
$ic = rand();

if ($status == "add") { add_details(); }
else if($status == "update") { update_details(); }

print <<<ENDLINE

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="our_styles.css">
<script>
function show_iframe(table_code)
{
	var our_ic;
	our_ic = Math.random();
	document.tables_details_iframe.location = "tables_details.php?session_index=$session_index&table_code=" + table_code + "&ic=" + our_ic;
	// alert("something should happen now...");
}
	
</script>
</head>

<body>
<h2 style="width: 380px; margin: auto; padding: 13px;">
Managing tables &nbsp;&nbsp; 
<input type="button" name="bn3" value="Main Page" onclick="window.location='main_page.php?session_index=$session_index&ic=$ic';" class="horizontal_button" style="float: none;"></h2>
<div class="tables_main">
	<div class="tables_names">
		<input type="button" class="vertical_button" style="width: 180px;" name="bn1" value="Assignments" onclick="show_iframe('1');">
		<input type="button" class="vertical_button" style="width: 180px;" name="bn1" value="Assignments results" onclick="show_iframe('3');">
		<input type="button" class="vertical_button" style="width: 180px;" name="bn1" value="Meetings" onclick="show_iframe('2');">
		<input type="button" class="vertical_button" style="width: 180px;" name="bn1" value="Interested in..." onclick="show_iframe('4');">
		<input type="button" class="vertical_button" style="width: 180px;" name="bn1" value="Interested in... Sub Section" onclick="show_iframe('5');">
		<input type="button" class="vertical_button" style="width: 180px;" name="bn1" value="Interested in... Sub Section" onclick="show_iframe('6');">
		<input type="button" class="vertical_button" style="width: 180px;" name="bn1" value="Item Status" onclick="show_iframe('7');">
		<input type="button" class="vertical_button" style="width: 180px;" name="bn1" value="Mikum" onclick="show_iframe('8');">
		<input type="button" class="vertical_button" style="width: 180px;" name="bn1" value="Set By" onclick="show_iframe('9');">
	</div>
	<div class="tables_details">
		<iframe class="tables_details_iframe" src="tables_details.php?session_index=$session_index&ic=$ic" name="tables_details_iframe"></iframe>
	</div>
</div>
</body></html>

ENDLINE;

$dbh = NULL;
