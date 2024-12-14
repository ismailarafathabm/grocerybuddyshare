<?php
include_once './../gen.php';
if ($rq !== "POST") {
    echo res(false, "Page Not Found", [], 404);
    exit();
}
include_once './../../db/db.php';
$conn = new DBConnect();
$cn = $conn->connectDB();

include_once "./../../controller/userscontroller.php";
$uc = new UsersController($cn);

include_once './../auth.php';

extract($_POST);
$Isave = array(
    ":purchaseothers" => $purchaseothers,
    ":purchaseType" => $purchaseType,
    ":purchasePaid" => $purchasePaid,
    
    ":purchaseDiscounttype" => $purchaseDiscounttype,
    ":purchaseDiscountval" => $purchaseDiscountval,
    ":purchaseUniqNo" => $purchaseUniqNo
);
include_once '../../controller/purchasecontroller.php';
$pcc = new PurchaseController($cn);
echo $pcc->SaveNewBill($Isave);
exit();
