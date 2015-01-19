<?php

include "func.php";

$dbh = new PDO("mysql:dbname=rinatours","root","");
$session = secure($dbh);
$ic = rand();
$sth = $dbh->query("select * from users where code = '$session[usercode]'"); $user = $sth->fetch();

print <<<ENDLINE

<html>
<head>
<title>Table Management</title>
<meta charset="utf-8">
<link rel="stylesheet" href="styles.css">
<script src="scripts.js"></script>
<script>
function open_table(table_code)
{
	var our_ic, our_addr;
	our_ic = Math.random();
	our_addr = "table_details.php?session_index=$session[session_index]&table_code=" + table_code + "&ic=" + our_ic;
	document.tables_iframe_name.location = our_addr;
	// alert("should see something...");
}
</script>
</head>

<body class="tables">
<div class="serilox_main"></div>
<div class="rinatours_main"></div>
<div class="user_picture"><img src="users_photos/$user[file_name]" width="72" height="72"></div>
<div align="center">
<h1>Tables Management</h1>
<p><table class="tables_main">
	<tr><td class="tables_left" valign="top">
		<table class="tables_left_inner">
			<tr><td><input type="button" class="tables_link" name="bn1" value="Media Source (main)" onclick="open_table('1');" class="button_medium"></td></tr>
			<tr><td><input type="button" class="tables_link" name="bn1" value="Media Source (secondary)" onclick="open_table('2');" class="button_medium"></td></tr>
			<tr><td><input type="button" class="tables_link" name="bn1" value="Activity Type" onclick="open_table('3');" class="button_medium"></td></tr>
			<tr><td><input type="button" class="tables_link" name="bn1" value="Interested In" onclick="open_table('4');" class="button_medium"></td></tr>
			<tr><td><input type="button" class="tables_link" name="bn1" value="Customer Title" onclick="open_table('5');" class="button_medium"></td></tr>
			<tr><td><input type="button" class="tables_link" name="bn1" value="Why Not Interested?" onclick="open_table('6');" class="button_medium"></td></tr>
		</table>
	</td><td class="tables_right">
		<iframe src="table_details.php?session_index=$session[session_index]" class="tables_iframe" name="tables_iframe_name" id="tables_iframd_id"></iframe>
	</td></tr>
	<tr><td colspan="2" style="text-align: center;"><input type="button" name="bn5" class="main_button_main" value="Main" onclick="window.location='main_page.php?session_index=$session[session_index]&ic=$ic';"></td></tr>
</table></p>
</div></body></html>

ENDLINE;

$dbh = NULL;
?>
