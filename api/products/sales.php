<?php
include_once './../gen.php';
if ($rq !== "GET") {
    echo res(false, "Page Not Found", [], 404);
    exit();
}
$id = !isset($_GET['id']) || trim($_GET['id']) === '' ? '' : trim($_GET['id']);
if ($id === "") {
    echo res(false, "Product Missing", [], 409);
    exit();
}
include_once './../../db/db.php';
$conn = new DBConnect();
$cn = $conn->connectDB();

include_once "./../../controller/userscontroller.php";
$uc = new UsersController($cn);

include_once './../auth.php';

require_once "./../../controller/salescontroller.php";
$pc = new SalesController($cn);
echo $pc->Itemsaleshistory($id);
exit();
