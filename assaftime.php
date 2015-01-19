<?php
function presentable_to_mysql($our_date)
{	
	$our_date = split("/", $our_date);
	if ($our_date[2] >= 70 and $our_date[2] < 1900) { $our_date[2] += 1900; } // print "adding 1900\n"; }
	else if ($our_date[2] < 70) { $our_date[2] += 2000; } // print "adding 2000\n"; }
	$new_date = $our_date[2] . "-" . $our_date[1] . "-" . $our_date[0];
	return $new_date;
}

function mysql_to_presentable($our_date, $date_type)
{
	$our_date = split("-", $our_date);
	if ($date_type == "long")
	{
		$our_date[1] = sprintf("%02d", $our_date[1]);
		$our_date[2] = sprintf("%02d", $our_date[2]);
		$new_date = "$our_date[2]/$our_date[1]/$our_date[0]";
	}
	else
	{
		if ($our_date[0] >= 2000) { $our_date[2] -= 2000; } else { $our_date[2] -= 1900; }
		$our_date[1] = sprintf("%02d", $our_date[1]);
		$our_date[2] = sprintf("%02d", $our_date[2]);
		$new_date = "$our_date[2]/$our_date[1]/$our_date[0]";
	}
	
	return $new_date;
}
		
	
function get_unixtime_from_mysql($our_date, $our_time)
{
	$unixtime = strtotime("$our_date $our_time"); // print "unixtime: $unixtime\n";
	return $unixtime;
}











$a = time(); $our_time = $a;
date_default_timezone_set("Asia/Jerusalem");
$a = strtotime("2012-01-01"); // print "We have: $a\n";
$a = strtotime("2012-01-01 16:30"); // print "We have: $a\n";
$a = strtotime("2012-01-01 16:30:17"); // print "We have: $a\n";
$our_time = $a; // print "our_time is $our_time\n";
$a = strftime("%d/%m/%Y %H:%M:%S", $our_time); 
$a = strftime("%d/%m/%Y", $our_time); // print "We have: $a - preparing format time\n"; $format_time = $a;
$a = strftime("%d/%m/%Y", $our_time); // print "We have: $a\n";
$a = presentable_to_mysql("13/11/12"); // print "We have: $a\n"; $new_time = $a;
$a = get_unixtime_from_mysql($format_time, "15:10:10"); // print "We have: $a\n"; 
$a = get_unixtime_from_mysql($format_time, "15:10:30"); // print "We have: $a\n";


?>
