<?php

include "func.php";
include "assaftime.php";

$dbh = new PDO("mysql:dbname=rinatours1","root","");
$session = secure($dbh);
$now = time();

file_this("status: $session[status], customer_code: $session[customer_code]", "uc1");

# if ($session[status] == "add")
if (!$session[customer_code] and $session[status] == "update")
{
	file_this("we are in the no customer code zone", "uc1");
	$customer_name = secure_string($_POST[customer_name]); $customer_name = addslashes($customer_name); if(!$customer_name) { $error_message = "First Name can not be blank"; }
	$customer_last_name = secure_string($_POST[customer_last_name]); $customer_last_name = addslashes($customer_last_name); if (!$customer_last_name) { $error_message = "Last Name can not be blank"; }
	if (!$customer_last_name and !$customer_name) { $error_message = "Please update traveler's name"; }
	$city = secure_string($_POST[city]); $city = addslashes($city);
	$address = secure_string($_POST[address]); $address = addslashes($address);
	$phone_1 = secure_string($_POST[phone_1]); if (!$phone_1) { $error_message = "Phone Number can not be blank"; }
	$phone_2 = secure_string($_POST[phone_2]); 
	$email = secure_extended($_POST[email_1]); if (!$email) { $error_message = "Email can not be blank"; }
	$birth_date = secure_string($_POST[birth_date]); $check_birth_date = get_unixtime($birth_date);
	$title = secure_numbers($_POST[title]);
	$passport_number = secure_string($_POST[passport_number]);
	$passport_exp_date = secure_string($_POST[passport_exp_date]); 
	$customer_status = secure_string($_POST[customer_status]);
	$passport_check = get_unixtime($passport_exp_date); if($passport_check < $now) { $passport_line = '$("#passport_exp_div", window.parent.document).fadeIn(300).delay(800).fadeOut(300);'; }
	file_this("performing the dbh thing now!!", "uc1");
	if ($check_birth_date > $now)
	{
		$error_message = "Traveler not born yet???";
	}

	if ($phone_1 or $phone_2)
	{
		$phone_check_line = "select code from customer where 1 = 1 and ( ";
		if ($phone_1) { $phone_check_line .= " phone_1 regexp '$phone_1' or phone_2 regexp '$phone_1' "; }
		if ($phone_1 and $phone_2) { $phone_check_line .= " or "; }
		if ($phone_2) { $phone_check_line .= " phone_1 regexp '$phone_2' or phone_2 regexp '$phone_2' "; }
		$phone_check_line .= " ) ";
		file_this($phone_check_line, "uc3");
		$sth = $dbh->query($phone_check_line); $check_phone = $sth->fetch();
		if ($check_phone)
		{
			$error_message = "Phone number Exists. Please check customer number $check_phone[code]";
		}
	}

	if(!$error_message)
	{
		$our_time = time();
		$dbh_line = "insert into customer set customer_name = \"$customer_name\", address = \"$address\", city = \"$city\", phone_1 = \"$phone_1\", email_1 = \"$email\", birth_date = \"$birth_date\"";
		$dbh_line .= ", passport_number = \"$passport_number\", passport_exp_date = \"$passport_exp_date\", open_status = \"open\", phone_2 = \"$phone_2\", session_index = \"$session[session_index]\"";
		$dbh_line .= ", title = \"$title\", customer_last_name = \"$customer_last_name\", create_date = current_date, create_time = current_time, create_unix = \"$our_time\", status = \"active\"";
		file_this($dbh_line, "uc1");
		$dbh->exec($dbh_line);
		$dbh_line = "select code from customer where session_index = \"$session[session_index]\" and open_status = \"open\"";
		file_this($dbh_line, "uc1");
		$sth = $dbh->query($dbh_line); $customer_code = $sth->fetch(); $customer_code = $customer_code[code];
		$dbh->exec("update customer set open_status = \"closed\" where code = \"$customer_code\"");
		file_this("customer code is: $customer_code", "uc1");
	
		$html_line .= <<<ENDLINE
	
		<html>
		<head>
		<script src="jquery-1.11.0.min.js"></script>
		<script>
		$(document).ready(function()
		{
ENDLINE;
		if ($passport_line)
		{
			$html_line .= <<<ENDLINE
			$("#passport_exp_div", window.parent.document).fadeIn(300).delay(800).fadeOut(300, function()
			{
				$("#confirm_update_div", window.parent.document).fadeIn(300).delay(800).fadeOut(300);
			})
ENDLINE;
		}
		else
		{
			$html_line .= <<<ENDLINE
			$("#confirm_update_div", window.parent.document).fadeIn(300).delay(800).fadeOut(300);
ENDLINE;
		}
		$html_line .= <<<ENDLINE
		});
		
		function update_customer_code()
		{
			// alert('updating customer code now');
			window.parent.document.forms[0].customer_code.value = "$customer_code";
		}
		</script>
		</head>
	
		<body onload="update_customer_code();"></body>
		</html>
ENDLINE;
	}
	else
	{
		$html_line .= <<<ENDLINE
	
		<html>
		<head>
		<script src="jquery-1.11.0.min.js"></script>
		<script>
		$(document).ready(function()
		{
			$("#error_message_div", window.parent.document).text("$error_message");
			$("#error_message_div", window.parent.document).fadeIn(300).delay(800).fadeOut(300);
			// $("#confirm_update_div", window.parent.document).show();
			
		});

		</script>
		</head>
	
		<body></body>
		</html>
ENDLINE;
	}	
}
else if($session[customer_code])
{
	file_this("we are in the EXIST customer code zone", "uc1");
	$code = secure_numbers($_POST[code]);
	$customer_name = secure_string($_POST[customer_name]); $customer_name = addslashes($customer_name); if(!$customer_name) { $error_message = "First Name can not be blank"; }
	$customer_last_name = secure_string($_POST[customer_last_name]); $customer_last_name = addslashes($customer_last_name); if (!$customer_last_name) { $error_message = "Last Name can not be blank"; }
	if (!$customer_last_name and !$customer_name) { $error_message = "Please update traveler's name"; }
	$city = secure_string($_POST[city]); $city = addslashes($city);
	$address = secure_string($_POST[address]); $address = addslashes($address);
	$phone_1 = secure_string($_POST[phone_1]); if (!$phone_1) { $error_message = "Phone Number can not be blank"; }
	$phone_2 = secure_string($_POST[phone_2]); 
	$email = secure_extended($_POST[email_1]); if (!$email) { $error_message = "Email can not be blank"; }
	$birth_date = secure_string($_POST[birth_date]); $check_birth_date = get_unixtime($birth_date);
	$passport_number = secure_string($_POST[passport_number]);
	$passport_exp_date = secure_string($_POST[passport_exp_date]);
	$customer_status = secure_string($_POST[customer_status]);
	$passport_check = get_unixtime($passport_exp_date); if($passport_check < $now) { $passport_line = '$("#passport_exp_div", window.parent.document).fadeIn(300).delay(800).fadeOut(300);'; }
	$title = secure_numbers($_POST[title]);
	if ($check_birth_date > $now)
	{
		$error_message = "Traveler not born yet???";
	}
	$phone_check_line = "select code from customer where code != '$session[customer_code]' and ( ";
	if ($phone_1) { $phone_check_line .= " phone_1 regexp '$phone_1' or phone_2 regexp '$phone_1' "; }
	if ($phone_2) { $phone_check_line .= " phone_1 regexp '$phone_2' or phone_2 regexp '$phone_2' "; }
	$phone_check_line .= " ) ";
	file_this($phone_check_line, "uc3");
	$sth = $dbh->query($phone_check_line); $check_phone = $sth->fetch();
	if ($check_phone)
	{
		$error_message = "Phone number Exists. Please check customer number $check_phone[code]";
	}
	if(!$error_message)
	{
		$dbh_line = "update customer set customer_name = \"$customer_name\", city = \"$city\", address = \"$address\", phone_1 = \"$phone_1\", email_1 = \"$email\", birth_date = \"$birth_date\",";
		$dbh_line .= "passport_number = \"$passport_number\", passport_exp_date = \"$passport_exp_date\", phone_2 = \"$phone_2\"";
		$dbh_line .= ", title = \"$title\", customer_last_name = \"$customer_last_name\", status = \"$customer_status\" where code = \"$session[customer_code]\"";
		file_this("update line: $dbh_line", "uc1");
		$dbh->exec($dbh_line);
	
		$html_line .= <<<ENDLINE
	
		<html>
		<head>
		<script src="jquery-1.11.0.min.js"></script>
		<script>
		$(document).ready(function()
		{
ENDLINE;
		if ($passport_line)
		{
			$html_line .= <<<ENDLINE
			$("#passport_exp_div", window.parent.document).fadeIn(300).delay(800).fadeOut(300, function()
			{
				$("#confirm_update_div", window.parent.document).fadeIn(300).delay(800).fadeOut(300);
			})
ENDLINE;
		}
		else
		{
			$html_line .= <<<ENDLINE
			$("#confirm_update_div", window.parent.document).fadeIn(300).delay(800).fadeOut(300);
ENDLINE;
		}
		$html_line .= <<<ENDLINE
		});
		</script>
		</head>
	
		<body></body>
		</html>
ENDLINE;
	}
	else
	{
		$html_line .= <<<ENDLINE
	
		<html>
		<head>
		<script src="jquery-1.11.0.min.js"></script>
		<script>
		$(document).ready(function()
		{
			$("#error_message_div", window.parent.document).text("$error_message");
			$("#error_message_div", window.parent.document).fadeIn(300).delay(800).fadeOut(300);
			window.parent.document.forms[0].customer_code.value = "$session[customer_code]";
			// $("#confirm_update_div", window.parent.document).show();
			
		});
		</script>
		</head>
	
		<body></body>
		</html>
ENDLINE;
	}
}
else
{
	file_this("We are in the NOTHING zone", "uc1");
	$html_line .= "Content-Type: text/html\n\n";
}

$dbh = NULL;
file_this("\n\n --- NEW UPDATE FILE --- \n\n$html_line\n\n", "uc2");
print $html_line;
?>
