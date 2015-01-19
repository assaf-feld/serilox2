<html>
<head>
<meta charset="utf-8">
<body>

<?php

$file = fopen("1396986318.V803I452012M938715.polygana.co.il", "r");
while(!feof($file))
{
	$counter++;
	echo "<p>we have($counter): " . fgets($file) . "</p>";
}

fclose($file);

print "</body></html>";

