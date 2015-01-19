<?php

include "func.php";
$dbh = new PDO("mysql:dbname=rinatours","root","");
$session = secure($dbh);

if ($session[lead_code])
{
	$session[reason] = addslashes($session[reason]);
	$our_time = time();
	$dbh->exec("update assignments set free_text = '$session[reason]', bapoal_date = current_date, bapoal_time = current_time, bapoal_unix = '$our_time', as_result = 'removed',
	as_main_results = NULL where code = '$session[lead_code]'");
	print "OKAY";
}

$dbh = NULL;
