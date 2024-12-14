<?php
include_once './../gen.php';
if ($rq !== "POST") {
    echo res(false, "Page Not Found", [], 404);
    exit();
}
extract($_POST);

//validations
$salesCustomerName = !isset($salesCustomerName) || trim($salesCustomerName) === "" ? "" : trim($salesCustomerName);
$salesCustomerPhone = !isset($salesCustomerPhone) || trim($salesCustomerPhone) === "" ? "" : trim($salesCustomerPhone);
$salesCustomerAddress = !isset($salesCustomerAddress) || trim($salesCustomerAddress) === "" ? "" : trim($salesCustomerAddress);

$salesPayentType = !isset($salesPayentType) || trim($salesPayentType) === "" ? "" : trim($salesPayentType);
if ($salesPayentType === "") {
    echo res(false, "Enter Payment Method", [], 409);
    exit();
}
$salesRefNo = !isset($salesRefNo) || trim($salesRefNo) === "" ? "" : trim($salesRefNo);
if ($salesRefNo === "") {
    echo res(false, "Sales Code Missing", [], 409);
    exit();
}
$salesCustomerPaid = !isset($salesCustomerPaid) || trim($salesCustomerPaid) === "" ? "" : trim($salesCustomerPaid);
if ($salesCustomerPaid === "") {
    echo res(false, "Enter Paid Amount", [], 409);
    exit();
}
if (!is_numeric($salesCustomerPaid)) {
    echo res(false, "Paid Value Not valid Format", [], 409);
    exit();
}
$salesCustomerBalance = !isset($salesCustomerBalance) || trim($salesCustomerBalance) === "" ? "" : trim($salesCustomerBalance);
if ($salesCustomerBalance === "") {
    echo res(false, "Enter Balance Amount", [], 409);
    exit();
}
if (!is_numeric($salesCustomerBalance)) {
    echo res(false, "Balance Value Not valid Format", [], 409);
    exit();
}
$billtotal = !isset($billtotal) || trim($billtotal) === "" ? "" : trim($billtotal);
if ($billtotal === "") {
    echo res(false, "Bill Total Missing", [], 409);
    exit();
}
if (!is_numeric($billtotal)) {
    echo res(false, "Bill Total not valid number format", [], 409);
    exit();
}
$salesOthers = !isset($salesOthers) || trim($salesOthers) === "" ? 0 : trim($salesOthers);
if(!is_numeric($salesOthers)){
    echo res(false,"Discount Value Not valid Format",[],409);
    exit();
}
//validate
include_once './../../db/db.php';
$conn = new DBConnect();
$cn = $conn->connectDB();

include_once "./../../controller/userscontroller.php";
$uc = new UsersController($cn);

include_once './../auth.php';

//save new customer
$usedPoints = 0;
if (trim($salesCustomerPhone) !== "") {
    //save new customer 
    $ICustomer = array(
        ":customerName" => $salesCustomerName,
        ":customerPhone" => $salesCustomerPhone,
        ":customerAddress" => $salesCustomerAddress,
    );
    include_once "./../../controller/customercontroller.php";
    $cuc = new CustomerController($cn);
    $cuc->SaveNewCustomer($ICustomer);

    include_once './../../controller/salespointscontroller.php';
    $sspc = new SalesPointController($cn);

    $isCustomerUsingpoints = !isset($isCustomerUsingpoints) || trim($isCustomerUsingpoints) === "" ? 'No' : trim($isCustomerUsingpoints);
    if ($isCustomerUsingpoints === "Yes") {
        $usingPoints = !isset($usingPoints) || trim($usingPoints) === "" ? "" : trim($usingPoints);
        if ($usingPoints === "") {
            echo res(false, "Enter Point value", [], 409);
            exit();
        }
        if (!is_numeric($usingPoints)) {
            echo res(false, "Using Points Are not valid Number Format", [], 409);
            exit();
        }
        $usedPoints = $usingPoints;
        $ICustomerSalesPointUses = array(
            ":useDate" => date('Y-m-d'),
            ":customerPhone" => $salesCustomerPhone,
            ":usedPoints" => $usingPoints,
            ":usePointSalesInvoiceNo" => $salesRefNo,
            ":cBy" => $userId,
            ":eBy" => $userId,
        );
        $issaveupoints = $sspc->UseSelesPoint($ICustomerSalesPointUses);
        if (!$issaveupoints) {
            echo res(false, "Error on Updating Use Points", [], 500);
            exit();
        }
    }
    //save point
    if ((float)$billtotal >= 0 ) {
        $customerpoint = (float)$billtotal / 100;
        $ICustomerSalesPoints = array(
            ":customerPhone" => $salesCustomerPhone,
            ":salesInvoice" => $salesRefNo,
            ":salesInvoiceTotal" => $billtotal,
            ":salesInvoicePointGet" => $customerpoint,
            ":cBy" => $userId,
            ":eBy" => $userId,
        );


        $isSavepoints = $sspc->AddSalesPoints($ICustomerSalesPoints);
        if (!$isSavepoints) {
            echo res(false, "Error on saveing points", [], 500);
            exit();
        }
    }
}

$salesPaymentRefno = "SLS_{$salesRefNo}_{$userId}_" . date('Ymd');
$Payment = array(
    ":salesCustomerName" => $salesCustomerName,
    ":salesCustomerPhone" => $salesCustomerPhone,
    ":salesCustomerAddress" => $salesCustomerAddress,
    ":salesPayentType" => $salesPayentType,
    ":status" => 1,
    ":salesCustomerPaid" => $salesCustomerPaid,
    ":salesCustomerBalance" => $salesCustomerBalance,
    ":salesPaymentRefno" => $salesPaymentRefno,
    ":cususedpoints" => $usedPoints,
    ":salesOthers" => $salesOthers,
    ":payable" => $payable,
    ":salesRefNo" => $salesRefNo,

);
include_once './../../controller/salescontroller.php';
$ssc = new SalesController($cn);
echo $ssc->UpdateBillPayment($Payment);
exit();
