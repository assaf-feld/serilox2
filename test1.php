<html>
<body>
<h3>Starting</h3>
<?php

$dbh = new PDO("mysql:host=localhost;dbname=management","root","");
$sth = $dbh->query("select username from users");
while ($details = $sth->fetch())
{
        print "<P>we have: $details[username]</p>";
}









?>
<?h3>
</body>
</html>
