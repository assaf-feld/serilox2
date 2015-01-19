<?php

include "func.php";
include "assaftime.php";

$dbh = new PDO("mysql:dbname=rinatours","root","");
$session = secure($dbh);
$now = time();

file_this("status: $session[status], customer_code: $session[customer_code]", "uc1");

# if ($session[status] == "add")
if (!$session[customer_code] and $session[status] == "update")
{
	file_this("we are in the no customer code zone", "uc1");
	$customer_name = secure_string($_POST[customer_name]); $customer_name = addslashes($customer_name);
	$city = secure_string($_POST[city]); $city = addslashes($city);
	$address = secure_string($_POST[address]); $address = addslashes($address);
	$phone_1 = secure_string($_POST[phone_1]); 
	$phone_2 = secure_string($_POST[phone_2]); 
	$email = secure_extended($_POST[email_1]);
	$birth_date = secure_string($_POST[birth_date]); $check_birth_date = get_unixtime($birth_date);
	$title = secure_numbers($_POST[title]);
	$customer_last_name = secure_string($_POST[customer_last_name]); $customer_last_name = addslashes($customer_last_name);
	$passport_number = secure_string($_POST[passport_number]);
	$passport_exp_date = secure_string($_POST[passport_exp_date]); 
	$passport_check = get_unixtime($passport_exp_date); if($passport_check < $now) { $passport_line = '$("#passport_exp_div", window.parent.document).fadeIn(300).delay(800).fadeOut(300);'; }
	file_this("performing the dbh thing now!!", "uc1");
	if ($check_birth_date > $now)
	{
		$error_message = "Traveler not born yet???";
	}
	if(!$error_message)
	{
		$dbh_line = "insert into customer set customer_name = \"$customer_name\", address = \"$address\", city = \"$city\", phone_1 = \"$phone_1\", email_1 = \"$email\", birth_date = \"$birth_date\"";
		$dbh_line .= ", passport_number = \"$passport_number\", passport_exp_date = \"$passport_exp_date\", open_status = \"open\", phone_2 = \"$phone_2\", session_index = \"$session[session_index]\"";
		$dbh_line .= ", title = \"$title\", customer_last_name = \"$customer_last_name\"";
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
			window.parent.document.forms[0].customer_code.value = "$customer_code";
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
	$customer_name = secure_string($_POST[customer_name]); $customer_name = addslashes($customer_name);
	$city = secure_string($_POST[city]); $city = addslashes($city);
	$address = secure_string($_POST[address]); $address = addslashes($address);
	$phone_1 = secure_string($_POST[phone_1]); 
	$phone_2 = secure_string($_POST[phone_2]); 
	$email = secure_extended($_POST[email_1]);
	$birth_date = secure_string($_POST[birth_date]); $check_birth_date = get_unixtime($birth_date);
	$passport_number = secure_string($_POST[passport_number]);
	$passport_exp_date = secure_string($_POST[passport_exp_date]);
	$passport_check = get_unixtime($passport_exp_date); if($passport_check < $now) { $passport_line = '$("#passport_exp_div", window.parent.document).fadeIn(300).delay(800).fadeOut(300);'; }
	$title = secure_numbers($_POST[title]);
	$customer_last_name = secure_string($_POST[customer_last_name]); $customer_last_name = addslashes($customer_last_name);
	if ($check_birth_date > $now)
	{
		$error_message = "Traveler not born yet???";
	}
	if(!$error_message)
	{
		$dbh_line = "update customer set customer_name = \"$customer_name\", city = \"$city\", address = \"$address\", phone_1 = \"$phone_1\", email_1 = \"$email\", birth_date = \"$birth_date\",";
		$dbh_line .= "passport_number = \"$passport_number\", passport_exp_date = \"$passport_exp_date\", phone_2 = \"$phone_2\"";
		$dbh_line .= ", title = \"$title\", customer_last_name = \"$customer_last_name\" where code = \"$session[customer_code]\"";
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
