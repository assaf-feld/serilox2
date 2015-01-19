<?php

include "func.php";

$dbh = new PDO("mysql:dbname=rinatours1","root","");
$session = secure($dbh);

$sth = $dbh->query("select refresh_main from administration"); $refresh_main = $sth->fetch();

if ($refresh_main[refresh_main] == "yes")
{
	print <<<ENDLINE

	<html>
	<head>
	<script>
	function refresh_top()
	{
		var our_ic, our_addr;
		our_ic = Math.random();
		our_addr = "main_page.php?session_index=$session[session_index]&ic=" + our_ic;
		// alert("going to refresh now");
		window.parent.location = our_addr;
	}
	</script>
	</head>

	<body onload="refresh_top();"></body></html>
ENDLINE;
}
else
{
	print <<<ENDLINE
	<html>
	<head>
	<meta http-equiv="refresh" content="30">
	</head>
	<body></body></html>
ENDLINE;
}

$dbh = NULL;
