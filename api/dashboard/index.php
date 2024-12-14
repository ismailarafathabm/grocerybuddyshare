

<?php
include_once './../gen.php';
if ($rq !== "GET") {
    echo res(false, "Page Not Found", [], 404);
    exit();
}
include_once './../../db/db.php';
$conn = new DBConnect();
$cn = $conn->connectDB();
extract($_GET);
$stdate = !isset($stdate) || trim($stdate) === "" ? date("Y-m-d") : trim($stdate);
if (!date_create($stdate)) {
    echo res(false, "Check From Date format", [], 409);
    exit();
}
$enddate = !isset($enddate) || trim($enddate) === "" ? date("Y-m-d") : trim($enddate);
if (!date_create($enddate)) {
    echo res(false, "Check From Date format", [], 409);
    exit();
}
$stdate = date_format(date_create($stdate), 'Y-m-d');
$enddate = date_format(date_create($enddate), 'Y-m-d');
include_once "./../../controller/userscontroller.php";
$uc = new UsersController($cn);

include_once './../auth.php';


require_once "./../../controller/productcontroller.php";
$pc = new ProductController($cn);
$products = $pc->DashboardProducts();

include_once './../../controller/suppliercontroller.php';
$sup = new SupplierController($cn);
$suppliers = $sup->DashboardCountForSuppliers();

include_once './../../controller/purchasecontroller.php';
$ppc = new PurchaseController($cn);
$purchase = $ppc->DashboardcountsforPurchase($stdate, $enddate);

include_once './../../controller/salescontroller.php';
$ssc = new SalesController($cn);
$sales = $ssc->DashboardCountsFromSales($stdate, $enddate);
$homesummary_access = true;
$homepagelowstock = true;
if ($userId !== 'superadmin') {
    $getacces = json_decode($uc->_getuseraccess($userId));
    $homesummary_access = !isset($getacces->homepagesummary) ? false : (bool)$getacces->homepagesummary;
    $homepagelowstock = !isset($getacces->homepagelowstock) ? false : (bool)$getacces->homepagelowstock;
}
$data = array(
    "suppliers" => !$homesummary_access ? [] : $suppliers,
    "products" => !$homepagelowstock ? [] : $products,
    "purchase" => !$homesummary_access ? [] : $purchase,
    "sales" => !$homesummary_access ? [] : $sales
);



// print_r($data);

echo res(true, "ok", $data, 200);
exit();


?>