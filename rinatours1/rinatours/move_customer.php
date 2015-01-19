<?php

include "func.php";
$dbh = new PDO("mysql:dbname=rinatours1","root","");
$session = secure($dbh);

file_this("lead_code: $session[lead_code], customer_code: $session[customer_code]", "mc1");

$sth = $dbh->query("select code from customer where code = '$session[customer_code]'"); $check = $sth->fetch();
if(!$check[code]) 
{ 
	print "Customer Not Found"; 
	file_this("customer not found", "mc1");
} 
else 
{ 
	$dbh->exec("update assignments set customer_code = '$session[customer_code]' where code = '$session[lead_code]'");
	file_this("update assignments set customer_code = '$session[customer_code]' where code = '$session[lead_code]'", "mc1");
	print "OKAY"; 
}

$dbh = NULL;
?>
