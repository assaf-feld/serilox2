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
<title>Contact List</title>
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

function open_contact_list()
{

		document.new_contact_iframe.location = "new_contact.php?session_index=$session[session_index]&customer_code=" + document.forms[0].customer_code.value + "&ic=$ic";
		$("#customer_details_new_lead").fadeIn(300);
}
</script>
</head>

<body class="new_lead"><div align="center">
<form method="post" action="new_contact.php">
<input type="hidden" name="session_index" value="$session[session_index]">
<input type="hidden" name="status" value="add">
<input type="hidden" name="customer_code" value="$session[customer_code]">
<table class="new_lead_table">
<tr><td>שם</td><td>Role</td><td>Phone</td><td>eMail</td></tr>

ENDLINE;

$sth = $dbh->query("select * from contact where customer_code = '$session[customer_code]' ");
while ($details = $sth->fetch()){
	print " <tr><td>$details[contact_name]</td><td>$details[role]</td><td>$details[phone]</td><td>$details[email]</td></tr>";
}

print <<<ENDLINE
</table>
<p> 
<input type="button" id="hide_button" class="customer_details_submit" value="Hide">
<input type="submit" name="bn1" value="New Contact" class="customer_details_submit" id="new_lead_button">
</p>
</form>
</div>
</body>
</html>


ENDLINE;
$dbh = NULL;