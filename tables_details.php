<?php

include "our_func.php";

if ($_POST['session_index'])
{
	$session_index = $_POST['session_index']; $session_index = secure_letters($session_index);
	$status= $_POST['status']; $status = secure_letters($status);
	$table_code = $_POST['table_code']; $table_code = secure_numbers($table_code);
	$ic = $_POST['ic']; $ic = secure_numbers($ic);
}

else if($_GET['session_index'])
{
	$session_index = $_GET['session_index']; $session_index = secure_letters($session_index);
	$status= $_GET['status']; $status = secure_letters($status);
	$table_code = $_GET['table_code']; $table_code = secure_numbers($table_code);
	$ic = $_GET['ic']; $ic = secure_numbers($ic);
}

$dbh = new PDO("mysql:dbname=serilox","root","");
$session = secure($session_index, $dbh);
if ($status == "add") { add_details(); }
else if ($status == "update") { update_details(); }

print <<<ENDLINE

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="our_styles.css">
</head>

<body style="padding: 20px;" onload="document.forms[0].value.focus();">
ENDLINE;
if ($table_code)
{
	print <<<ENDLINE
	<form method="post" action="tables_details.php">
	<input type="hidden" name="session_index" value="$session_index">
	<input type="hidden" name="table_code" value="$table_code">
	<input type="hidden" name="status" value="add">
	<div class="tables_details_add">
			<input type="text" class="tables_details_input" name="value">
			<input type="submit" class="tables_details_submit" value="Add Value">
	</div>
	</form>
ENDLINE;
	$sth = $dbh->query("select * from table_details where table_code = '$table_code'"); while ($details = $sth->fetch())
	{
		$details[value] = preg_replace("/\"/", "&quot;", $details[value]);
		print <<<ENDLINE
		<form method="post" action="tables_details.php">
		<input type="hidden" name="session_index" value="$session_index">
		<input type="hidden" name="table_code" value="$table_code">
		<input type="hidden" name="status" value="update">
		<input type="hidden" name="code" value="$details[code]">
		<div class="tables_details_update">
				<input type="text" class="tables_details_input" name="value" value="$details[value]">
				<input type="submit" class="tables_details_submit" name="bn2" value="Update Value">
				<input type="submit" class="tables_details_submit" name="delete" value="Delete Value" onclick="return confirm('Are you sure?');">
		</div>
		</form>
ENDLINE;
	}
}

print <<<ENDLINE
</body>
</head>

ENDLINE;
$dbh = NULL;

function add_details()
{
	global $table_code, $dbh;
	$value = $_POST['value']; $value = secure_string($value);
	$dbh->exec("insert into table_details values(NULL, '$table_code', '$value')");
	file_this("just did: insert into table_details values(NULL, '$table_code', '$value')", "add_tables_1");
}

function update_details()
{
	global $table_code, $dbh;
	$value = $_POST['value']; $value = secure_string($value);
	$code = $_POST['code']; $code = secure_numbers($code);
	$delete = $_POST['delete']; $delete = secure_string($delete);
	
	if ($delete)
	{
		$dbh->exec("delete from table_details where code = '$code'");
		file_this("just did: delete from table_details where code = '$code'", "add_tables_1");
		return;
	}

	$dbh->exec("update table_details set value = '$value' where code = '$code'");
	file_this("just did: update table_details set value = '$value' where code = '$code'", "add_tables_1");
}
	

