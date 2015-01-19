<?php
//header('Content-Type: text/html; charset=utf-8');
include "func.php";
$dbh = new PDO("mysql:dbname=rinatours1;charset=utf8","root","");
$session = secure($dbh);
$sql = $dbh->prepare("SET NAMES UTF8");
$sql->execute();
//$dbh->query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");

print <<<ENDLINE

<html>
<head>
<title>New Contact</title>
<meta charset="UTF-8">
<link rel="stylesheet" href="styles.css">
<script src="jquery-1.11.0.min.js"></script>
<script>
$(document).ready(function()
{
	$("#hide_button").click(function()
	{
		$("#customer_details_new_lead", window.parent.document).fadeOut(300);
	});
});
</script>
</head>

<body class="new_lead"><div align="center">
<form method="post" action="update_contact.php" target="update_contact_iframe">
<input type="hidden" name="session_index" value="$session[session_index]">
<input type="hidden" name="status" value="add">
<input type="hidden" name="customer_code" value="$session[customer_code]">
<table class="new_lead_table">
<tr><td>Name</td><td>Role</td><td>Phone</td><td>eMail</td></tr>
<tr>
<td> <input type='text' name="name"></td>
<td> <input type='text' name="role"></td>
<td> <input type='text' name="phone"></td>
<td> <input type='text' name="email"></td>
</tr>
</table>
<p><input type="submit" name="bn3" class="customer_details_submit" value="Save"> 
<input type="button" id="hide_button" class="customer_details_submit" value="Hide"></p>
</form></div>
<iframe src="update_contact.php?session_index=$session[session_index]" style="border: 0; margin: 0; padding: 0;" name="update_contact_iframe"></iframe>
</body>
</html>

ENDLINE;
$dbh = NULL;