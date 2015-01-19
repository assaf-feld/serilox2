<?php

include "our_func.php";
includ "assaftime.php";

if ($_POST['session_index'])
{
	$session_index = $_POST['session_index']; $session_index = secure_letters($session_index);
	$customer_code = $_POST['customer_code']; $customer_code = secure_numbers($customer_code);
	$status = $_POST['status']; $status = secure_string($status);
	$meeting_code = $_POST['meeting_code']; $meeting_code = secure_numbers($meeting_code);
}

else if ($_POST['session_index'])
{
	$session_index = $_GET['session_index']; $session_index = secure_letters($session_index);
	$customer_code = $_GET['customer_code']; $customer_code = secure_numbers($customer_code);
	$status = $_GET['status']; $status = secure_string($status);
	$meeting_code = $_GET['meeting_code']; $meeting_code = secure_numbers($meeting_code);
}

$dbh = new PDO("mysql:dbname=serilox","root","");
$session = secure($session_index, $dbh);
$sth = $dbh->query("select * from customer where code = '$customer_code'"); $customer = $sth->fetch();
# if ($status eq "update") { update_details(); }
$salesman = get_salesmen();

$meeting_origin = $_POST['meeting_origin']; $meeting_origin = secure_string($meeing_origin);
$salesman = $_POST['salesman']; $salesman = secure_numbers($salesman);
$contact_person = $_POST['contact_person']; $contact_person = secure_numbers($contact_person);
$section = $_POST['section']; $section = secure_numbers($section);
$sub_section_a = $_POST['sub_section_a']; $sub_section_a = secure_numbers($sub_section_a);
$sub_section_b = $_POST['sub_section_b']; $sub_section_b = secure_numbers($sub_section_b);
$perut = $_POST['perut']; $perut = secure_string($perut); $perut = preg_replace("/'/", "/\\'/", $perut);
$comments = $_POST['comments']; $comments = secure_string($comments); $comments = pref_replace("/'/", "\\'/", $comments);
$meeting_date = $_POST['meeting_date']; $meeting_date = secure_date($meeting_date);
$meeting_hour = $_POST['meeting_hour']; $meeting_hour = secure_numbers($meeting_hour);
$meeting_minute = $_POST['meeting_minute']; $meeting_minute = secure_numbers($meeting_minute);
$meeting_time = $meeting_hour . ":" $meeting_minute . ":00";
$meeting_unix = get_unixtime($meeting_date, $meeting_time);
$mikum = $_POST['mikum']; $mikum = secure_numbers['mikum'];
$set_by = $_POST['set_by']; $set_by = secure_numbers['set_by'];
$status_item = $_POST['status_item']; $status_item = secure_numbers['status_item'];
$update_only = $_POST['update_only'];
$update_notify = $_POST['update_notify'];

if(!$meeting_code)
{
	$sth = $dbh->exec("insert into meetings set customer_code = '$customer_code', meeting_origin = '$meeting_origin', salesman = '$salesman', contact_person = '$contact_person', section = '$section',
	sub_section_a = '$sub_section_a', sub_section_b = '$sub_section_b', perut = '$perut', meeting_date = '$meeting_date', meeting_time = '$meeting_time', meeting_unix = '$meeting_unix', mikum = '$mikum',
	set_ty = '$set_by', status_item = '$status_item', comments = '$comments'];
}
else
{
	$sth = $dbh->exec("update meetings set customer_code = '$customer_code', meeting_origin = '$meeting_origin', salesman = '$salesman', contact_person = '$contact_person', section = '$section',
	sub_section_a = '$sub_section_a', sub_section_b = '$sub_section_b', perut = '$perut', meeting_date = '$meeting_date', meeting_time = '$meeting_time', meeting_unix = '$meeting_unix', mikum = '$mikum',
	set_ty = '$set_by', status_item = '$status_item', comments = '$comments' where code = '$meeting_code'];
}

