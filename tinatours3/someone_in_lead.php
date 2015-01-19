<?php

include "func.php";
$dbh = new PDO("mysql:dbname=rinatours","root","");
$session = secure($dbh);

$sth = $dbh->query("select usercode from session where is_valid = 'yes' and in_lead = '$session[lead_code]'"); $check_lead = $sth->fetch();
if (!$check_lead[usercode])
{
	print "CLEAR";
}
else
{
	$sth = $dbh->query("select fullname from users where code = '$check_lead[usercode]'"); $fullname = $sth->fetch();
	print "$fullname[fullname]";
}

$dbh = NULL;
