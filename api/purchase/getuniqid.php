<?php 
    include_once './../gen.php';
    if($rq !== "GET"){
        echo res(false,"page Not found",[],404);
        exit();
    }
    $suppliercode = !isset($_GET['id']) || trim($_GET['id']) === "" ? "" : trim($_GET['id']);
    if($suppliercode === ""){
        echo res(false,"Enter Supplier Code",[],409);
        exit();
    }
    include_once './../../db/db.php';
    $conn = new DBConnect();
    $cn = $conn->connectDB();

    include_once "./../../controller/userscontroller.php";
    $uc = new UsersController($cn);

    include_once './../auth.php';

    $encsupcode = $uc->enc('enc',$suppliercode);
    $token = $encsupcode . "_" . date('Ymdhis');
    $data = array( "pcode" => $token);
    echo res(true,"ok",$data,200);
    exit();
?>