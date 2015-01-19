<?php
include "our_func.php";
if ($_POST['session_index'])
{
	$session_index = $_POST['session_index']; $session_index = secure_letters($session_index);
	$status = $_POST['status']; $status = secure_letters($status);
	$customer_code = $_POST['customer_code']; $customer_code = secure_numbers($customer_code);
}
else if ($_GET['session_index'])
{
	$session_index = $_GET['session_index']; $session_index = secure_letters($session_index);
	$status = $_GET['status']; $status = secure_letters($status);
	$customer_code = $_GET['customer_code']; $customer_code = secure_numbers($customer_code);
}

$dbh = new PDO("mysql:dbname=serilox","root","");
secure($session_index, $dbh);

if ($status == "add") { add_details(); }
else if ($status == "update") { update_details(); }

print <<<ENDLINE

<!DOCTYPE html>
<html>
<head>
<title>Cutomser Contacts</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="our_styles.css">
</head>

<body style="border: 0; padding: 20px;"><div class="contacts_main_main">
<form method="post" action="contacts.php">
<input type="hidden" name="session_index" value="$session_index">
<input type="hidden" name="status" value="add">
<input type="hidden" name="customer_code" value="$customer_code">

<div class="contacts_main">
	<div class="contact_col">
		<div class="contact_label">Contact Name</div>
		<div class="contact_details"><input type="text" class="contact_details" name="contact_name"></div>
	</div>
	<div class="contact_col">
		<div class="contact_label">Phone 1</div>
		<div class="contact_details"><input type="text" class="contact_details" name="phone_1"></div>
	</div>
	<div class="contact_col">
		<div class="contact_label">Phone 2</div>
		<div class="contact_details"><input type="text" class="contact_details" name="phone_2"></div>
	</div>
	<div class="contact_col">
		<div class="contact_label">Cellphone</div>
		<div class="contact_details"><input type="text" class="contact_details" name="cellphone"></div>
	</div>
	<div class="contact_col">
		<div class="contact_label">Email</div>
		<div class="contact_details"><input type="text" class="contact_details" name="email"></div>
	</div>
	<div class="contact_col">
		<input type="submit" class="contact_button" name="bn1" value="Add contact" style="height: 50px;">
	</div>
</div></form>

ENDLINE;

$sth = $dbh->query("select * from contact where customer_code = '$customer_code'"); while ($details = $sth->fetch())
{
	$details[contact_name] = preg_replace('/\"/', '&quot;', $details[contact_name]);
	print <<<ENDLINE

	<div class="contacts_main">
	<form method="post" action="contacts.php">
	<input type="hidden" name="session_index" value="$session_index">
	<input type="hidden" name="status" value="update">
	<input type="hidden" name="customer_code" value="$customer_code">
	<input type="hidden" name="code" value="$details[code]">
		<div class="contact_col">
			<div class="contact_label">Contact Name</div>
			<div class="contact_details"><input type="text" class="contact_details" name="contact_name" value="$details[contact_name]"></div>
		</div>
		<div class="contact_col">
			<div class="contact_label">Phone 1</div>
			<div class="contact_details"><input type="text" class="contact_details" name="phone_1" value="$details[phone_1]"></div>
		</div>
		<div class="contact_col">
			<div class="contact_label">Phone 2</div>
			<div class="contact_details"><input type="text" class="contact_details" name="phone_2" value="$details[phone_2]"></div>
		</div>
		<div class="contact_col">
			<div class="contact_label">Cellphone</div>
			<div class="contact_details"><input type="text" class="contact_details" name="cellphone" value="$details[cellphone]"></div>
		</div>
		<div class="contact_col">
			<div class="contact_label">Email</div>
			<div class="contact_details"><input type="text" class="contact_details" name="email" value="$details[email]"></div>
		</div>
		<div class="contact_col">
			<input type="submit" name="bn2" value="Edit details" class="contact_button">
			<input type="submit" name="delete" value="Delete" class="contact_button" onclick="return confirm('Are you sure?');">
		</div>
	</form>
	</div>
ENDLINE;

}

print "<footer class='contacts_footer'><input type='button' class='contact_button_done' style='clear: both; margin: auto;' name='bn3' value='Done' onclick='window.parent.fadeout_contacts();'></footer>";
print "</div></body></html>";
$dbh = NULL;

function add_details()
{
	global $dbh; global $customer_code;
	$contact_name = $_POST['contact_name']; $contact_name = secure_string($contact_name); $contact_name = fix_quotes($contact_name);
	$phone_1 = $_POST['phone_1']; $phone_1 = secure_string($phone_1);
	$phone_2 = $_POST['phone_2']; $phone_2 = secure_string($phone_2);
	$cellphone = $_POST['cellphone']; $cellphone = secure_string($cellphone);
	$email = $_POST['email']; $email = secure_extended($email);

	$sth = $dbh->exec("insert into contact set customer_code = '$customer_code', contact_name = '$contact_name', phone_1 = '$phone_1', phone_2 = '$phone_2', cellphone = '$cellphone', email = '$email'");
}



function update_details()
{
	global $dbh; global $customer_code;
	$code = $_POST['code']; $code = secure_numbers($code); if(!$code) { return; }
	$delete = $_POST['delete']; $delete = secure_string($delete);
	if ($delete)
	{
		$dbh->exec("delete from contact where code = '$code'");
		return;
	}

	$contact_name = $_POST['contact_name']; $contact_name = secure_string($contact_name); $contact_name = fix_quotes($contact_name);
	$phone_1 = $_POST['phone_1']; $phone_1 = secure_string($phone_1);
	$phone_2 = $_POST['phone_2']; $phone_2 = secure_string($phone_2);
	$cellphone = $_POST['cellphone']; $cellphone = secure_string($cellphone);
	$email = $_POST['email']; $email = secure_extended($email);

	$dbh->exec("update contact set customer_code = '$customer_code', contact_name = '$contact_name', phone_1 = '$phone_1', phone_2 = '$phone_2', cellphone = '$cellphone', email = '$email'
	where code = '$code'");
}




	

?>

