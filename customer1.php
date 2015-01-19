<?php
include "our_func.php";
$ic = rand();
if($_POST['session_index'])
{
	$session_index = $_POST['session_index']; $session_index = preg_replace('/[^0-9a-zA-Z]/', '', $session_index);
	$status = $_POST['status']; $status = preg_replace('/[^0-9a-zA-Z_]/', '', $status);
	$customer_code = $_POST['customer_code']; $customer_code = preg_replace('/[^0-9]/', '', $customer_code);
}
else if($_GET['session_index'])
{
	$session_index = $_GET['session_index']; $session_index = preg_replace('/[^0-9a-zA-Z]/', '', $session_index);
	$status = $_GET['status']; $status = preg_replace('/[^0-9a-zA-Z_]/', '', $status);
	$customer_code = $_GET['customer_code']; $customer_code = preg_replace('/[^0-9]/', '', $customer_code);
}

$dbh = new PDO("mysql:host=localhost;dbname=serilox","root","");
if ($status == "update") 
{ 
	$customer_code = update_details($customer_code, $session_index); 
	file_this("we got back customer_code: $customer_code", "c1");
}

if ($customer_code)
{
	$sth = $dbh->query("select * from customer where code = '$customer_code'"); $customer = $sth->fetch();
	$customer[customer_name] = preg_replace('/\"/', '/&quot;/', $customer[customer_name]);
	$customer[address] = preg_replace('/\"/', '/&quot;/', $customer[address]);
	$customer[city] = preg_replace('/\"/', '/&quot;/', $customer[city]);
	$buttons_div_length = "810";
}
else
{
	$buttons_div_length = "648";
}

secure($session_index, $dbh);
$ic = rand();

?>


<!DOCTYPE html>
<html>
<head>
<title>Customer Details</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="our_styles.css">
<script>
var our_div, general_counter, general_flag;
function load_div()
{
	our_div = document.getElementById("id_contacts_div");
}

function fadein_contacts()
{
	general_counter = 0;
	// alert("okay - general counter is zero");
	our_div.style.display = "block";
	actual_fadein();
}

function actual_fadein()
{
	var real_counter;
	general_counter += 5;;
	real_counter = general_counter / 100;
	// alert("real_counter is: " + real_counter);
	our_div.style.opacity = real_counter;
	if (general_counter < 100)
	{
		setTimeout("actual_fadein()", 5);
	}
	else
	{
		return true;
	}
}

function fadeout_contacts()
{
	general_counter = 100;
	// alert("okay - general counter is zero");
	our_div.style.display = "block";
	actual_fadeout();
}

function actual_fadeout()
{
	var real_counter;
	general_counter -= 5;
	real_counter = general_counter / 100;
	// alert("real_counter is: " + real_counter);
	our_div.style.opacity = real_counter;
	if (general_counter > 0)
	{
		setTimeout("actual_fadeout()", 5);
	}
	else
	{
		our_div.style.display = "none";
		return true;
	}
}

</script>
</head>

<?php
print <<<ENDLINE
<body onload="load_div();">
<div id="id_contacts_div" class="contacts_div">
<iframe class="contacts_iframe" id="id_contacts_iframe" name="name_contacts_iframe" src="contacts.php?session_index=$session_index&customer_code=$customer_code&ic=$ic"></iframe>
</div>

ENDLINE;

print "<h1 style='width: 400px; text-align: center; margin: auto; margin-top: 25px; margin-bottom: 20px;'>";
if ($customer[customer_name]) { print "Details for $customer[customer_name]</h1>"; } 
else { print "New Customer</h1>"; }

print <<<ENDLINE

<form method="post" action="customer.php">
<input type="hidden" name="session_index" value="$session_index">
<input type="hidden" name="status" value="update">
<input type="hidden" name="customer_code" value="$customer_code">
<div class="customer_details_main">
	<div class="customer_details_line">
		<div class="customer_details_item">Customer Code</div>
		<div class="customer_details_item"><strong>$customer[code]</strong></div>
		<div class="customer_details_item">Customer Name</div>
		<input type="text" name="customer_name" value="$customer[customer_name]" class="customer_details_item" style="width: 300px;">
	</div>
	<div class="customer_details_line">
		<div class="customer_details_item">Address</div>
		<input type="text" name="address" class="customer_details_item" value="$customer[address]" style="width: 604px;">
	</div>
	<div class="customer_details_line">
		<div class="customer_details_item">City</div>
		<input type="text" name="city" class="customer_details_item" value="$customer[city]">
		<div class="customer_details_item" style="margin-left: 20px;">Zip Code</div>
		<input type="text" name="mikud" class="customer_details_item" value="$customer[mikud]" style="width: 202px;">
	</div>
	<div class="customer_details_line">
		<div class="customer_details_item">P.O.Box</div>
		<input type="text" name="pobox" class="customer_details_item" value="$customer[pobox]">
		<div class="customer_details_item" style="margin-left: 20px;">Phone 1</div>
		<input type="text" name="phone_1" class="customer_details_item" value="$customer[phone_1]" style="width: 202px;">
	</div>
	<div class="customer_details_line">
		<div class="customer_details_item">Phone 2</div>
		<input type="text" name="phone_2" class="customer_details_item" value="$customer[phone_2]">
		<div class="customer_details_item" style="margin-left: 20px;">Fax</div>
		<input type="text" name="fax" class="customer_details_item" value="$customer[fax]" style="width: 202px;">
	</div>
	<div class="customer_details_line">
		<div class="customer_details_item">Cellular Phone</div>
		<input type="text" name="cellphone_1" class="customer_details_item" value="$customer[cellphone_1]">
		<div class="customer_details_item" style="margin-left: 20px;">Email</div>
		<input type="text" name="email_1" class="customer_details_item" value="$customer[email_1]" style="width: 202px;">
	</div>
	<div class="customer_details_line">
		<div class="customer_details_item">Chet Pei</div>
		<input type="text" name="chet_pei" value="$customer[chet_pei]" class="customer_details_item">
	</div>
</div>
<div class="horizontal_buttons" style="width: {$buttons_div_length}px">
	<input type="submit" name="bn1" value="Update Details" class="horizontal_button">
	<input type="button" name="bn2" value="Main Page" class="horizontal_button" onclick="window.location='main_page.php?session_index=$session_index&ic=$ic';">
	<input type="button" name="bn3" value="Customers Query" class="horizontal_button" onclick="window.location='customers_query.php?session_index=$session_index&ic=$ic';">
	<input type="button" name="bn4" value="New Customer" class="horizontal_button" onclick="window.location='customer.php?session_index=$session_index&ic=$ic';">
ENDLINE;
	if ($customer_code) 
	{
		print <<<ENDLINE
		<input type="button" name="bn4" value="Contacts" class="horizontal_button" onclick="fadein_contacts();">
ENDLINE;
	}
	print <<<ENDLINE
</div>
</form>
ENDLINE;
print_footer();
print <<<ENDLINE
</body></html>
ENDLINE;
$dbh = NULL;

function update_details($customer_code, $session_index)
{
	global $dbh;
	$customer_name = $_POST['customer_name']; $customer_name = secure_string($customer_name); $customer_name = fix_quotes($customer_name);
	$address = $_POST['address']; $address = secure_string($address); $address = fix_quotes($address);
	$city = $_POST['city']; $city = secure_string($city); $city = fix_quotes($city);
	$mikud = $_POST['mikud']; $mikud = secure_numbers($mikud);
	$pobox = $_POST['pobox']; $pobox = secure_numbers($pobox);
	$phone_1 = $_POST['phone_1']; $phone_1 = secure_string($phone_1);
	$phone_2 = $_POST['phone_2']; $phone_2 = secure_string($phone_2);
	$fax = $_POST['fax']; $fax = secure_string($fax);
	$email_1 = $_POST['email_1']; $email_1 = secure_extended($email_1);
	$chet_pei = $_POST['chet_pei']; $chet_pei = secure_numbers($chet_pei);
	$cellphone_1 = $_POST['cellphone_1']; $cellphone_1 = secure_string($cellphone_1);
	$cellphone_2 = $_POST['cellphone_2']; $cellphone_2 = secure_string($cellphone_2);

	if(!$customer_code)
	{
		$dbh->exec("insert into customer set customer_name = '$customer_name', address = '$address', city = '$city', mikud = '$mikud', pobox = '$pobox', phone_1 = '$phone_1',
		phone_2 = '$phone_2', fax = '$fax', cellphone_1 = '$cellphone_1', cellphone_2 = '$cellphone_2', email_1 = '$email_1', email_2 = '$email_2', chet_pei = '$chet_pei', 
		session_index = '$session_index', open_status = 'open'");
		$sth = $dbh->query("select code from customer where session_index = '$session_index' and open_status = 'open'"); $customer_code = $sth->fetch(); $customer_code = $customer_code[code];
		file_this("we are returning customer_code: $customer_code: select code from customer where session_index = '$session_index' and open_status = 'open'", "c1");
		$dbh->exec("update customer set open_status = 'closed' where code = '$customer_code'");
		return $customer_code;
	}
	else if($customer_code)
	{
		$dbh->exec("update customer set customer_name = '$customer_name', address = '$address', city = '$city', mikud = '$mikud', pobox = '$pobox', phone_1 = '$phone_1',
		phone_2 = '$phone_2', fax = '$fax', cellphone_1 = '$cellphone_1', cellphone_2 = '$cellphone_2', email_1 = '$email_1', email_2 = '$email_2', chet_pei = '$chet_pei'
		where code = '$customer_code'");
		return $customer_code;
	}
}
