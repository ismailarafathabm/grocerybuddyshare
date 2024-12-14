<?php
include_once './../gen.php';
if ($rq !== "POST") {
    echo res(false, "Page Not Found", [], 404);
    exit();
}
extract($_POST);
$id =!isset($_GET['id'])  || trim($_GET['id']) === "" ? "" : trim($_GET['id']);
if($id === ""){
    echo res(false,'Customer Id Missing',[],409);
    exit;
}
include_once './../../db/db.php';
$conn = new DBConnect();
$cn = $conn->connectDB();

include_once "./../../controller/userscontroller.php";
$uc = new UsersController($cn);

include_once './../auth.php';

require_once './../../controller/customercontroller.php';
$bc = new CustomerController($cn);
$Icustomer = array(
    ":customerName" => $customerName,
    ":customerAddress" => $customerAddress,
    ":id" => $id
);
echo $bc->UpdateCustomer($Icustomer);exit;
