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
$refno = $purchaseUniqNo;
$purchaseSubtot = (float)$purchaseQty * (float)$purchasePrice;
$purchasePaidRefno = ((float)$purchasePrice * (float)$gst / 100) * $purchaseQty;
$xi = $purchaseSubtot + $purchasePaidRefno;
$isHaveExp =  !isset($isHaveExp) || trim($isHaveExp) === "" ? "No" : trim($isHaveExp);

if ($isHaveExp === "Yes") {
    $purchaseManDate = !isset($purchaseManDate) || trim($purchaseManDate) === "" ? "" : trim($purchaseManDate);
    if ($purchaseManDate === "") {
        echo res(false, "Enter Packed Date", [], 409);
        exit();
    }
    if (!date_create($purchaseManDate)) {
        echo res(false, "Packed Date Value is not valid Date Format", [], 409);
        exit();
    }

    $purchaseExpdate = !isset($purchaseExpdate) || trim($purchaseExpdate) === "" ? "" : trim($purchaseExpdate);
    if ($purchaseExpdate === "") {
        echo res(false, "Enter Expirey Date", [], 409);
        exit();
    }
    if (!date_create($purchaseExpdate)) {
        echo res(false, "Expirey Date Value is not valid Date Format", [], 409);
        exit();
    }
}
function _ishaveExp($s, $d)
{
    if ($s === "Yes") return date_format(date_create($d), "Y-m-d");
    return date('Y-m-d');
}
$prid = !isset($prid) || trim($prid) ===  "" ? '' : trim($prid);

if ($updateproduct === 'Yes') {
    //if product update then
    $IProduct = array(
        ":productPPrice" => $purchasePrice,
        ":productSPrice" => $purchaseSPrice,
        ":productMrp" => $purchaseMrp,
        ":productCgst" => (float)$gst / 2,
        ":productSgst" => (float)$gst / 2,
        ":productgst" => $gst,
        ":id" => $prid
    );
    include_once './../../controller/productcontroller.php';
    $ppc = new ProductController($cn);
    $ppc->_updateproductPriceInfo($IProduct);
}
$purchaseGst =  $gst;
$purchaseCgst = $gst/2;
$purchaseSgst = $gst/2;
$purhcaseGstval = (float)$purchasePrice + ($purchasePrice * $gst /100);
$purhcaseGsttotval = $purhcaseGstval *  $purchaseQty;
$Iupdate = array(
    ":purchaseQty" => $purchaseQty,
    ":purchasePrice" => $purchasePrice,
    ":purchaseSubtot" => $purchaseSubtot,
    ":purcahseHaveExpi" => $isHaveExp,
    ":purchaseManDate" => _ishaveExp($isHaveExp, $purchaseManDate),
    ":purchaseExpdate" => _ishaveExp($isHaveExp, $purchaseExpdate),
    ":purchaseSPrice" => $purchaseSPrice,
    ":purchaseMrp" => $purchaseMrp,
    ":purchasePaidRefno" => $xi,
    ":purchaseGst" => $purchaseGst,
    ":purchaseCgst" => $purchaseCgst,
    ":purchaseSgst" => $purchaseSgst,
    ":purhcaseGstval" => $purhcaseGstval,
    ":purhcaseGsttotval" => $purhcaseGsttotval,
    ":id" => $id
);
include_once '../../controller/purchasecontroller.php';
$pcc = new PurchaseController($cn);
echo $pcc->UpdateProduct($Iupdate, $refno);
exit();
