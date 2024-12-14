<?php 
    include_once './../gen.php';
    if($rq !== "GET"){
        echo res(false,"Page Not Found",[],404);
        exit();
    }
    extract($_GET);
    $id = !isset($id) || trim($id) === "" ? "" : trim($id);
    if($id === ""){
        echo res(false,"Product Id Missing",[],409);
    }
    include_once './../../db/db.php';
    $conn = new DBConnect();
    $cn = $conn->connectDB();

    include_once "./../../controller/userscontroller.php";
    $uc = new UsersController($cn);

    include_once './../auth.php';
  
    require_once "./../../controller/productcontroller.php";
    $pc = new ProductController($cn);
    echo $pc->RemoveProduct($id);
    exit();
?>