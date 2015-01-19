<?php

include "func.php";
$dbh = new PDO("mysql:dbname=rinatours1","root","");
$session = secure($dbh);
file_this("session index is: $session[session_index]", "td1");

if ($session['status'] == "add") { add_details(); }
else if ($session['status'] == "update") { update_details(); }

print <<<ENDLINE

<!DOCTYPE html>
<html>
<head>
<title>Table details</title>
<meta charset="windows-1255">
<link rel="stylesheet" href="styles.css">
</head>

<body onload="document.forms[0].value.focus();">
ENDLINE;

if ($session[table_code]) { print <<<ENDLINE

<p><table class="table_details">
	<tr><form method="post" action="table_details.php">
	<input type="hidden" name="session_index" value="$session[session_index]">
	<input type="hidden" name="table_code" value="$session[table_code]">
	<input type="hidden" name="status" value="add">
	<td><input type="text" name="value" class="table_details_text"></td>
	<td><input type="checkbox" name="is_defaule" value="yes"></td>
	<td><input type="submit" name="bn1" value="Add" class="tables_details_button"></td></form></tr>

ENDLINE;

$sth = $dbh->query("select * from table_details where table_code = '$session[table_code]'"); while ($details = $sth->fetch())
{
	print <<<ENDLINE

	<tr><form method="post" action="table_details.php">
	<input type="hidden" name="session_index" value="$session[session_index]">
	<input type="hidden" name="table_code" value="$session[table_code]">
	<input type="hidden" name="status" value="update">
	<input type="hidden" name="code" value="$details[code]">
	
	<td><input type="text" name="value" class="table_details_text" value="$details[value]"></td>
ENDLINE;
	print "<td><input type=\"checkbox\" name=\"is_default\" value=\"yes\""; if ($details[is_default] == "yes") { print " checked"; } print "></td>";
	print <<<ENDLINE
	<td><input type="submit" name="bn2" value="Update" class="tables_details_button"></td>
	<td><input type="submit" name="delete" value="Delete" class="tables_details_button" onclick="return confirm('Confirm Deletion');"></td>
	</form></tr>
ENDLINE;
}

print "</table>"; } print "</body></html>";
$dbh = NULL;

function add_details()
{
	global $dbh, $session;
	$value = addslashes($session[value]);
	$dbh->exec("insert into table_details values(NULL, \"$session[table_code]\", \"$value\", NULL)");
}

function update_details()
{
	global $dbh, $session;
	$code = $_POST['code'];
	$delete = $_POST['delete'];
	$is_default = $_POST['is_default'];
	if ($delete)
	{
		file_this("delete from table_details where code = '$code'", "td1");
		$dbh->exec("delete from table_details where code = '$code'");
		return;
	}

	$value = addslashes($session[value]);
	$dbh->exec("update table_details set value = \"$value\" where code = '$code'");
	if ($is_default == "yes")
	{
		$dbh->exec("update table_details set is_default = \"no\" where table_code = \"$session[table_code]\"");
		$dbh->exec("update table_details set is_default = \"yes\" where code = \"$code\"");
	}
}
