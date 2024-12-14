<?php
include_once './../gen.php';
if ($rq !== "POST") {
    echo res(false, "Page Not Found", [], 404);
    exit();
}

extract($_POST);
$supplierName = !isset($supplierName) || trim($supplierName) === "" ? '' : $supplierName;
if ($supplierName === "") {
    echo res(false, "Enter Supplier Name", [], 409);
    exit();
}



$supplierPhone = !isset($supplierPhone) || trim($supplierPhone) === "" ? '' : $supplierPhone;
if ($supplierPhone === "") {
    echo res(false, "Enter Supplier Phone Number", [], 409);
    exit();
}

$supplierAddress = !isset($supplierAddress) || trim($supplierAddress) === "" ? '' : $supplierAddress;
$supplierGstNo = !isset($supplierGstNo) || trim($supplierGstNo) === "" ? '' : $supplierGstNo;
$supplierPanNo = !isset($supplierPanNo) || trim($supplierPanNo) === "" ? '' : $supplierPanNo;

include_once './../../db/db.php';
$conn = new DBConnect();
$cn = $conn->connectDB();

include_once "./../../controller/userscontroller.php";
$uc = new UsersController($cn);

include_once './../auth.php';

$cBy = $userId;
$eBy = $userId;
$id = !isset($_GET['id']) || trim($_GET['id']) === "" ? "" : trim($_GET['id']);
if($id === ""){
    echo res(false,"Enter Supplier Id",[],409);
    exit();
}
$ISupplier = array(
    ":supplierName" => $supplierName,
    ":supplierPhone" => $supplierPhone,
    ":supplierAddress" => $supplierAddress,
    ":supplierGstNo" => $supplierGstNo,
    ":supplierPanNo" => $supplierPanNo,
    ":eBy" => $eBy,
    ":id" => $id,
    
);

include_once './../../controller/suppliercontroller.php';
$supc = new SupplierController($cn);
echo $supc->UpdateSupplier($ISupplier);
exit();
