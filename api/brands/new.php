<?php
include_once './../gen.php';
if ($rq !== "POST") {
    echo res(false, "Page Not Found", [], 404);
    exit();
}
$brandName = !isset($_POST['brandName']) || trim($_POST['brandName']) === '' ? '' : trim($_POST['brandName']);
if(!$brandName === ""){
    echo res(false,"Check Brand Name",[],409);
    exit();
}
include_once './../../db/db.php';
$conn = new DBConnect();
$cn = $conn->connectDB();

include_once "./../../controller/userscontroller.php";
$uc = new UsersController($cn);

include_once './../auth.php';

require_once './../../controller/brandcontroller.php';
$bc = new BrandController($cn);
$IBrand = array(
    ":brandName" => $brandName,
    ":status" => 1,
    ":cBy" => $userId,
    ":eBy" => $userId,
);
echo $bc->SaveNewBrand($IBrand);
exit();


