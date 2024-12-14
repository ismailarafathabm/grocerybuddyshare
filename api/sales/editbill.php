<?php 
include_once './../gen.php';
if($rq !== "GET"){
    echo res(false,"page Not found",[],404);
    exit();
}
$salesRefNo = !isset($_GET['salesRefNo']) || trim($_GET["salesRefNo"]) === "" ? "" : trim($_GET["salesRefNo"]);
if(!$salesRefNo || $salesRefNo === ""){
   echo res(false,"Sales Id is Missing",[],409);
   exit();
}
$customerPhone = !isset($_GET['customerPhone']) || trim($_GET["customerPhone"]) === "" ? "" : trim($_GET["customerPhone"]);


include_once './../../db/db.php';
$conn = new DBConnect();
$cn = $conn->connectDB();

include_once "./../../controller/userscontroller.php";
$uc = new UsersController($cn);

include_once './../auth.php';

include_once './../../controller/salescontroller.php';
$ssc = new SalesController($cn);
$xres =  $ssc->EnableEditBill($salesRefNo);
$res = json_decode($xres);
if(!$res->isSuccess){
    echo $xres;
    exit();
}
if($customerPhone !== ""){
include_once './../../controller/salespointscontroller.php';
$sspc = new SalesPointController($cn);
$sspc->RemoveSalesPoint($customerPhone,$salesRefNo);
$sspc->RemoveUsedSalesPoints($customerPhone,$salesRefNo);
}
echo $xres;
exit();

