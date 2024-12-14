<?php 
     include_once './../gen.php';
     if($rq !== "GET"){
         echo res(false,"Page Not Found",[],404);
         exit();
     }
     $productBarcode = !isset($_GET['barcode']) || trim($_GET['barcode']) === '' ? '' : trim($_GET['barcode']);
     if($productBarcode === ""){
        echo res(false,"Barcode Missing",[],409);
        exit();
     }
     include_once './../../db/db.php';
     $conn = new DBConnect();
     $cn = $conn->connectDB();
 
     include_once "./../../controller/userscontroller.php";
     $uc = new UsersController($cn);
 
     include_once './../auth.php';
     
     require_once "./../../controller/productcontroller.php";
     $pc = new ProductController($cn);
     echo $pc->GetProductInfo($productBarcode);
     exit();
?>