<?php
include_once './../gen.php';
if ($rq !== "POST") {
    echo res(false, "Page Not Found", [], 404);
    exit();
}
$unitType = !isset($_POST['unitType']) || trim($_POST['unitType']) === '' ? '' : trim($_POST['unitType']);
if(!$unitType === ""){
    echo res(false,"Check Unit Type",[],409);
    exit();
}
include_once './../../db/db.php';
$conn = new DBConnect();
$cn = $conn->connectDB();

include_once "./../../controller/userscontroller.php";
$uc = new UsersController($cn);

include_once './../auth.php';

require_once './../../controller/productunitcontroller.php';
$bc = new ProductUnitController($cn);
$IBrand = array(
    ":unitType" => $unitType,
    ":status" => 1,
    ":cBy" => $userId,
    ":eBy" => $userId,
);
echo $bc->SaveNewUnit($IBrand);
exit();


