<?php

include "func.php";
include "assaftime.php";

$dbh = new PDO("mysql:dbname=rinatours1","root","");
$session = secure($dbh);
file_this("status is: $session[status]", "ul1");
$ic = rand();

if ($session[status] == "add")
{
	$bizua_date = secure_date($session[bizua_date]);
	$bizua_hour = secure_numbers($session[bizua_hours]); if(!$bizua_hour) { $bizua_hour = "00"; }
	$bizua_minute = secure_numbers($session[bizua_minute]); if(!$bizua_minute) { $bizua_minute = "00"; }
	$bizua_time = "$bizua_hour:$bizua_minute";
	$bizua_time = fix_time($bizua_time);
	file_this("bizua_hour: $bizua_hour, bizua_minute: $bizua_minute, bizua_time: $bizua_time", "ul1");
	$bizua_unix = get_unixtime_from_mysql($bizua_date, $bizua_time);
	$activity = secure_numbers($session[activity]);
	$free_text = secure_string($session[free_text]);
	$free_text = addslashes($free_text);
	$our_time = time();
	if ($our_time > $bizua_unix)
	{
		$error_message = "New Lead can not be in the past";
	}
	if ($error_message)
	{
		print <<<ENDLINE
	
		<html>
		<head>
		<script src="jquery-1.11.0.min.js"></script>
		<script>
		$(document).ready(function()
		{
			$("#error_message_div", window.parent.parent.document).text("$error_message");
	                $("#error_message_div", window.parent.parent.document).fadeIn(300).delay(800).fadeOut(300);
			// window.parent.parent.location = 'customer_details.php?customer_code=$session[customer_code]&session_index=$session[session_index]&ic=$ic';
		});
		</script>
		</head>
	
		<body></body></html>
ENDLINE;
	}
	else
	{		

		$dbh_line = "insert into assignments set hitkash_date = current_date, hitkash_time = current_time, hitkash_unix = \"$our_time\", customer_code = \"$session[customer_code]\", ";
		$dbh_line .= "bizua_date = \"$bizua_date\", bizua_time = \"$bizua_time\", bizua_unix = \"$bizua_unix\", activity_type = \"$activity\", free_text = \"$free_text\", usercode = \"$session[usercode]\",
		as_type = \"new_lead\", interested_in = \"$session[interested_in]\", media_source = \"$session[media_source]\", departure_date = \"$session[departure_date]\", return_date = \"$session[return_date]\",
		people_in_party = \"$session[people_in_party]\", dates_flexible = \"$session[dates_flexible]\"";
		file_this($dbh_line, "nl1"); $dbh->exec($dbh_line);
		
		print <<<ENDLINE
	
		<html>
		<head>
		<script src="jquery-1.11.0.min.js"></script>
		<script>
		$(document).ready(function()
		{
			$("#customer_details_new_lead", window.parent.parent.document).fadeOut(300, function()
			{
				window.parent.parent.location = 'customer_details.php?customer_code=$session[customer_code]&session_index=$session[session_index]&ic=$ic';
			});
		});
		</script>
		</head>
	
		<body></body></html>
ENDLINE;
	}
}

else if($session[status] == "update_details")
{
	$bizua_date = secure_date($session[bizua_date]);
	$bizua_hour = secure_numbers($session[bizua_hours]); if(!$bizua_hour) { $bizua_hour = "00"; }
	$bizua_minute = secure_numbers($session[bizua_minute]); if(!$bizua_minute) { $bizua_minute = "00"; }
	$bizua_time = "$bizua_hour:$bizua_minute";
	$bizua_time = fix_time($bizua_time);
	file_this("bizua_hour: $bizua_hour, bizua_minute: $bizua_minute, bizua_time: $bizua_time", "ul1");
	$bizua_unix = get_unixtime_from_mysql($bizua_date, $bizua_time);
	$activity = secure_numbers($session[activity]);
	$free_text = secure_string($session[free_text]);
	$free_text = addslashes($free_text);
	$our_time = time();
	if ($our_time > $bizua_unix)
	{
		$error_message = "Lead can not be in the past";
	}
	if ($error_message)
	{
		print <<<ENDLINE
	
		<html>
		<head>
		<script src="jquery-1.11.0.min.js"></script>
		<script>
		$(document).ready(function()
		{
			$("#error_message_div", window.parent.document).text("$error_message");
	                $("#error_message_div", window.parent.document).fadeIn(300).delay(800).fadeOut(300);
			// window.parent.parent.location = 'customer_details.php?customer_code=$session[customer_code]&session_index=$session[session_index]&ic=$ic';
		});
		</script>
		</head>
	
		<body></body></html>
ENDLINE;
	}
	else
	{		

		$dbh_line = "update assignments set hitkash_date = current_date, hitkash_time = current_time, hitkash_unix = \"$our_time\", customer_code = \"$session[customer_code]\", 
		bizua_date = \"$bizua_date\", bizua_time = \"$bizua_time\", bizua_unix = \"$bizua_unix\", activity_type = \"$activity\", free_text = \"$free_text\", usercode = \"$session[usercode]\",
		interested_in = \"$session[interested_in]\", media_source = \"$session[media_source]\", departure_date = \"$session[departure_date]\", return_date = \"$session[return_date]\",
		people_in_party = \"$session[people_in_party]\", dates_flexible = \"$session[dates_flexible]\" where code = \"$session[lead_code]\"";
		file_this($dbh_line, "nl1"); $dbh->exec($dbh_line);
		
		print <<<ENDLINE
	
		<html>
		<head>
		<script src="jquery-1.11.0.min.js"></script>
		<script>
		$(document).ready(function()
		{
			$("#confirm_update_div", window.parent.document).fadeIn(300).delay(800).fadeOut(300, function()
			{
				window.parent.location = 'main_page.php?session_index=$session[session_index]&ic=$ic';
			});
		});
		</script>
		</head>
	
		<body></body></html>
ENDLINE;
	}
}



















else if($session[status] == "update")
{
	$sth = $dbh->query("select * from assignments where code = \"$session[lead_code]\""); $our_lead = $sth->fetch();
	$our_lead[free_text] = addslashes($our_lead[free_text]);
	$our_time = time();
	file_this("sub status: $session[sub_status]", "ul1");
	if ($session[sub_status] == "no_answer")
	{
		$bizua_unix = $our_time + (60 * 60 * 3);
		$our_summary = "No Answer";
	}
	else if($session[sub_status] == "busy")
	{
		$bizua_unix = $our_time + (60 * 20);
		$our_summary = "Line was busy";
	}

	$timeline = time_from_unixtime($bizua_unix);
	file_this("timeline: $timeline", "ul1");
	$date_fields = split(" ", $timeline);
	$bizua_date = $date_fields[0];
	$bizua_time = $date_fields[1];
	file_this("bizua_date: $bizua_date, bizua_time: $bizua_time", "ul1");
	$dbh->exec("update assignments set bapoal_date = current_date, bapoal_time = current_time, bapoal_unix = \"$our_time\" where code = \"$session[lead_code]\"");
	file_this("update assignments set bapoal_date = current_date, bapoal_time = current_time, bapoal_unix = \"$our_time\" where code = \"$session[lead_code]\"", "ul1");
	$dbh_line = <<<ENDLINE
	insert into assignments set customer_code = "$our_lead[customer_code]", hitkash_date = current_date, hitkash_time = current_time, hitkash_unix = "$our_time", bizua_date = "$bizua_date",
	bizua_time = "$bizua_time", bizua_unix = "$bizua_unix", assignment_type = "$our_lead[assignment_type]", free_text = "$our_summary<br>($our_lead[free_text])", session_index="$session[session_index]",
	usercode = "$session[usercode]", as_type = "assignment", media_source = "$our_lead[media_source]", media_source_2 = "$our_lead[media_source_2]", interested_in = "$our_lead[interested_in]",
	activity_type = "$our_lead[activity_type]", as_main_results = "$session[sub_status]"
ENDLINE;
	file_this("new lead: $dbh_line", "ul1");
	$dbh->exec($dbh_line);

	print <<<ENDLINE

	<html>
	<head>
	<script src="jquery-1.11.0.min.js"></script>
	<script>
	$(document).ready(function()
	{
		$("#update_confirm", window.parent.document).fadeIn(300).delay(800).fadeOut(300, function()
		{
			window.parent.location = "lead_details.php?session_index=$session[session_index]&lead_code=$our_lead[code]&ic=$ic";
		});
	});
	</script>
	</head>
	<body></body></html>
ENDLINE;
}

else if($session[status] == "update_postpone")
{
	$error_message = "";
	$our_time = time();
	$sth = $dbh->query("select * from assignments where code = \"$session[lead_code]\""); $our_lead = $sth->fetch();
	$session[free_text] = addslashes($session[free_text]);
	$session[bizua_hour] = sprintf("%02d", $session[bizua_hour]);
	$session[bizua_minute] = sprintf("%02d", $session[bizua_minute]);
	$bizua_time = "$session[bizua_hour]:$session[bizua_minute]:00";
	$bizua_unix = get_unixtime_from_mysql($session[bizua_date], $bizua_time); if ($bizua_unix < $our_time) { $error_message = "New assignment can not be in the past"; }
	if (!$error_message)
	{
		$dbh->exec("update assignments set bapoal_date = current_date, bapoal_time = current_time, bapoal_unix = \"$our_time\" where code = \"$session[lead_code]\"");
		file_this("update assignments set bapoal_date = current_date, bapoal_time = current_time, bapoal_unix = \"$our_time\" where code = \"$session[lead_code]\"");
		$dbh_line = <<<ENDLINE
		insert into assignments set customer_code = "$our_lead[customer_code]", hitkash_date = current_date, hitkash_time = current_time, hitkash_unix = "$our_time",
		bizua_date = "$session[bizua_date]", bizua_time = "$bizua_time", bizua_unix = "$bizua_unix", assignment_type = "$our_lead[assignment_type]", free_text = "$session[free_text]",
		session_index = "$session[session_index]", usercode = "$session[usercode]", as_type = "assignment", interested_in = "$our_lead[interested_in]", media_source = "$our_lead[media_source]",
		media_source_2 = "$our_lead[media_source_2]", activity_type = "$our_lead[activity_type]", as_main_results = "postpone"
ENDLINE;
		file_this("postpone query: $dbh_line", "ul1");
		$dbh->exec($dbh_line);
		print <<<ENDLINE
	
		<html>
		<head>
		<script src="jquery-1.11.0.min.js"></script>
		<script>
		$(document).ready(function()
		{
			$("#update_confirm", window.parent.document).fadeIn(300).delay(800).fadeOut(300, function()
			{
				$('body').fadeOut(300, function()
				{
					window.parent.location = "main_page.php?session_index=$session[session_index]&ic=$ic";
				});
			});
			$("#lead_details_postpone", window.parent.document).fadeOut(300);
		});
		</script>
		</head>
		<body></body></html>
ENDLINE;
	}
	else
	{
		print <<<ENDLINE
	
		<html>
		<head>
		<script src="jquery-1.11.0.min.js"></script>
		<script>
		$(document).ready(function()
		{
			$("#error_message_div", window.parent.document).text("$error_message");
			$("#error_message_div", window.parent.document).fadeIn(300).delay(1600).fadeOut(300);
			$("#lead_details_postpone", window.parent.document).fadeOut(300);
		});
		</script>
		</head>
		<body></body></html>
ENDLINE;
	}
}

else if($session[status] == "update_order")
{
	$our_time = time(); $error_message = "";
	$sth = $dbh->query("select * from assignments where code = \"$session[lead_code]\""); $our_lead = $sth->fetch();
	$session[free_text] = addslashes($session[free_text]);
	$session[bizua_hour] = sprintf("%02d", $session[bizua_hour]);
	$session[bizua_minute] = sprintf("%02d", $session[bizua_minute]);
	$bizua_time = "$session[bizua_hour]:$session[bizua_minute]:00";
	$bizua_unix = get_unixtime_from_mysql($session[bizua_date], $bizua_time); if ($bizua_unix < $our_time and $session[as_result] != "not_interested") 
	{ 
		$error_message = "New assignment can not be in the past";
	}
	if (!$error_message)
	{
		$dbh->exec("update assignments set bapoal_date = current_date, bapoal_time = current_time, bapoal_unix = \"$our_time\", as_main_results = \"update_lead\", as_result = \"$session[as_result]\" where code = \"$session[lead_code]\"");
		if ($session[as_result] != "not_interested")
		{
			$dbh_line = <<<ENDLINE
			insert into assignments set customer_code = "$our_lead[customer_code]", hitkash_date = current_date, hitkash_time = current_time, hitkash_unix = "$our_time",
			bizua_date = "$session[bizua_date]", bizua_time = "$bizua_time", bizua_unix = "$bizua_unix", assignment_type = "$our_lead[assignment_type]", free_text = "$session[free_text]",
			session_index = "$session[session_index]", usercode = "$session[usercode]", as_type = "assignment", interested_in = "$session[interested_in]", media_source = "$our_lead[media_source]",
			media_source_2 = "$our_lead[media_source_2]", activity_type = "$session[activity_type]", as_result = "price_quote"
ENDLINE;
			file_this("postpone query: $dbh_line", "ul1");
			$dbh->exec($dbh_line);
		}
		print <<<ENDLINE
	
		<html>
		<head>
		<script src="jquery-1.11.0.min.js"></script>
		<script>
		$(document).ready(function()
		{
			$("#update_confirm", window.parent.document).fadeIn(300).delay(800).fadeOut(300, function()
			{
				$('body').fadeOut(300, function()
				{
					window.parent.location = "main_page.php?session_index=$session[session_index]&ic=$ic";
				});
			});
			$("#lead_details_order", window.parent.document).fadeOut(300);
		});
		</script>
		</head>
		<body></body></html>
ENDLINE;
	}
	else
	{
		print <<<ENDLINE
	
		<html>
		<head>
		<script src="jquery-1.11.0.min.js"></script>
		<script>
		$(document).ready(function()
		{
			$("#error_message_div", window.parent.document).text("$error_message");
			$("#error_message_div", window.parent.document).fadeIn(300).delay(1600).fadeOut(300);
			$("#lead_details_order", window.parent.document).fadeOut(300);
		});
		</script>
		</head>
		<body></body></html>
ENDLINE;
	}
}

else if($session[status] == "update_not_interested")
{
	$why_not_reason = addslashes($session[why_not_reason]);
	$free_text = addslashes($session[free_text]);
	$our_time = time();
	$dbh->exec("update assignments set why_not_reason = '$why_not_reason', free_text = '$free_text', bapoal_date = current_date, bapoal_time = current_time, bapoal_unix = '$our_time', as_result = 'not_interested' where code = '$session[lead_code]'");
	file_this("update assignments set why_not_reason = '$why_not_reason', free_text = '$free_text', bapoal_date = current_date, bapoal_time = current_time, bapoal_unix = '$our_time', as_result = 'not_interested' where code = '$session[lead_code]'", "ul1");

		print <<<ENDLINE
	
		<html>
		<head>
		<script src="jquery-1.11.0.min.js"></script>
		<script>
		$(document).ready(function()
		{
			$(".lead_update_options_not", window.parent.document).fadeOut(300);
			$("#update_confirm", window.parent.document).fadeIn(300).delay(800).fadeOut(300, function()
			{
				$('body').fadeOut(300, function()
				{
					window.parent.location = "main_page.php?session_index=$session[session_index]&ic=$ic";
				});
			});
			$("#lead_details_order", window.parent.document).fadeOut(300);
		});
		</script>
		</head>
		<body></body></html>
ENDLINE;
}

else if($session[status] == "update_order_click")
{
	$our_time = time();
	$dbh->exec("update assignments set bapoal_date = current_date, bapoal_time = current_time, bapoal_unix = '$our_time', as_result = 'order', as_main_results = 'update_lead' where code = '$session[lead_code]'");
	file_this("update assignments set bapoal_date = current_date, bapoal_time = current_time, bapoal_unix = '$our_time', as_result = 'order', as_main_results = 'update_lead' where code = '$session[lead_code]'", "ul1");
}

$dbh = NULL;
