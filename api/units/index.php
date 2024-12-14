<?php 
    include_once './../gen.php';
    if($rq !== "GET"){
        echo res(false,"Page Not Found",[],404);
        exit();
    }
    include_once './../../db/db.php';
    $conn = new DBConnect();
    $cn = $conn->connectDB();

    include_once "./../../controller/userscontroller.php";
    $uc = new UsersController($cn);

    include_once './../auth.php';
    
    require_once "./../../controller/productUnitController.php";
    $bc = new ProductUnitController($cn);
    echo $bc->GetAllUnits();
    exit();

    
?>