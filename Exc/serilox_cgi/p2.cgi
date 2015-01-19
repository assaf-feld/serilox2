#!/usr/bin/perl

use strict;
use DBI;
use MIME::Base64;
use utf8;
use ASSAF::HEBREW_DOS;

my ($sth, $dbh, $line, @d, @c, $b, $i, $complete_line);

$dbh = DBI->connect("DBI:mysql:test","root","");

print qq[Content-Type: text/html

<htm>
<head>
<title>Reading Mail</title>
<meta charset="utf-8">
</head>

<body>];

$sth = $dbh->prepare(qq[select * from t2]); $sth->execute();
while ($line = $sth->fetchrow_hashref())
{
	if ($line->{"our_text"} =~ /Subject/)
	{
		@d = split(/8\?B\?/, $line->{"our_text"});
		for ($i = 1; $i <= $#d; $i++)
		{
			@c = split(/\?=/, $d[$i]);
			# print "<p><b>in c: $c[0]</b></p>";
			$b = $c[0];
			# print "<p><b>b so far: $b</b></p>";
			$b = MIME::Base64::decode($b);
			utf8::decode($b);
			# print "<p>Just did subject!!!!!! and it is $b</p>";
			$complete_line .= $b;
		}
		$line->{"our_text"} = $complete_line;
	}
	print qq[<p>We have: $line->{"our_text"}</p>];
}

$dbh->disconnect();

print "</body></html>";
