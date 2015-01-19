<?php

include "our_func.php";
if($_POST['session_index'])
{
	$session_index = $_POST['session_index']; $session_index = secure_letters($session_index);
}
else if ($_GET['session_index'])
{
	$session_index = $_GET['session_index']; $session_index = secure_letters($session_index);
}

$dbh = new PDO("mysql:host=localhost;dbname=serilox","root","");
file_this("sending $session_index", "main_page_1");
$session = secure($session_index, $dbh);
$sth = $dbh->query("select * from users where code = '$session[usercode]'"); $user = $sth->fetch();
$ic = rand();

?>

<!DOCTYPE html>
<html>
<head>
<title>Serilox CRM Main Page</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="our_styles.css">
</head>

<body>
<div class="main_title">
<h1 class="main_title">CRM Main Menu</h1><h3 class="main_title">Hello <?php print "$session[fullname]"; ?>!</h3>
</div>

<?php print <<<ENDLINE
<div class="main">
	<input type="button" class="menu_item" value="Customers" onclick="window.location='customers_query.php?session_index=$session_index&ic=$ic';"></input>
	<input type="button" class="menu_item" value="Leads Monitor"></input>
	<input type="button" class="menu_item" value="Permormance Monitor"></input>
ENDLINE;
if ($user[users_auth] == "yes") { print "<input type='button' class='menu_item' value='Users Management' onclick=\"window.location='users.php?session_index=$session_index&ic=$ic';\"></input>"; }
if ($user[tables_auth] == "yes") { print "<input type='button' class='menu_item' value='Tables Management' onclick=\"window.location='tables.php?session_index=$session_index&ic=$ic';\"></input>"; }

print <<<ENDLINE
</div>
ENDLINE;
print_footer();
print <<<ENDLINE
</body>
</html>
ENDLINE;
