<?php

include "func.php";

$dbh = new PDO("mysql:dbname=rinatours","root","");
$session secure($dbh);

$lead_code = $_POST['lead_code']; $lead_code = secure_numbers($lead_code);
$as_results = $_POST['as_results']; $as_results = secure_string($as_results);
if ($_POST['no_answer']) { $as_main_results = "no_answer"; }
else if ($_POST['busy']) { $as_main_results = "busy"; }
else if ($_POST['postpone']) { $as_main_results = "postpone"; }
else if ($_POST['update_lead']) { $as_main_results = "update_lead"; }
$our_time = time();

$dbh->exec("update assignements set bapoal_date = current_date, bapoal_time = current_time, bapoal_unix = '$our_time', as_result = '$as_results', as_main_results = '$as_main_results' where code = '$lead_code'");

print <<<ENDLINE

<!DOCTYPE html>
<html>
<head>
<script src="scripts.js"></script>
<script src="jquery-1.11.0.min.js"></script>
<script>
$(document).ready(function()
{
	$("#update_confirm", window.parent.document).fadeIn(300).delay(3000).fadeOut(300);
}
</script>
</head>

<body></body></html>

ENDLINE;
$dbh = NULL;
