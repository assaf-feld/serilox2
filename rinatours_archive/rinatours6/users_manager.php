<?php

include "func.php";

$dbh = new PDO("mysql:dbname=rinatours","root","");
$session = secure($dbh);
$sth = $dbh->query("select * from users where code = '$session[usercode]'"); $user = $sth->fetch();

if ($session[status] == "add_category") { add_category(); }
else if($session[status] == "update_category") { update_category(); }
else if($session[status] == "add_user") { add_user(); }
else if($session[status] == "update_user") { update_user(); }

print <<<ENDLINE

<html>
<head>
<title>Users Manager</title>
<meta charset="utf-8">
<link rel="stylesheet" href="styles.css">
<script src="jquery-1.11.0.min.js"></script>
<script>
$(document).ready(function()
{
	$("#add_category_div_id").hide();
	$("#add_users_div_id").hide();
	$("#add_category_button").click(function()
	{
		$("#add_category_div_id").fadeIn(300);
	});
	$("#add_users_button").click(function()
	{
		$("#add_users_div_id").fadeIn(300);
	});
	$("#hide_add_category").click(function()
	{
		$("#add_category_div_id").fadeOut(300);
	});
	$("#hide_add_users").click(function()
	{
		$("#add_users_div_id").fadeOut(300);
	});
});
</script>
</head>

<body class="users_manager" class="users_manager">
<div class="serilox_main"></div>
<div class="rinatours_main"></div>
<div class="user_picture"><img src="users_photos/$user[file_name]" width="72" height="72"></div>
<div align="center">
<h1>Users Manager</h1>

<div class="add_category_div_parent"><div class="add_category_div" class="add_category_div" id="add_category_div_id">
	<form method='post' action='users_manager.php'>
	<input type='hidden' name='session_index' value='$session[session_index]'>
	<input type='hidden' name='status' value='add_category'>
	
	<table class="users_manager_table">
	<tr><td>Name</td><td>Main</td><td>Leads Monitor</td><td>Customer View</td><td>Users Manager</td><td>Performace Reports</td><td>Tables Management</td><td>Sales Rep</td></tr>
	<tr><td><input type='text' class='users_manager_text' name='category_name'></td>
	<td><input type='checkbox' name='mainn' value='yes'></td>
	<td><input type='checkbox' name='leads_monitor' value='yes'></td>
	<td><input type='checkbox' name='customer_view' value='yes'></td>
	<td><input type='checkbox' name='users_manager' value='yes'></td>
	<td><input type='checkbox' name='performance_reports' value='yes'></td>
	<td><input type='checkbox' name='tables_update' value='yes'></td>
	<td><input type='checkbox' name='sales_rep' value='yes'></td>
	</tr></table>
	<p><input type="submit" class="customer_search_submit" value="Save"> 
	<input type="button" class="customer_search_submit" id="hide_add_category" value="Hide"></p>
	</form>
</div></div>
<div class="add_category_div_parent_users"><div class="add_category_div" class="add_category_div" id="add_users_div_id">
	<form method='post' action='users_manager.php' enctype='multipart/form-data'>
	<input type='hidden' name='session_index' value='$session[session_index]'>
	<input type='hidden' name='status' value='add_user'>
	
	<table class="users_manager_table">
	<tr><td>Full Name</td><td>Username</td><td>Password</td><td>Category</td></tr>
	<tr><td><input type="text" class="users_manager_text" name="fullname"></td>
	<td><input type="text" name="username" class="users_manager_text"></td>
	<td><input type="text" name="password" class="users_manager_text"></td>
	<td><select name="category_code"><option value=""></option>

ENDLINE;

	$sth = $dbh->query("select * from user_category"); while ($details = $sth->fetch())
	{
		print "<option value=\"$details[code]\">$details[category_name]</option>";
	}
	print <<<ENDLINE
	</select></td></tr>
	<tr><td>Phone Number</td><td>Email</td><td>Upload Photo</td><td>&nbsp;</td></tr>
	<tr><td><input type="text" class="users_manager_text" name="phone_1"></td>
	<td><input type="text" class="users_manager_text" name="email"></td>
	<td><input type="file" name="file_name" class="users_manager_text"></td>
	<td>&nbsp;</td></tr></table>
	<p><input type="submit" class="customer_search_submit" value="Save">
	<input type="button" name="bn9" class="customer_search_submit" value="Hide" id="hide_add_users"></p>
	</form>
</div></div>

<div style="width: 1140px;"><div class="main_new_leads">Categories</div></div><p>&nbsp;</p>
<div class="users_manager_block">
<table class="users_manager_table">
<tr><td>Name</td><td>Main</td><td>Leads Monitor</td><td>Customer View</td><td>Users Manager</td><td>Performace Reports</td><td>Tables Management</td><td>Sales Rep</td><td colspan="2">&nbsp;</td></tr>

ENDLINE;

$sth = $dbh->query("select * from user_category"); while ($details = $sth->fetch())
{
	$details[category_name] = preg_replace("/\"/", "&quot;", $details[category_name]);
	print "<tr>
	<form method='post' action='users_manager.php'>
	<input type='hidden' name='session_index' value='$session[session_index]'>
	<input type='hidden' name='status' value='update_category'>
	<input type='hidden' name='code' value='$details[code]'>
	<td><input type='text' class='users_manager_text' name='category_name' value='$details[category_name]'></td>
	<td><input type='checkbox' name='mainn' value='yes'"; if ($details[mainn] == "yes") { print " checked"; } print "></td>
	<td><input type='checkbox' name='leads_monitor' value='yes'"; if ($details[leads_monitor] == "yes") { print " checked"; } print "></td>
	<td><input type='checkbox' name='customer_view' value='yes'"; if ($details[customer_view] == "yes") { print " checked"; } print "></td>
	<td><input type='checkbox' name='users_manager' value='yes'"; if ($details[users_manager] == "yes") { print " checked"; } print "></td>
	<td><input type='checkbox' name='performance_reports' value='yes'"; if ($details[performance_reports] == "yes") { print " checked"; } print "></td>
	<td><input type='checkbox' name='tables_update' value='yes'"; if ($details[tables_update] == "yes") { print " checked"; } print "></td>
	<td><input type='checkbox' name='sales_rep' value='yes'"; if ($details[sales_rep] == "yes") { print " checked"; } print "></td>
	<td><input type='submit' name='bn1' value='Update' class='customer_search_button'></td>
	<td><input type='submit' name='delete' value='Delete' class='customer_search_button' onclick=\"return confirm('Confirm Deletion');\"></td>
	</form></tr>";
}

print <<<ENDLINE
</table>
<p><input type="button" name="bn2" class="customer_search_submit" id="add_category_button" value="Add"></p>
</div>
<div style="width: 1140px;"><div class="main_new_leads">Users</div></div><p>&nbsp;</p>
<div class="users_manager_block">
<table class="users_manager_table">
<tr><td>Full Name</td><td>Username</td><td>Password</td><td>Category</td><td>Phone Number</td><td>Email</td><td>Upload Photo</td><td colspan="2">&nbsp;</td></tr>

ENDLINE;

$sth = $dbh->query("select * from users"); while ($details = $sth->fetch())
{
	print <<<ENDLINE

	<tr>
	<form method="post" action="users_manager.php" enctype="multipart/form-data">
	<input type="hidden" name="session_index" value="$session[session_index]">
	<input type="hidden" name="status" value="update_user">
	<input type="hidden" name="code" value="$details[code]">
	
	<td><input type="text" class="users_manager_text" name="fullname" value="$details[fullname]"></td>
	<td><input type="text" name="username" class="users_manager_text" value="$details[username]"></td>
	<td><input type="text" name="password" class="users_manager_text" value="$details[password]"></td>
	<td><select name="category_code"><option value=""></option>

ENDLINE;

	$sth1 = $dbh->query("select * from user_category"); while ($item = $sth1->fetch())
	{
		print "<option value=\"$item[code]\""; if ($details[category_code] == $item[code]) { print " selected"; } print ">$item[category_name]</option>";
	}
	print <<<ENDLINE
	</select></td>
	<td><input type="text" class="users_manager_text" name="phone_1" value="$details[phone_1]"></td>
	<td><input type="text" class="users_manager_text" name="email" value="$details[email_1]"></td>
	<td>
ENDLINE;
	if ($details[file_name])
	{
		print "<img src=\"users_photos/$details[file_name]\" width=\"120\" height=\"120\"><br>
		<input type=\"file\" name=\"file_name\" class=\"users_manager_text\">
		";
	}
	else
	{
		print "<input type=\"file\" name=\"file_name\" class=\"users_manager_text\">";
	}
	print <<<ENDLINE

	</td>
	<td><input type="submit" class="customer_search_button" name="bn1" value="Save"></td>
	<td><input type="submit" class="customer_search_button" name="delete" value="Delete" onclick="return confirm('Confirm Deletion')"></td>
	</form></tr>
ENDLINE;
}

print <<<ENDLINE
</table></div>
<p><input type="button" class="customer_search_submit" value="Add" id="add_users_button"> 
<input type="button" name="bn10" value="Main" class="customer_search_submit" onclick="window.location='main_page.php?session_index=$session[session_index]&ic=$ic';"></div></body></html>
ENDLINE;
$dbh = NULL;

function add_user()
{
	global $dbh;
	$fullname = secure_string($_POST[fullname]);
	$username = secure_letters($_POST[username]);
	$password = secure_letters($_POST[password]);
	$phone_1 = secure_string($_POST[phone_1]);
	$category_code = secure_numbers($_POST[category_code]);
	$email = secure_extended($_POST[email]);
	file_this("does the file has a name: it is " . $_FILES["file_name"]["name"], "um1");
	if($_FILES["file_name"]["name"])
	{
		$sth = $dbh->query("select photo_counter from counters"); $photo_counter = $sth->fetch(); $dbh->exec("update counters set photo_counter = photo_counter + 1"); $photo_counter = $photo_counter[photo_counter];
		$file_name = $_FILES["file_name"]["name"];
		$fields = split("\.", $file_name); $our_size = sizeof($fields) - 1;
		$siomet = $fields[$our_size];
		$new_file_name = $photo_counter . "." . $siomet;
		file_this("new_file_name: $new_file_name, photo_counter: $photo_counter", "um1");
		move_uploaded_file($_FILES["file_name"]["tmp_name"], "users_photos/$new_file_name");
	}

	$dbh_line = <<<ENDLINE
	insert into users set fullname = "$fullname", username = "$username", password = "$password", phone_1 = "$phone_1", category_code = "$category_code", email_1 = "$email", file_name = "$new_file_name"
ENDLINE;
	file_this("add user query: $dbh_line", "um1");
	$dbh->exec($dbh_line);
}

function update_user()
{
	global $dbh;
	$code = secure_numbers($_POST[code]);
	$delete = $_POST[delete];
	if ($delete)
	{
		$dbh->exec("delete from users where code = \"$code\"");
		return;
	}

	$fullname = secure_string($_POST[fullname]);
	$username = secure_letters($_POST[username]);
	$password = secure_letters($_POST[password]);
	$phone_1 = secure_string($_POST[phone_1]);
	$category_code = secure_numbers($_POST[category_code]);
	$email = secure_extended($_POST[email]);
	if($_FILES["file_name"]["name"])
	{
		$sth = $dbh->query("select photo_counter from counters"); $photo_counter = $sth->fetch(); $dbh->exec("update counters set photo_counter = photo_counter + 1"); $photo_counter = $photo_counter[photo_counter];
		$file_name = $_FILES["file_name"]["name"];
		$fields = split("\.", $file_name); $our_size = sizeof($fields) - 1;
		$siomet = $fields[$our_size];
		$new_file_name = $photo_counter . "." . $siomet;
		file_this("new_file_name: $new_file_name, photo_counter: $photo_counter", "um1");
		move_uploaded_file($_FILES["file_name"]["tmp_name"], "users_photos/$new_file_name");
	}
	
	$dbh_line = <<<ENDLINE
	update users set fullname = "$fullname", username = "$username", password = "$password", phone_1 = "$phone_1", category_code = "$category_code", email_1 = "$email", file_name = "$new_file_name"
	where code = "$code"
ENDLINE;

	$dbh->exec($dbh_line);
}

function add_category()
{
	global $dbh;
	$category_name = secure_string($_POST[category_name]); $category_name = addslashes($category_name);
	$sales_rep = secure_string($_POST[sales_rep]);
	$mainn = secure_string($_POST[mainn]);
	$leads_monitor = secure_string($_POST[leads_monitor]);
	$customer_view = secure_string($_POST[customer_view]);
	$users_manager = secure_string($_POST[users_manager]);
	$performance_reports = secure_string($_POST[performance_reports]);
	$tables_update = secure_string($_POST[tables_update]);

	$dbh_line = <<<ENDLINE
	insert into user_category values(NULL, "$category_name", "$sales_rep", "$mainn", "$leads_monitor", "$customer_view", "$users_manager", "$performance_reports", "$tables_update")
ENDLINE;
	$dbh->exec($dbh_line);
}

function update_category()
{
	global $dbh;
	$code = secure_numbers($_POST[code]);
	$delete = $_POST[delete];
	if ($delete)
	{
		$dbh->exec("delete from user_category where code = \"$code\"");
		return;
	}
	
	$category_name = secure_string($_POST[category_name]); $category_name = addslashes($category_name);
	$sales_rep = secure_string($_POST[sales_rep]);
	$mainn = secure_string($_POST[mainn]);
	$leads_monitor = secure_string($_POST[leads_monitor]);
	$customer_view = secure_string($_POST[customer_view]);
	$users_manager = secure_string($_POST[users_manager]);
	$performance_reports = secure_string($_POST[performance_reports]);
	$tables_update = secure_string($_POST[tables_update]);

	$dbh_line = <<<ENDLINE
	update user_category set category_name = "$category_name", sales_rep = "$sales_rep", mainn = "$mainn", leads_monitor = "$leads_monitor", customer_view = "$customer_view", users_manager = "$users_manager",
	performance_reports = "$performance_reports", tables_update = "$tables_update" where code = "$code"
ENDLINE;
	file_this("update category: $dbh_line", "um1");
	$dbh->exec($dbh_line);
}

?>
