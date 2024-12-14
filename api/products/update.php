<?php
include_once './../gen.php';
if ($rq !== "POST") {
    echo res(false, "Page Not Found", [], 404);
    exit();
}
extract($_POST);
$productBrand =  !isset($productBrand) || trim($productBrand) === "" ? "" : trim($productBrand);
if($productBrand === ""){
    echo res(false,"Select Brand from brand List",[],409);
    exit();
}
$productType =  !isset($productType) || trim($productType) === "" ? "" : trim($productType);
if($productType === ""){
    echo res(false,"Select Product Type from Product Type List",[],409);
    exit();
}
$productNametamil = !isset($productNametamil) || trim($productNametamil) === "" ? "" : trim($productNametamil);
if($productNametamil === ""){
    echo res(false,"Enter Product tamil Name",[],409);
    exit();
}
$productName = !isset($productName) || trim($productName) === "" ? "" : trim($productName);
if($productName === ""){
    echo res(false,"Enter Product Name",[],409);
    exit();
}
$productSku = !isset($productSku) || trim($productSku) === "" ? "" : trim($productSku);
$productUnitType =  !isset($productUnitType) || trim($productUnitType) === "" ? "" : trim($productUnitType);
if($productUnitType === ""){
    echo res(false,"Select Unit from Unit  List",[],409);
    exit();
}

$productMinQty =  !isset($productMinQty) || trim($productMinQty) === "" ? "" : trim($productMinQty);
if($productMinQty === ""){
    echo res(false,"Enter Minimum Stock Qty for Low stock notification",[],409);
    exit();
}
if(!is_numeric($productMinQty)){
    echo res(false,"Minimumn Qty Not A Number Format",[],409);
    exit();
}

$productPPrice =  !isset($productPPrice) || trim($productPPrice) === "" ? "" : trim($productPPrice);
if($productPPrice === ""){
    echo res(false,"Enter Purchase Price",[],409);
    exit();
}
if(!is_numeric($productPPrice)){
    echo res(false,"Purchase Price Not A Number Format",[],409);
    exit();
}

$productSPrice =  !isset($productSPrice) || trim($productSPrice) === "" ? "" : trim($productSPrice);
if($productSPrice === ""){
    echo res(false,"Enter Sales Price",[],409);
    exit();
}
if(!is_numeric($productSPrice)){
    echo res(false,"Sales Price Not A Number Format",[],409);
    exit();
}

$productMrp =  !isset($productMrp) || trim($productMrp) === "" ? "" : trim($productMrp);
if($productMrp === ""){
    echo res(false,"Enter MRP Price",[],409);
    exit();
}
if(!is_numeric($productMrp)){
    echo res(false,"MRP Price Not A Number Format",[],409);
    exit();
}


$productgst =  !isset($productgst) || trim($productgst) === "" ? "" : trim($productgst);
if($productgst === ""){
    echo res(false,"Enter GST% value",[],409);
    exit();
}
if(!is_numeric($productgst)){
    echo res(false,"GST% Not A Number Format",[],409);
    exit();
}


$productCgst =  !isset($productCgst) || trim($productCgst) === "" ? "" : trim($productCgst);
if($productCgst === ""){
    echo res(false,"Enter CGST% value",[],409);
    exit();
}
if(!is_numeric($productCgst)){
    echo res(false,"CGST% Not A Number Format",[],409);
    exit();
}

$productSgst =  !isset($productSgst) || trim($productSgst) === "" ? "" : trim($productSgst);
if($productSgst === ""){
    echo res(false,"Enter SGST% value",[],409);
    exit();
}
if(!is_numeric($productSgst)){
    echo res(false,"SGST% Not A Number Format",[],409);
    exit();
}
$productDiscount =  !isset($productDiscount) || trim($productDiscount) === "" ? "0" : trim($productDiscount);
$productLocationRack =  !isset($productLocationRack) || trim($productLocationRack) === "" ? "0" : trim($productLocationRack);
include_once './../../db/db.php';
$conn = new DBConnect();
$cn = $conn->connectDB();

include_once "./../../controller/userscontroller.php";
$uc = new UsersController($cn);

include_once './../auth.php';

$cBy = $userId;
$eBy = $userId;
$peBy = $userId;
$id = !isset($_GET['id']) || trim($_GET['id']) === "" ? "" : $_GET['id'];
if($id === ""){
    echo res(false,"Enter Product Id",[],409);
    exit();
}
$IProduct = array(
    ":productBrand" => $productBrand,
    ":productType" => $productType,
    ":productName" => $productName,
    ":productSku" => $productSku,
    ":productUnitType" => $productUnitType,
    ":productMinQty" => $productMinQty,
    ":productPPrice" => $productPPrice,
    ":productSPrice" => $productSPrice,
    ":productMrp" => $productMrp,
    ":productCgst" => $productCgst,
    ":productSgst" => $productSgst,
    ":productDiscount" => $productDiscount,
    ":productLocationRack" => $productLocationRack,
    ":eBy" => $eBy,
    ":peBy" => $peBy,
    ":productNametamil" => $productNametamil,
    ":productgst" => $productgst,
    ":id" => $id
    
);
include_once './../../controller/productcontroller.php';
$pc = new ProductController($cn);
echo $pc->UpdateProduct($IProduct);
exit();
?>