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
    $productview_access = true;
    if ($userId !== 'superadmin') {
        $getacces = json_decode($uc->_getuseraccess($userId));
        $productview_access = !isset($getacces->productview) ? false : (bool)$getacces->productview;
        
    }
    if(!$productview_access){
        echo res(false,'You Can not access this page',[],402);
        exit();
    }
    require_once "./../../controller/brandcontroller.php";
    $bc = new BrandController($cn);
    echo $bc->GetAllBrands();
    exit();

    
?>