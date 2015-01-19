<?php

include "func.php";
$dbh = new PDO("mysql:dbname=rinatours1","root","");
$session = secure($dbh);

if ($session[im_handeling])
{
	$dbh->exec("update assignments set im_handeling = '$session[im_handeling]' where code = '$session[lead_code]'");
	print "OKAY";
}

$dbh = NULL;
