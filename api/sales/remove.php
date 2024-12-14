<?php 
 include_once './../gen.php';
 if($rq !== "GET"){
     echo res(false,"page Not found",[],404);
     exit();
 }
 $id = !isset($_GET['id']) || trim($_GET["id"]) === "" ? "" : trim($_GET["id"]);
 if(!$id || $id === ""){
    echo res(false,"ID Missing",[],409);
    exit();
 }
 $code = !isset($_GET['code']) || trim($_GET['code']) === "" ? "" : trim($_GET["code"]);
 if(!$code || $code === ""){
    echo res(false,"Sales Code Is Missing",[],409);
    exit();
 }
 include_once './../../db/db.php';
 $conn = new DBConnect();
 $cn = $conn->connectDB();

 include_once "./../../controller/userscontroller.php";
 $uc = new UsersController($cn);

 include_once './../auth.php';

 include_once './../../controller/salescontroller.php';
 $ssc = new SalesController($cn);
 echo $ssc->RemoveItem($id,$code);
 exit();
?>