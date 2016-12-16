<?php 
header("Access-Control-Allow-Origin: *");
require 'Slim/Slim.php';
require 'vendor/lib/db.php';

$app = new Slim();

$app->get('/rest', 'help');
$app->post('/rest/login', 'login');
$app->post('/rest/register', 'register');
$app->post('/rest/facebook', 'facebookLogin');
$app->get('/rest/:email',  'getUser');
$app->get('/rest/events/:status','getEvents');
$app->get('/rest/foruser/:userEmail','getEventsforUser');
$app->get('/rest/event/:id','getEventDtl');
$app->get('/rest/category/:status','getCategories');
$app->post('/rest/create','CreateEvent');
$app->post('/rest/sales','tkSales');
$app->post('/rest/paid','tkPaid');
$app->post('/rest/tkuser','tkUser');
$app->post('/rest/donate','tkDonate');
$app->post('/rest/suscriber','suscriber');
$app->post('/rest/update','profile');
$app->post('/rest/picture','updateProf');
$app->post('/rest/pic','updatePic');
$app->get('/rest/tickets/:id','getPrices');
$app->get('/rest/mail/','welcome');
$app->post('/rest/free','tkFree');
$app->get('/rest/purchased/:email','tkList');
$app->get('/rest/tkdtl/:id','tkDtl');
$app->get('/rest/prices/:id','tkCost');
$app->post('/rest/addpromote', 'addpromote');
$app->get('/rest/listpromote/:email', 'listpromote');
$app->get('/rest/delete/:email', 'deletepromote');
$app->post('/rest/order', 'confirmOrder');
$app->get('/rest/alluser/', 'allUsers');
$app->get('/rest/onlyrole/', 'allapps');


 $app->run();
// TODO: All methods here;


// login method
function login(){  
 $request = Slim::getInstance()->request();
 $body = $request->getBody();
 $wine = json_decode($body);
 $mail= $wine->email;
 $pass = hash('sha256', $wine->password);
 $sql = "SELECT email,userTypeId,CreatedBy FROM User WHERE email='$mail' AND password='$pass'";
  try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("email", $mail);
        $stmt->bindParam("password", $pass);
        $stmt->execute();
        $wine = $stmt->fetchObject();
        $db = null;
        echo json_encode($wine);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
  
}

function addpromote() {
    $request = Slim::getInstance()->request();
    $wine = json_decode($request->getBody());
    $sql = "INSERT INTO User (email, userTypeId,password, CreatedBy) VALUES (:email,:userTypeId,:password, :CreatedBy)";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("email", $wine->email);
        $stmt->bindParam("userTypeId", $wine->userTypeId);
        $pass=hash('sha256', $wine->password);
        $stmt->bindParam("password",$pass);
        $stmt->bindParam("CreatedBy",$wine->CreatedBy);
        $stmt->execute();
        $wine->id = $db->lastInsertId();
        $db = null;
        echo json_encode($wine);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function register() {
    $request = Slim::getInstance()->request();
    $wine = json_decode($request->getBody());
    $sql = "INSERT INTO User (email, userTypeId,password, phone) VALUES (:email,:userTypeId,:password, :phone)";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("email", $wine->email);
        $stmt->bindParam("userTypeId", $wine->userTypeId);
        $pass=hash('sha256', $wine->password);
        $stmt->bindParam("password",$pass);
        $stmt->bindParam("phone", $wine->phone);
        $stmt->execute();
        $wine->id = $db->lastInsertId();
        $db = null;
        echo json_encode($wine);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function facebookLogin() {
    $request = Slim::getInstance()->request();
    $wine = json_decode($request->getBody());
    $sql = "INSERT INTO User (email, userTypeId,imgUrl,firstName) VALUES (:email,:userTypeId,:imgUrl, :firstName)";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("email", $wine->email);
        $stmt->bindParam("userTypeId", $wine->userTypeId);
        $stmt->bindParam("imgUrl",$wine->picture);
         $stmt->bindParam("firstName",$wine->firstName);
        $stmt->execute();
        $wine->id = $db->lastInsertId();
        $db = null;
        echo json_encode($wine);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}



// user data method 
function getUser($email) {
    $sql = "SELECT email, userTypeId, firstName, lastName, address, imgUrl FROM User WHERE email=:email";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("email", $email);
        $stmt->execute();
        $wine = $stmt->fetchObject();
        $db = null;
        echo json_encode($wine);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}
function listpromote($email) {
    $sql = "SELECT email, createdAt FROM User WHERE CreatedBy=:email";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("email", $email);
        $stmt->execute();
        $wine = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;
        echo json_encode($wine);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function allUsers(){
$sql = "SELECT email, firstName, lastName,phone,imgUrl,createdAt FROM User";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $wine = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;
        echo json_encode($wine);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }

}
function allapps(){
$sql = "SELECT email, firstName, lastName,phone,imgUrl,createdAt FROM User WHERE userTypeId=5";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $wine = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;
        echo json_encode($wine);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }

}
function deletepromote($email) {
    $sql = "DELETE FROM User WHERE email=:email";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("email", $email);
        $stmt->execute();
        $wine = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;
        echo json_encode($wine);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}
function getEvents($status){
     $sql = "SELECT eventId,categoryId,name,description,address,emailCo,organization,longitude,latitude,price,time,
     ticketStock,publishDate,endDate,imgUrl,videoUrl,City,status FROM Event WHERE status=:status";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("status", $status);
        $stmt->execute();
        $wine = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;
        echo json_encode($wine);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    } 
      
  }
function getEventsforUser($userEmail){
     $sql = "SELECT eventId,categoryId,name,price,time,ticketStock,publishDate,endDate,City,status FROM Event WHERE userEmail='$userEmail'";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("status", $status);
        $stmt->execute();
        $wine = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;
        echo json_encode($wine);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    } 
      
  }
   function getEventDtl($id){
     $sql = "SELECT eventId,categoryId,name,description,address,emailCo,organization,longitude,latitude,price,time,
     ticketStock,publishDate,endDate,imgUrl,videoUrl,City,status FROM Event WHERE eventId='$id'";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("eventId", $id);
        $stmt->execute();
        $wine =$stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;
        echo json_encode($wine);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    } 
      
  }
  function getCategories($status){
     $sql = "SELECT categoryId, name FROM Category WHERE status=1";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("status", $status);
        $stmt->execute();
        $wine = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;
        echo json_encode($wine);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    } 
      
  }
  
  
function CreateEvent() {
    $request = Slim::getInstance()->request();
    $wine = json_decode($request->getBody());
    $sql = "INSERT INTO Event (userEmail,categoryId,name,description,address,organization,longitude,latitude,price,time,ticketStock,ticketPendings,publishDate,endDate,imgUrl,videoUrl,City,status) VALUES (:userEmail,:categoryId,:name,:description,:address,
:organization,:longitude,:latitude,:price,:time,:ticketStock,:ticketPendings,:publishDate,:endDate,:imgUrl,:videoUrl,:City,:status)";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("userEmail", $wine->userId);
        $stmt->bindParam("categoryId", $wine->categoryId);
        $stmt->bindParam("name", $wine->name);
        $stmt->bindParam("description", $wine->description);
        $stmt->bindParam("address", $wine->address);
        $stmt->bindParam("organization", $wine->organization);
        $stmt->bindParam("longitude", $wine->longitude);
        $stmt->bindParam("latitude", $wine->latitude);
        $stmt->bindParam("price", $wine->price);
        $stmt->bindParam("time", $wine-> time);
        $stmt->bindParam("ticketStock", $wine->ticketStock);
        $stmt->bindParam("ticketPendings", $wine->ticketPendings);
        $stmt->bindParam("publishDate", $wine-> publishdate);
        $stmt->bindParam("endDate", $wine->endDate);
        $stmt->bindParam("imgUrl", $wine->img);
         $stmt->bindParam("videoUrl", $wine->video);
        $stmt->bindParam("City", $wine->city);
        $status=1;
        $stmt->bindParam("status", $status);
        $stmt->execute();
        $wine->id = $db->lastInsertId();
        $db = null;
        echo json_encode($wine);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}
function tkSales() {
    $request = Slim::getInstance()->request();
    $wine = json_decode($request->getBody());
    $sql = "INSERT INTO Ticket (eventId,Type,username,amount,quantity) VALUES (:eventId,:Type,:username, :amount,:quantity)";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("eventId", $wine->id);
        $stmt->bindParam("Type", $wine->name);
        $stmt->bindParam("username", $wine->username);
        $stmt->bindParam("amount",$wine->price);
        $stmt->bindParam("quantity",$wine->quantity);
        $stmt->execute();
        $wine->id = $db->lastInsertId();
        $db = null;
        echo json_encode($wine);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}
function tkPaid() {
    $request = Slim::getInstance()->request();
    $wine = json_decode($request->getBody());
    $sql = "INSERT INTO Ticket (eventId,Type,username,amount,quantity) VALUES (:eventId,:Type,:username, :amount,:quantity)";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("eventId", $wine->id);
        $stmt->bindParam("Type", $wine->name);
        $stmt->bindParam("username", $wine->username);
        $stmt->bindParam("amount",$wine->price);
        $stmt->bindParam("quantity",$wine->quantity);
        $stmt->execute();
        $wine->id = $db->lastInsertId();
        $db = null;
        echo json_encode($wine);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}




function tkDonate() {
    $request = Slim::getInstance()->request();
    $wine = json_decode($request->getBody());
    $sql = "INSERT INTO Ticket (eventId,Type,username,amount,quantity) VALUES (:eventId,:Type,:username, :amount,:quantity)";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("eventId", $wine->id);
        $stmt->bindParam("Type", $wine->name);
        $stmt->bindParam("username", $wine->username);
        $stmt->bindParam("amount",$wine->price);
        $stmt->bindParam("quantity",$wine->quantity);
        $stmt->execute();
        $wine->id = $db->lastInsertId();
        $db = null;
        echo json_encode($wine);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

  function suscriber() {
    $request = Slim::getInstance()->request();
    $wine = json_decode($request->getBody());
    $sql = "INSERT INTO Suscribers(email) VALUES (:email)";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("email",$wine->email);
        $stmt->execute();
        $wine->id = $db->lastInsertId();
        $db = null;
        echo json_encode($wine);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function profile() {
   $request = Slim::getInstance()->request();
 $body = $request->getBody();
 $wine = json_decode($body);
    $mail= $wine->email;
    $firstName=$wine->firstName;
    $lastName=$wine->lastName;
    $address=$wine->address;
   
    $sql = "UPDATE User SET firstName='$firstName', lastName='$lastName', address='$address' WHERE  email='$mail'";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute();
       
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}
function updatePic() {
   $request = Slim::getInstance()->request();
 $body = $request->getBody();
 $wine = json_decode($body);
    $mail= $wine->email;
    $img=$wine->img;
   
   
    $sql = "UPDATE User SET imgUrl='$img' WHERE  email='$mail'";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute();
       
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function updateProf() {
   $request = Slim::getInstance()->request();
 $body = $request->getBody();
 $wine = json_decode($body);
    $mail= $wine->email;
    $firstName=$wine->firstName;
    $lastName=$wine->lastName;
    $address=$wine->address;
    $img=$wine->imgUrl;
   
    $sql = "UPDATE User SET firstName='$firstName', lastName='$lastName', address='$address',imgUrl='$img' WHERE        email='$mail'";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute();
       
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

  function getPrices($id){
    $type=1;
   $sql = "SELECT ticketId,Type FROM Ticket WHERE eventId='$id' AND status='$type'";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("eventId", $id);
        $stmt->execute();
        $wine = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;
        echo json_encode($wine);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    } 



  }

   



function tkUser() {
    $request = Slim::getInstance()->request();
    $wine = json_decode($request->getBody());
    $sql = "INSERT INTO Ticket (eventId,Type,username,amount,quantity) VALUES (:eventId,:Type,:username, :amount,:quantity)";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("eventId", $wine->id);
        $stmt->bindParam("Type", $wine->name);
        $stmt->bindParam("username", $wine->username);
        $stmt->bindParam("amount",$wine->price);
        $stmt->bindParam("quantity",$wine->quantity);
        $stmt->execute();
        $wine->id = $db->lastInsertId();
        $db = null;
        echo json_encode($wine);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function tkFree() {
    $request = Slim::getInstance()->request();
    $wine = json_decode($request->getBody());
    $sql = "INSERT INTO Ticket (eventId,username,amount,quantity) VALUES (:eventId,:username, :amount,:quantity)";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("eventId", $wine->eventId);
        $stmt->bindParam("username", $wine->email);
        $stmt->bindParam("amount",$wine->price);
        $stmt->bindParam("quantity",$wine->quantity);
        $stmt->execute();
        $wine->id = $db->lastInsertId();
        $db = null;
        echo json_encode($wine);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}
function tkList($email) {
    $sql = "SELECT Ticket.ticketId, Ticket.eventId,Ticket.username, Ticket.qrUrl, Ticket.amount, Ticket.createdDate, Event.name FROM Ticket INNER JOIN Event ON Ticket.eventId=Event.eventId WHERE username=:email";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("email", $email);
        $stmt->execute();
        $wine =  $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;
        echo json_encode($wine);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}
function tkDtl($id) {
    $sql = "SELECT ticketId, eventId,username,qrUrl, amount, createdDate FROM Ticket WHERE ticketId='$id'";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("ticketId", $id);
        $stmt->execute();
        $wine =  $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;
        echo json_encode($wine);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function tkCost($id) {
    $type="PAID";
    $sql = "SELECT amount FROM Ticket WHERE eventId='$id'AND Type='$type'";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("eventId", $id);
        $stmt->bindParam("Type", $type);
        $stmt->execute();
        $wine =  $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;
        echo json_encode($wine);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}


function confirmOrder() {
    $request = Slim::getInstance()->request();
    $wine = json_decode($request->getBody());
    $sql = "INSERT INTO Order (userEmail,ticketId,totalAmount,status) VALUES (:userEmail, :ticketId, :totalAmount, :status)";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("userEmail", $wine->email);
        $stmt->bindParam("ticketId", $wine->ticketId);
        $stmt->bindParam("totalAmount", $wine->amount);
        $stmt->bindParam("status",$wine->status);
        $stmt->execute();
        $wine->id = $db->lastInsertId();
        $db = null;
        echo json_encode($wine);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

?>
