<?php 
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods:GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, x-xsrf-token');
require 'twilio/Services/Twilio.php';
$device=json_decode(file_get_contents('php://input'));  //get data apps

$number=$device->number;

$AccountSid = "ACac27dfcc0788b1773122987efa373d97"; // MGa2faa1c9e1e4dbfd5be0b8eb673d873a Your Account SID from www.twilio.com/console
$AuthToken = "cdfd0e19b40c47625167e84e34c81c57";   // Your Auth Token from www.twilio.com/console

$client = new Services_Twilio($AccountSid, $AuthToken);

$token = bin2hex(openssl_random_pseudo_bytes( 6));
$message = $client->account->messages->create(array(
    "From" => "+12024706138 ", // From a valid Twilio number
    "To" => $number,   // Text this number
    "Body" => "Hello from Mrticketplus! your transaction code is, please save the code: ".$token,
));



// Display a confirmation message on the screen
$data =$message->sid;
echo json_encode($data);


?>