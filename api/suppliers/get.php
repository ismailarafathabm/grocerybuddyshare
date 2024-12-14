<?php
include_once './../gen.php';
if ($rq !== "GET") {
    echo res(false, "Page Not Found", [], 404);
    exit();
}
$id = !isset($_GET['id']) || trim($_GET['id']) === "" ? "" : trim($_GET['id']);
if ($id === "") {
    echo res(false, "Enter Supplier Id", [], 409);
    exit();
}
include_once './../../db/db.php';
$conn = new DBConnect();
$cn = $conn->connectDB();

include_once "./../../controller/userscontroller.php";
$uc = new UsersController($cn);

include_once './../auth.php';

require_once "./../../controller/suppliercontroller.php";
$supc = new SupplierController($cn);

echo $supc->GetSupplierInfo($id);
exit();