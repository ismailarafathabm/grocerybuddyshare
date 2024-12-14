<?php 
 include_once './../gen.php';
 if($rq !== "GET"){
     echo res(false,"page Not found",[],404);
     exit();
 }
 
 include_once './../../db/db.php';
 $conn = new DBConnect();
 $cn = $conn->connectDB();

 include_once "./../../controller/userscontroller.php";
 $uc = new UsersController($cn);

 include_once './../auth.php';

 include_once './../../controller/purchasecontroller.php';
 $pcc = new PurchaseController($cn);
 echo $pcc->LoadNotClosedPuchase();
?>