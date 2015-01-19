#!/usr/bin/perl

use strict;
use DBI;

my ($sth, $dbh, $line);

$dbh = DBI->connect("DBI:mysql:test","root","");
$sth = $dbh->do(qq[delete from t2]);

print qq[Content-Type: text/html

<htm>
<head>
<title>Reading Mail</title>
<meta charset="utf-8">
</head>

<body>];

# open(MYFILE, "1396986318.V803I452012M938715.polygana.co.il");
open(MYFILE, "1396982092.V803I452010M353943.polygana.co.il");
while (<MYFILE>)
{
	$line = $_;
	$line =~ s/\"/\\"/g;
	$sth = $dbh->do(qq[insert into t2 values(NULL, "$line")]);
}

close MYFILE;
$dbh->disconnect();

print "</body></html>";
