#!/usr/bin/perl

use strict;
use CGI;
use DBI;
use ASSAF::ASSECURE;
use ASSAF::MISC;

my ($sth, $dbh, $first_name, $last_name, $email, $phone_1);

$first_name = CGI::param("first_name"); $first_name = ASSECURE::make_comments($first_name);
$last_name = CGI::param("last_name"); $last_name = ASSECURE::make_comments($last_name);
$phone_1 = CGI::param("phone_1"); $phone_1 = ASSECURE::make_comments($phone_1);
$email = CGI::param("email"); $email = ASSECURE::make_comments($email);

MISC::file_this(qq[first_name: $first_name, last_name: $last_name, phone_1: $phone_1, email: $email], "rinarm1");
