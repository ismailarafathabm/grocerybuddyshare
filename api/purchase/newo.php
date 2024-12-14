<?php
include_once './../gen.php';
if ($rq !== "POST") {
    echo res(false, "Page Not Found", [], 404);
    exit();
}

extract($_POST);

//validate

$purchaseUniqNo = !isset($purchaseUniqNo) || trim($purchaseUniqNo) === "" ? "" : $purchaseUniqNo;
if ($purchaseUniqNo === "") {
    echo res(false, "Enter purhcase Uniq Number", [], 409);
    exit();
}
$purchaseDate = !isset($purchaseDate) || trim($purchaseDate) === "" ? "" : $purchaseDate;
if ($purchaseDate === "") {
    echo res(false, "Enter Purchase Date", [], 409);
    exit();
}
if (!date_create($purchaseDate)) {
    echo res(false, "Your Date is not valid Format", [], 409);
    exit();
}

$purchaseSupplier = !isset($purchaseSupplier) || trim($purchaseSupplier) === "" ? "" : $purchaseSupplier;
if ($purchaseSupplier === "") {
    echo res(false, "Select Supplier", [], 409);
    exit();
}

$purchaseLotCode = !isset($purchaseLotCode) || trim($purchaseLotCode) === "" ? "" : trim($purchaseLotCode);

$purchaseProduct = !isset($purchaseProduct) || trim($purchaseProduct) === "" ? "" : trim($purchaseProduct);
if ($purchaseProduct === "") {
    echo res(false, "Enter Product", [], 409);
    exit();
}
$purchaseQty = !isset($purchaseQty) || trim($purchaseQty) === "" ? "" : trim($purchaseQty);
if ($purchaseQty === "") {
    echo res(false, "Enter Qty", [], 409);
    return;
}
if (!is_numeric($purchaseQty)) {
    echo res(false, "Check Qty Type", [], 409);
    exit();
}

$purchasePrice = !isset($purchasePrice) || trim($purchasePrice) === "" ? "" : trim($purchasePrice);
if ($purchasePrice === "") {
    echo res(false, "Enter Price", [], 409);
    return;
}
if (!is_numeric($purchasePrice)) {
    echo res(false, "Check Price Type", [], 409);
    exit();
}

$purchaseSubtot = (float)$purchaseQty * (float)$purchasePrice;
$purchaseInvoiceno = !isset($purchaseInvoiceno) || trim($purchaseInvoiceno) === "" ? "" : trim($purchaseInvoiceno);
if ($purchaseInvoiceno === "") {
    echo res(false, "Enter Invoice", [], 409);
    exit();
}
$purchaseothers = 0;
include_once './../../db/db.php';
$conn = new DBConnect();
$cn = $conn->connectDB();

include_once "./../../controller/userscontroller.php";
$uc = new UsersController($cn);

include_once './../auth.php';
$Ipurchase = array(
    ":purchaseUniqNo" => $purchaseUniqNo,
    ":purchaseDate" => date_format(date_create($purchaseDate),'Y-m-d'),
    ":purchaseSupplier" => $purchaseSupplier,
    ":purchaseLotCode" => $purchaseLotCode,
    ":purchaseProduct" => $purchaseProduct,
    ":purchaseQty" => $purchaseQty,
    ":purchasePrice" => $purchasePrice,
    ":purchaseSubtot" => $purchaseSubtot,
    ":purchaseInvoiceno" => $purchaseInvoiceno,
    ":cBy" => $userId,
    ":eBy" => $userId,
    ":purchaseothers" => 0,
    ":status" => 1
);
include_once '../../controller/purchasecontroller.php';
$pcc = new PurchaseController($cn);
echo $pcc->SaveNewPurchase($Ipurchase);
exit();
