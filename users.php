<?php

include "our_func.php";

if($_POST['session_index'])
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

if ($status == "add") { add_details(); }
else if ($status == "update") { update_details(); }
$ic = rand();

print <<<ENDLINE

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Serilox - Users Management</title>
<link rel="stylesheet" href="our_styles.css">
<script>
function confirm_delete()
{
	return confirm("Are you sure?");
}
</script>
</head>

<body onload="document.forms[0].fullname.focus();">
<h1 style="width: 280px; margin: auto; padding-top: 20px; padding-bottom: 20px;">Users Management</h1>
<form method="post" action="users.php">
<input type="hidden" name="session_index" value="$session_index">
<input type="hidden" name="status" value="add">
<p><table class="table_users_add">
<tr class="tr_users_add_title"><td>Full Name</td><td>Username</td><td>Password</td><td>Office Phone</td><td>Cellphone</td><td>Email</td><td>Users auth</td><td>Tables auth</td><td>Salesman</td><td>nazig</td></tr>
<tr class="tr_users_add_details">
<td><input type="text" name="fullname" class="td_users"></td>
<td><input type="text" name="username" class="td_users"></td>
<td><input type="password" name="password" class="td_users"></td>
<td><input type="text" name="phone_1" class="td_users"></td>
<td><input type="text" name="cellphone_1" class="td_users"></td>
<td><input type="text" name="email_1" class="td_users"></td>
<td><input type="checkbox" name="users_auth" value="yes"></td>
<td><input type="checkbox" name="tables_auth" value="yes"></td>
<td><input type="checkbox" name="is_salesman" value="yes"></td>
<td><input type="checkbox" name="is_nazig" value="yes"></td>
</tr></table></p>
<div class="add_users_buttons"><input type="submit" class="horizontal_button" value="Add User"> 
<input type="button" class="horizontal_button" name="bn2" value="Main Menu" onclick="window.location='main_page.php?session_index=$session_index&ic=$ic';"></div></form>

<table class="table_users_update">
<tr class="tr_users_add_title"><td>Full Name</td><td>Username</td><td>Password</td><td>Office Phone</td><td>Cellphone</td><td>Email</td><td>Users auth</td><td>Tables auth</td><td>Salesman</td><td>Nazig</td>
<td colspan="2"></tr>

ENDLINE;

$sth = $dbh->query("select * from users order by fullname"); while ($details = $sth->fetch())
{
	print <<<ENDLINE
	<tr class="tr_users_update_details">
	<form method="post" action="users.php">
	<input type="hidden" name="session_index" value="$session_index">
	<input type="hidden" name="status" value="update">
	<input type="hidden" name="code" value="$details[code]">

	<td><input type="text" name="fullname" class="td_users" value="$details[fullname]"></td>
	<td><input type="text" name="username" class="td_users" value="$details[username]"></td>
	<td><input type="password" name="password" class="td_users" value="$details[password]"></td>
	<td><input type="text" name="phone_1" class="td_users" value="$details[phone_1]"></td>
	<td><input type="text" name="cellphone_1" class="td_users" value="$details[cellphone_1]"></td>
	<td><input type="text" name="email_1" class="td_users" value="$details[email_1]"></td>
ENDLINE;
	print "<td><input type='checkbox' name='users_auth' value='yes'"; if ($details[users_auth] == "yes") { print " checked"; } print "></td>
	<td><input type='checkbox' name='tables_auth' value='yes'"; if ($details[users_auth] == "yes") { print " checked"; } print "></td>
	<td><input type='checkbox' name='is_salesman' value='yes'"; if ($details[users_auth] == "yes") { print " checked"; } print "></td>
	<td><input type='checkbox' name='is_nazig' value='yes'"; if ($details[users_auth] == "yes") { print " checked"; } print "></td>
	<td><input type='submit' name='bn5' value='Update' class='update_button'></td>
	<td><input type='submit' name='delete' value='Delete' class='update_button' onclick='return confirm_delete();'></td>
	</form></tr>";
}

print "</body></html>";
$dbh = NULL;

function add_details()
{
	global $dbh;
	$fullname = $_POST['fullname']; $fullname = secure_string($fullname);
	$username = $_POST['username']; $username = secure_letters($username);
	$password = $_POST['password']; $password = secure_letters($password);
	$phone_1 = $_POST['phone_1']; $phone_1 = secure_string($phone_1);
	$cellphone_1 = $_POST['cellphone_1']; $cellphone_1 = secure_string($cellphone_1);
	$email_1 = $_POST['email_1']; $email_1 = secure_string($email_1);
	$users_auth = $_POST['users_auth']; $users_auth = secure_letters($users_auth);
	$tables_auth = $_POST['tables_auth']; $tables_auth = secure_letters($tables_auth);
	$is_salesman = $_POST['is_salesman']; $is_salesman = secure_letters($is_salesman);
	$is_nazig = $_POST['is_nazig']; $is_nazig = secure_letters($is_nazig);
	
	$dbh->exec("insert into users set fullname = '$fullname', username = '$username', password = '$password', phone_1 = '$phone_1', cellphone_1 = '$cellphone_1', email_1 = '$email_1', users_auth = '$users_auth',
	tables_auth = '$tables_auth', is_salesman = '$is_salesman', is_nazig = '$is_nazig'");
	file_this("insert into users set fullname = '$fullname', username = '$username', password = '$password', phone_1 = '$phone_1', cellphone_1 = '$cellphone_1', email_1 = '$email_1', users_auth = '$users_auth',
        tables_auth = '$tables_auth', is_salesman = '$is_salesman', is_nazig = '$is_nazig'", "users1");
}

function update_details()
{
	global $dbh;
	$code = $_POST['code']; $code = secure_numbers($code);
	$delete = $_POST['delete']; $delete = secure_letters($delete);
	if ($delete) 
	{
		$dbh->exec("delete from users where code = '$code'");
		return;
	}

	$fullname = $_POST['fullname']; $fullname = secure_string($fullname);
	$username = $_POST['username']; $username = secure_letters($username);
	$password = $_POST['password']; $password = secure_letters($password);
	$phone_1 = $_POST['phone_1']; $phone_1 = secure_string($phone_1);
	$cellphone_1 = $_POST['cellphone_1']; $cellphone_1 = secure_string($cellphone_1);
	$email_1 = $_POST['email_1']; $email_1 = secure_string($email_1);
	$users_auth = $_POST['users_auth']; $users_auth = secure_letters($users_auth);
	$tables_auth = $_POST['tables_auth']; $tables_auth = secure_letters($tables_auth);
	$is_salesman = $_POST['is_salesman']; $is_salesman = secure_letters($is_salesman);
	$is_nazig = $_POST['is_nazig']; $is_nazig = secure_letters($is_nazig);
	
	$dbh->exec("update users set fullname = '$fullname', username = '$username', password = '$password', phone_1 = '$phone_1', cellphone_1 = '$cellphone_1', email_1 = '$email_1', users_auth = '$users_auth',
	tables_auth = '$tables_auth', is_salesman = '$is_salesman', is_nazig = '$is_nazig' where code = '$code'");
}


