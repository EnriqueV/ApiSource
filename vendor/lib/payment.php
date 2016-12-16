<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods:GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, x-xsrf-token');
require('conect.php');
require_once('stripe-php/lib/Stripe.php');
require_once('sentCode.php');
$device=json_decode(file_get_contents('php://input'));  //get data apps
$mount = $device->charge;
$number=$device->number;
$email=$device->email;
$id=$device->id;
$card=$device->card;
$status=1;
  Stripe::setApiKey("sk_test_VkejcTkcEhm98sifo4cuezio "); //Replace with your Secret Key
 
  $charge = Stripe_Charge::create(array(
  "amount" => $mount,
  "currency" => "usd",
  "card" => $card,
  "description" => "Charge for payment ticket"
  ));
 
    sentCode($number);

$sql = "INSERT INTO Order (userEmail,ticketId,totalAmount,status)";
$sql .= " VALUES ('".$email."', '".$id."', '".$mount."', '".$status."')";

if ($mysqli->query($sql) === TRUE)
{
    $data = array('success'=> true,'message'=>'Success message: New order is ready');
    echo json_encode($data);
}
else
{
    echo "Error: ";
}
?>