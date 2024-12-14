<?php
include_once './../gen.php';
if ($rq !== "POST") {
    echo res(false, "Page Not Found", [], 404);
    exit();
}
extract($_POST);
include_once './../../db/db.php';
$conn = new DBConnect();
$cn = $conn->connectDB();

include_once "./../../controller/userscontroller.php";
$uc = new UsersController($cn);

include_once './../auth.php';
$qty = $salesQty;
$gst = $productgst / 2;
$salesCGSTpres = $gst;
$salesSGSTpres = $gst;
$salestot = (float)$salesSPrice * (float)$salesQty;
$salesCGSTval = ((float)$salestot * $salesCGSTpres) / 100;
$salesSGSTval = ((float)$salestot * $salesSGSTpres) / 100;
$salestotgst = ($salesCGSTval + $salesSGSTval) * (float)$salesQty;
$salessubtot = $salestot ;
$salesnetprice = $salessubtot;

extract($_POST);
$Iupdate = array(
    ":salesSPrice" => $salesSPrice,
    ":salesCGSTpres" => $salesCGSTpres,
    ":salesCGSTval" => $salesCGSTval,
    ":salesSGSTpres" => $salesSGSTpres,
    ":salesSGSTval" => $salesSGSTval,
    ":salestot" => $salestot,
    ":salestotgst" => $salestotgst,
    ":salessubtot" => $salessubtot,
    ":salesnetprice" => $salesnetprice,
    ":_id" => $id,
);
include_once './../../controller/salescontroller.php';
$ssc = new SalesController($cn);
echo $ssc->UpdateSalesProductInfo($Iupdate,$salesRefNo);
exit();
?>
