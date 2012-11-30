<?php

function generateHash()
{
	return hash('sha512', md5( mt_rand().microtime(true).mt_rand().md5(microtime(false)).mt_rand() ));
}


/**
 * Note: This function does have to match \Sweany\Users::_encryptPassword()
 */
function encryptPassword($clearTextPassword, $passwordSalt)
{
	// start with some default value
	$hash = md5($passwordSalt.$clearTextPassword);

	// loop through to produce key stretching-times
	// in case of web bruteforce and hash via sha512 (strongest so far)
	for ($i=0; $i<20; $i++)
	{
		$hash = hash("sha512",$clearTextPassword.$passwordSalt.$hash);
	}
	return ($hash);
}


function displayUsage()
{
	global $argv;
	echo "===============================\n";
	echo "=  Sweany Password Generator  =\n";
	echo "===============================\n\n";
	echo "Usage:    ";
	echo "$argv[0] <cleartext password>\n\n";
}


if ( $argc != 2 ) {
	displayUsage();
	exit;
}

$pwd	= $argv[1];

$salt	= generateHash();
$enc	=  encryptPassword($pwd, $salt);

echo "===============================\n";
echo "=  Sweany Password Generator  =\n";
echo "===============================\n\n";
echo "[input]\n";
echo "------------------\n";
echo "cleartext: $pwd\n\n";
echo "[output]\n";
echo "------------------\n";
echo "password:  $enc\n";
echo "salt:      $salt\n";
