<?php

include "our_func.php";

if ($_POST['session_index'])
{
	$session_index = $_POST['session_index']; $session_index = secure_letters($session_index);
	$status = $_POST['status']; $status = secure_letters($status);
}
else if ($_GET['session_index'])
{
	$session_index = $_GET['session_index']; $session_index = secure_letters($session_index);
	$status = $_GET['status']; $status = secure_letters($status);
}

$dbh = new PDO("mysql:host=localhost;dbname=serilox","root","");
file_this("session_index is: $session_index", "cq1");
$session = secure($session_index, $dbh);
# if ($status == "update") { update_details($_POST); }
$ic = rand();

?>


<!DOCTYPE html>
<html>
<head>
<title>Customers query</title>
<meta http-equiv="Content-Type" content="text/html; charset=uft-8">
<link rel="stylesheet" href="our_styles.css">
</head>

<body onload="document.forms[0].client_name.focus();">
<!--<div class="main_header"></div>-->
<div class="real_main">
<h1 class="customer_query">Customer Query</h1>
<form method="post" action="customers_query.php">

<?php
print <<<ENDLINE
<input type="hidden" name="session_index" value="$session_index">
ENDLINE;

if ($status == "update")
{
	$client_code = $_POST['client_code']; $client_code = secure_numbers($client_code);
	$client_name = $_POST['client_name']; $client_name = secure_string($client_name); $client_name = preg_replace('/\"/', '/\"\"/', $client_name);
	$client_address = $_POST['client_address']; $client_address = secure_string($client_address); $client_address = preg_replace('/\"/', '/\"\"/', $client_address);
	$client_phone = $_POST['client_phone']; $client_phone = secure_string($client_phone);
	$client_chet_pei = $_POST['client_chet_pei']; $client_chet_pei = secure_numbers($client_chet_pei);

	$query = "select * from customer where 1 = 1";
	if ($client_code) { $query .= " and code = '$client_code'"; }
	if ($client_name) { $query .= " and customer_name regexp '$client_name'"; }
	if ($client_address) { $query .= " and (address regexp '$client_address' or city regexp '$client_address' or mikud regexp '$client_address' or pobox regexp '$client_address')"; }
	if ($client_phone) { $query .= " and (phone_1 regexp '$client_phone' or phone_2 regexp '$client_phone' or cellphone_1 regexp '$client_phone' or cellphone_2 regexp '$client_phone')"; }
	if ($client_chet_pei) { $query .= " and chet_pei regexp '$client_chet_pei'"; }

	file_this("query: $query", "customer_query_1"); 
}

print <<<ENDLINE


<input type="hidden" name="status" value="update">

<div class="customer_query">
	<div class="customer_query_line"><div class="customer_query_title">Client Number</div><div class="customer_query_item"><input type="text" class="customer_query_item" name="client_code" value="$client_code">
	</div></div>
	<div class="customer_query_line"><div class="customer_query_title">Client Name</div><div class="customer_query_item"><input type="text" class="customer_query_item" name="client_name" value="$client_name">
	</div></div>
	<div class="customer_query_line"><div class="customer_query_title">Client Phone</div><div class="customer_query_item"><input type="text" class="customer_query_item" name="client_phone" value="$client_phone">
	</div></div>
	<div class="customer_query_line"><div class="customer_query_title">Client Address</div><div class="customer_query_item"><input type="text" class="customer_query_item" name="client_address" value="$client_address">
	</div></div>
	<div class="customer_query_line"><div class="customer_query_title">Client Chet Pei</div><div class="customer_query_item"><input type="text" class="customer_query_item" name="client_chet_pei" value="$client_chet_pei">
	</div></div>
</div>
<div class="horizontal_buttons">
	<input type="submit" name="bn1" value="Show Results" class="horizontal_button">
	<input type="button" name="bn2" value="New Customer" class="horizontal_button"

onclick="window.location='customer.php?session_index=$session_index&ic=$ic';">
<input type="button" name="bn3" value="Main Page" class="horizontal_button" 
onclick="window.location='main_page.php?session_index=$session_index&ic=$ic';">
</div>
</form>

ENDLINE;

if ($status == "update")
{

	print <<<ENDLINE
	
	<p><table class="customer_query_table"><tr>
	<td class="customer_query_title">Number</td>
	<td class="customer_query_title">Name</td>
	<td class="customer_query_title">Address</td>
	<td class="customer_query_title">City</td>
	<td class="customer_query_title">Phone 1</td>
	<td class="customer_query_title">Phone 2</td>
	<td class="customer_query_title">Fax</td>
	<td class="customer_query_title">Cellphone</td>
	<td class="customer_query_title">Email</td>
	<td class="customer_query_title">Chet pei</td>
	</tr>

ENDLINE;

	$sth = $dbh->query($query); while($details = $sth->fetch())
	{
		print <<<ENDLINE

		<tr>
		<td class="customer_query_details"><a style="color: #000;" href="customer.php?session_index=$session_index&customer_code=$details[code]&ic=$ic"><strong>$details[code]</strong></a></td>
		<td class="customer_query_details">$details[customer_name]</td>
		<td class="customer_query_details">$details[address]</td>
		<td class="customer_query_details">$details[city]</td>
		<td class="customer_query_details">$details[phone_1]</td>
		<td class="customer_query_details">$details[phone_2]</td>
		<td class="customer_query_details">$details[fax]</td>
		<td class="customer_query_details">$details[cellphone_1]</td>
		<td class="customer_query_details">$details[email_1]</td>
		<td class="customer_query_details">$details[chet_pei]</td>
		</tr>

ENDLINE;
		
	}

	print "</table>";

}

?>

</div>
<?php print_footer(); ?>

</body>
</html>

