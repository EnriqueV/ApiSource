<?php
header('Access-Control-Allow-Origin: *');

	$serverName = "localhost";
	$db="Mrticketplus";
	$userName = "WebAdmin";
	$password = "rhbB617~";	
		
	try{
		$con = new PDO("mysql:host=$serverName;dbname=$db", $userName, $password);
		print "Connection Established\n";
	}catch(PDOException $e){
		print "Error: " .$e->getMessage()."";
		die();
	}		
	
	function closeConecction(){
		$con = null;
	}
	
	

?>
