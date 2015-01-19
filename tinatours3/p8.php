<?php

print <<<START
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1255">
</head>
<body>
START;

$dbh = new PDO("mysql:host=www.polygana.com;dbname=servicecallsiveco","shelly","octopussy");
$sth = $dbh->query("select * from users"); while ($details = $sth->fetch())
{
	print "<p>$details[fullname]</p>";
}

print "</body></html>";

$dbh = NULL;
