<?php

include "func.php";

$first_name = secure_string($_POST[first_name]);
$last_name = secure_string($_POST[last_name]);
$phone_1 = secure_string($_POST[phone_1]);
$email = secure_extended($_POST[email]);

file_this("first_name: $first_name, last_name: $last_name, phone_1: $phone_1, email: $email", "ul1");

?>

<html>
<body>
<h1>IF YOU SEE THIS THAN ALL IS GOOD!!!</h1>
</body>
</html>
