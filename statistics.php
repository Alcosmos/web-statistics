<?php

// Simple statistics collector by Alcosmos

if (true) {
	$address = "SOME_SCRIPT_IDENTIFIER_NAME_HERE";

	$dsn = "mysql:host=localhost;dbname=statistics";
	$user = "DB_USERNAME_HERE";
	$pass = "DB_PASSWORD_HERE";

	try {
		$statspdo = new PDO($dsn, $user, $pass);
	} catch (PDOException $e){
		 echo $e->getMessage();
	}


	$ip = $_SERVER['REMOTE_ADDR'];
	$request_uri = $_SERVER['REQUEST_URI'];
	$broswer = $_SERVER['HTTP_USER_AGENT'];

	$ipdata = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
	$country = $ipdata->geoplugin_countryName;


	$isnew = 1;

	$query = $statspdo -> prepare('SELECT * FROM access WHERE ip = ?;');
	$query -> bindParam(1, $_SERVER['REMOTE_ADDR']);
	$query -> execute();

	$row = $query -> fetch(PDO::FETCH_OBJ);

	if ($row != null) {
		$isnew = 0;
	}


	$query = $statspdo -> prepare('INSERT INTO access (ip, site, address, browser, country, isnew) VALUES (?, ?, ?, ?, ?, ?);');
	
	$query -> bindParam(1, $ip);
	$query -> bindParam(2, $address);
	$query -> bindParam(3, $request_uri);
	$query -> bindParam(4, $broswer);
	$query -> bindParam(5, $country);
	$query -> bindParam(6, $isnew);
	
	$query -> execute();

	$statspdo = null;
}

// End of statistics collector

?>
