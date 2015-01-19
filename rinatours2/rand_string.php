<?php

function create_session_index($quantity)
{
	$string = "";
	for ($i = 0; $i < $quantity; $i++)
	{
		$a = rand();
		$a = $a % 63;
		if ($a == 0) { $string .= "a"; }
		else if ($a == 1) { $string .= "a"; }
		else if ($a == 2) { $string .= "b"; }
		else if ($a == 3) { $string .= "c"; }
		else if ($a == 4) { $string .= "d"; }
		else if ($a == 5) { $string .= "e"; }
		else if ($a == 6) { $string .= "f"; }
		else if ($a == 7) { $string .= "g"; }
		else if ($a == 8) { $string .= "h"; }
		else if ($a == 9) { $string .= "i"; }
		else if ($a == 10) { $string .= "j"; }
		else if ($a == 11) { $string .= "k"; }
		else if ($a == 12) { $string .= "l"; }
		else if ($a == 13) { $string .= "m"; }
		else if ($a == 14) { $string .= "n"; }
		else if ($a == 15) { $string .= "o"; }
		else if ($a == 16) { $string .= "p"; }
		else if ($a == 17) { $string .= "q"; }
		else if ($a == 18) { $string .= "r"; }
		else if ($a == 19) { $string .= "s"; }
		else if ($a == 20) { $string .= "t"; }
		else if ($a == 21) { $string .= "u"; }
		else if ($a == 22) { $string .= "v"; }
		else if ($a == 23) { $string .= "w"; }
		else if ($a == 24) { $string .= "x"; }
		else if ($a == 25) { $string .= "y"; }
		else if ($a == 26) { $string .= "z"; }
		else if ($a == 27) { $string .= "A"; }
		else if ($a == 28) { $string .= "B"; }
		else if ($a == 29) { $string .= "C"; }
		else if ($a == 30) { $string .= "D"; }
		else if ($a == 31) { $string .= "E"; }
		else if ($a == 32) { $string .= "F"; }
		else if ($a == 33) { $string .= "G"; }
		else if ($a == 34) { $string .= "H"; }
		else if ($a == 35) { $string .= "I"; }
		else if ($a == 36) { $string .= "J"; }
		else if ($a == 37) { $string .= "K"; }
		else if ($a == 38) { $string .= "L"; }
		else if ($a == 39) { $string .= "M"; }
		else if ($a == 40) { $string .= "N"; }
		else if ($a == 41) { $string .= "O"; }
		else if ($a == 42) { $string .= "P"; }
		else if ($a == 43) { $string .= "Q"; }
		else if ($a == 44) { $string .= "R"; }
		else if ($a == 45) { $string .= "S"; }
		else if ($a == 46) { $string .= "T"; }
		else if ($a == 47) { $string .= "U"; }
		else if ($a == 48) { $string .= "V"; }
		else if ($a == 49) { $string .= "W"; }
		else if ($a == 50) { $string .= "X"; }
		else if ($a == 51) { $string .= "Y"; }
		else if ($a == 52) { $string .= "Z"; }
		else if ($a == 53) { $string .= "0"; }
		else if ($a == 54) { $string .= "1"; }
		else if ($a == 55) { $string .= "2"; }
		else if ($a == 56) { $string .= "3"; }
		else if ($a == 57) { $string .= "4"; }
		else if ($a == 58) { $string .= "5"; }
		else if ($a == 59) { $string .= "6"; }
		else if ($a == 60) { $string .= "7"; }
		else if ($a == 61) { $string .= "8"; }
		else if ($a == 62) { $string .= "9"; }
	}

	return $string;
}
?>
