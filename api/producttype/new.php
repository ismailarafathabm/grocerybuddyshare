<?php
include_once './../gen.php';
if ($rq !== "POST") {
    echo res(false, "Page Not Found", [], 404);
    exit();
}
$productTypeName = !isset($_POST['productTypeName']) || trim($_POST['productTypeName']) === '' ? '' : trim($_POST['productTypeName']);
if(!$productTypeName === ""){
    echo res(false,"Check Brand Name",[],409);
    exit();
}
include_once './../../db/db.php';
$conn = new DBConnect();
$cn = $conn->connectDB();

include_once "./../../controller/userscontroller.php";
$uc = new UsersController($cn);

include_once './../auth.php';

require_once './../../controller/productTypeController.php';
$bc = new ProductTypeController($cn);
$IBrand = array(
    ":productTypeName" => $productTypeName,
    ":status" => 1,
    ":cBy" => $userId,
    ":eBy" => $userId,
);
echo $bc->SaveNewProductType($IBrand);
exit();


