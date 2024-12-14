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
$purchaseUniqNo = !isset($purchaseUniqNo) || trim($purchaseUniqNo) === "" ? "" : $purchaseUniqNo;
if ($purchaseUniqNo === "") {
    echo res(false, "Enter purhcase Uniq Number", [], 409);
    exit();
}
$purchaseSupplier = !isset($purchaseSupplier) || trim($purchaseSupplier) === "" ? "" : trim($purchaseSupplier);
if ($purchaseSupplier === "") {
    echo res(false, "Select Supplier", [], 200);
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
$purchaseLotCode = !isset($purchaseLotCode) || trim($purchaseLotCode) === "" ? "" : trim($purchaseLotCode);
$purchaseInvoiceno = !isset($purchaseInvoiceno) || trim($purchaseInvoiceno) === "" ? "" : trim($purchaseInvoiceno);
if ($purchaseInvoiceno === "") {
    echo res(false, "Enter Invoice", [], 409);
    exit();
}
$itemtype = !isset($itemtype) || trim($itemtype) === "" ? ""  : trim($itemtype);
if ($itemtype === "") {
    echo res(false, "Enter Product Info", [], 409);
    exit();
}
$purchaseProductx = "";
$cBy = $userId;
$eBy = $userId;
$peBy = $userId;
$productQty = !isset($productQty) || trim($productQty) === "" ? "" : trim($productQty);
if ($productQty === "") {
    echo res(false, "Enter Product Qty", [], 409);
    exit();
}
if (!is_numeric($productQty)) {
    echo res(false, "Qty Not A Valid Format", [], 409);
    exit();
}
$productPPrice =  !isset($productPPrice) || trim($productPPrice) === "" ? "" : trim($productPPrice);
if ($productPPrice === "") {
    echo res(false, "Enter Purchase Price", [], 409);
    exit();
}
if (!is_numeric($productPPrice)) {
    echo res(false, "Purchase Price Not A Number Format", [], 409);
    exit();
}

$productSPrice =  !isset($productSPrice) || trim($productSPrice) === "" ? "" : trim($productSPrice);
if ($productSPrice === "") {
    echo res(false, "Enter Sales Price", [], 409);
    exit();
}
if (!is_numeric($productSPrice)) {
    echo res(false, "Sales Price Not A Number Format", [], 409);
    exit();
}

$productMrp =  !isset($productMrp) || trim($productMrp) === "" ? "" : trim($productMrp);
if ($productMrp === "") {
    echo res(false, "Enter MRP Price", [], 409);
    exit();
}
if (!is_numeric($productMrp)) {
    echo res(false, "MRP Price Not A Number Format", [], 409);
    exit();
}
$isHaveExp =  !isset($isHaveExp) || trim($isHaveExp) === "" ? "No" : trim($isHaveExp);

if($isHaveExp === "Yes"){
    $purchaseManDate = !isset($purchaseManDate) || trim($purchaseManDate) === "" ? "" : trim($purchaseManDate);
    if($purchaseManDate === ""){
        echo res(false,"Enter Packed Date",[],409);
        exit();
    }
    if(!date_create($purchaseManDate)){
        echo res(false,"Packed Date Value is not valid Date Format",[],409);
        exit();
    }

    $purchaseExpdate = !isset($purchaseExpdate) || trim($purchaseExpdate) === "" ? "" : trim($purchaseExpdate);
    if($purchaseExpdate === ""){
        echo res(false,"Enter Expirey Date",[],409);
        exit();
    }
    if(!date_create($purchaseExpdate)){
        echo res(false,"Expirey Date Value is not valid Date Format",[],409);
        exit();
    }
}
if ($itemtype === "new") {
    //for new product 
    //echo "bocl new";
    $productBarcode = !isset($productBarcode) || trim($productBarcode) === "" ? "" : trim($productBarcode);
    if ($productBarcode === "") {
        echo res(false, "Enter Product Barcode", [], 409);
        exit();
    }
    $productBrand =  !isset($productBrand) || trim($productBrand) === "" ? "" : trim($productBrand);
    if ($productBrand === "") {
        echo res(false, "Select Brand from brand List", [], 409);
        exit();
    }
    $productType =  !isset($productType) || trim($productType) === "" ? "" : trim($productType);
    if ($productType === "") {
        echo res(false, "Select Product Type from Product Type List", [], 409);
        exit();
    }
    $productNametamil = !isset($productNametamil) || trim($productNametamil) === "" ? "" : trim($productNametamil);
    if ($productNametamil === "") {
        echo res(false, "Enter Product tamil Name", [], 409);
        exit();
    }
    $productName = !isset($productName) || trim($productName) === "" ? "" : trim($productName);
    if ($productName === "") {
        echo res(false, "Enter Product Name", [], 409);
        exit();
    }
    $productSku = !isset($productSku) || trim($productSku) === "" ? "" : trim($productSku);
    $productUnitType =  !isset($productUnitType) || trim($productUnitType) === "" ? "" : trim($productUnitType);
    if ($productUnitType === "") {
        echo res(false, "Select Unit from Unit  List", [], 409);
        exit();
    }

    $productMinQty =  !isset($productMinQty) || trim($productMinQty) === "" ? "0" : trim($productMinQty);
    if ($productMinQty === "") {
        echo res(false, "Enter Minimum Stock Qty for Low stock notification", [], 409);
        exit();
    }
    if (!is_numeric($productMinQty)) {
        echo res(false, "Minimumn Qty Not A Number Format", [], 409);
        exit();
    }

    $productPPrice =  !isset($productPPrice) || trim($productPPrice) === "" ? "" : trim($productPPrice);
    if ($productPPrice === "") {
        echo res(false, "Enter Purchase Price", [], 409);
        exit();
    }
    if (!is_numeric($productPPrice)) {
        echo res(false, "Purchase Price Not A Number Format", [], 409);
        exit();
    }

    $productSPrice =  !isset($productSPrice) || trim($productSPrice) === "" ? "" : trim($productSPrice);
    if ($productSPrice === "") {
        echo res(false, "Enter Sales Price", [], 409);
        exit();
    }
    if (!is_numeric($productSPrice)) {
        echo res(false, "Sales Price Not A Number Format", [], 409);
        exit();
    }

    $productMrp =  !isset($productMrp) || trim($productMrp) === "" ? "" : trim($productMrp);
    if ($productMrp === "") {
        echo res(false, "Enter MRP Price", [], 409);
        exit();
    }
    if (!is_numeric($productMrp)) {
        echo res(false, "MRP Price Not A Number Format", [], 409);
        exit();
    }


    $productgst =  !isset($productgst) || trim($productgst) === "" ? "" : trim($productgst);
    if ($productgst === "") {
        echo res(false, "Enter GST% value", [], 409);
        exit();
    }
    if (!is_numeric($productgst)) {
        echo res(false, "GST% Not A Number Format", [], 409);
        exit();
    }


    $productCgst =  !isset($productCgst) || trim($productCgst) === "" ? "" : trim($productCgst);
    if ($productCgst === "") {
        echo res(false, "Enter CGST% value", [], 409);
        exit();
    }
    if (!is_numeric($productCgst)) {
        echo res(false, "CGST% Not A Number Format", [], 409);
        exit();
    }

    $productSgst =  !isset($productSgst) || trim($productSgst) === "" ? "" : trim($productSgst);
    if ($productSgst === "") {
        echo res(false, "Enter SGST% value", [], 409);
        exit();
    }
    if (!is_numeric($productSgst)) {
        echo res(false, "SGST% Not A Number Format", [], 409);
        exit();
    }
    $productDiscount =  !isset($productDiscount) || trim($productDiscount) === "" ? "0" : trim($productDiscount);
    $productLocationRack =  !isset($productLocationRack) || trim($productLocationRack) === "" ? "0" : trim($productLocationRack);

    $IProduct = array(
        ":productBarcode" => $productBarcode,
        ":productBrand" => $productBrand,
        ":productType" => $productType,
        ":productName" => $productName,
        ":productSku" => $productSku,
        ":productUnitType" => $productUnitType,
        ":productStatus" => "1",
        ":productMinQty" => $productMinQty,
        ":productPPrice" => $productPPrice,
        ":productSPrice" => $productSPrice,
        ":productMrp" => $productMrp,
        ":productCgst" => $productCgst,
        ":productSgst" => $productSgst,
        ":productDiscount" => $productDiscount,
        ":productLocationRack" => $productLocationRack,
        ":cBy" => $cBy,
        ":eBy" => $eBy,
        ":peBy" => $peBy,
        ":productNametamil" => $productNametamil,
        ":productgst" => $productgst,

    );
    include_once './../../controller/productcontroller.php';
    $pc = new ProductController($cn);
    $productId = $pc->saveNewProductInPurchase($IProduct);
    if ($productId === 0) {
        echo res(false, "this barcode Already found", [], 409);
        exit();
    }
    if ($productId === -1) {
        echo res(false, "Error on saving new Product Informations", [], 409);
        exit();
    }
    $purchaseProductx = $productId;
} else {
    //echo "blc old";
    $purchaseProductx = !isset($purchaseProduct) || trim($purchaseProduct) === "" ? "" : trim($purchaseProduct);
   // echo "product = {$purchaseProductx}";
    if ($purchaseProductx === "") {
        echo res(false, "Enter Product", [], 409);
        exit();
    }
}

function _ishaveExp($s,$d){
    if($s === "Yes") return date_format(date_create($d),"Y-m-d");
    return date('Y-m-d');
}
$purchaseSubtot = (float)$productQty * (float)$productPPrice;
$purchasePaidRefno = ((float)$productPPrice * (float)$productgst/100) * $productQty;
$xi = $purchaseSubtot + $purchasePaidRefno;
$purhcaseGstval = ((float)$productPPrice * (float)$productgst / 100)+(float)$productPPrice ;
$purhcaseGsttotval = $purhcaseGstval * (float)$productQty;
$Ipurchase = array(
    ":purchaseUniqNo" => $purchaseUniqNo,
    ":purchaseDate" => date_format(date_create($purchaseDate),'Y-m-d'),
    ":purchaseSupplier" => $purchaseSupplier,
    ":purchaseLotCode" => $purchaseLotCode,
    ":purchaseProduct" => $purchaseProductx,
    ":purchaseQty" => $productQty,
    ":purchasePrice" => $productPPrice,
    ":purchaseSubtot" => $purchaseSubtot,
    ":purchaseInvoiceno" => $purchaseInvoiceno,
    ":cBy" => $userId,
    ":eBy" => $userId,
    ":purchaseothers" => 0,
    ":status" => 0,
    ":purchasePaidRefno" => $xi,
    ":purcahseHaveExpi" => $isHaveExp,
    ":purchaseManDate" => _ishaveExp($isHaveExp,$purchaseManDate),
    ":purchaseExpdate" => _ishaveExp($isHaveExp,$purchaseExpdate),
    ":purchaseDiscounttype" => 0,
    ":purchaseDiscountval" => 0,
    ":purchaseSPrice" => $productSPrice,
    ":purchaseMrp" => $productMrp,
    ":purchaseGst" => $productgst,
    ":purchaseCgst" => $productCgst,
    ":purchaseSgst" => $productSgst,
    ":purhcaseGstval" => $purhcaseGstval,
    ":purhcaseGsttotval" => $purhcaseGsttotval,
);
include_once '../../controller/purchasecontroller.php';
$pcc = new PurchaseController($cn);
echo $pcc->SaveNewPurchase($Ipurchase);
exit();


