<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods:GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, x-xsrf-token');
require 'src/Cloudinary.php';
require 'src/Uploader.php';
require 'src/Api.php';
require 'src/configure.php';
$pic=json_decode(file_get_contents('php://input'));  //get data apps
$photo=$pic->img;
$Username=$pic->email;
$random= uniqid();
$data=$photo;

Cloudinary\Uploader::upload($data,array(
    "public_id" => $random,
    "format" => "jpg",
    "bytes" => "120253"
  ));


$file=".jpg";
$Url="http://res.cloudinary.com/dcbi3quoh/image/upload/c_fill/".$random.$file;


echo $Url;




 ?>
