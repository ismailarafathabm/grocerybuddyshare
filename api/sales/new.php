<?php 
    include_once './../gen.php';
    if($rq !== "POST"){
        echo res(false, "Page Not Found", [], 404);
        exit(); 
    }
    extract($_POST);
    $salesDate = !isset($salesDate) || trim($salesDate) === "" ? "" : trim($salesDate);
    if($salesDate === ""){
        echo res(false,'Enter Date',[],409);
        exit();
    }
    if(!date_create($salesDate)){
        echo res(false,"Date Format Is Not  Valid Format",[],409);
        exit();
    }
    $salesProduct = !isset($salesProduct) || trim($salesProduct) === "" ? "" : trim($salesProduct);

    if($salesProduct === ""){
        echo res(false,"Product information Is Missing",[],409);
        exit();
    }

    $salesQty = !isset($salesQty) || trim($salesQty) === "" ? "" : trim($salesQty);
    if($salesQty === ""){
        echo res(false,"Enter Qty",[],409);
        exit();
    }
    if(!is_numeric($salesQty)){
        echo res(false,"Qty Not A valid Format or Increse Qty",[],409);
        exit();
    }
    //echo $salesQty;
    if($salesQty === "0"){
        //echo "called";
        echo res(false,"Increse Qty",[],409);
        exit();
    }

    $salesSPrice = !isset($salesSPrice) || trim($salesSPrice) === "" ? "" : trim($salesSPrice);
    if($salesSPrice === ""){
        echo res(false,"Enter Price",[],409);
        exit();
    }
    if(!is_numeric($salesSPrice)){
        echo res(false,"Price Not A valid Format",[],409);
        exit();
    }
    
    include_once './../../db/db.php';
    $conn = new DBConnect();
    $cn = $conn->connectDB();
    
    include_once "./../../controller/userscontroller.php";
    $uc = new UsersController($cn);
    
    include_once './../auth.php';

    include_once './../../controller/salescontroller.php';
    $ssc = new SalesController($cn);
    //get sales refno 
    $salesRefno = !isset($salesRefno) || trim($salesRefno) === "" ? "getnew" : trim($salesRefno); 
    $salesInvoice = !isset($salesInvoice) || trim($salesInvoice) === "" ? 'getnew' : trim($salesInvoice);
    if($salesRefno === "getnew"){
        $salesRefno = (int)$ssc->genInvoiceNo();
        $salesInvoice = "BILL-" . $salesRefno;
    }
    include_once './../../controller/productcontroller.php';
    $ppc = new ProductController($cn);
    $prinfo = $ppc->getproductinfobyid($salesProduct);
    if(!isset($prinfo['pqty']) || trim($prinfo['pqty']) === "" ){
        echo res(false,"Product Not Found",[],404);
        exit();
    }
    $balqty = (float)$prinfo['balqty'];
    if((float)$salesQty > $balqty){
        echo res(false,"Out of stock",[],409);
        exit();
    }
    $salesPPrice = $salesPPrice;
    $salesCGSTpres = (float)$prinfo['productCgst'];
    $salesSGSTpres = (float)$prinfo['productSgst'];

    $salestot = (float)$salesSPrice * (float)$salesQty;
    $salesCGSTval = ((float)$salestot * $salesCGSTpres) / 100;
    $salesSGSTval = ((float)$salestot * $salesSGSTpres) / 100;
    $salestotgst = ($salesCGSTval + $salesSGSTval) *(float)$salesQty;
    $salessubtot = $salestot;
    $salesnetprice = $salessubtot + 0;

    $salesMrp = !isset($salesMrp) || trim($salesMrp) === "" ? "" : trim($salesMrp);
    if($salesMrp === ""){
        echo res(false,"MRP Missing",[],409);exit();
    }
    if(!is_numeric($salesMrp)){
        echo res(false,"MRP is not a valid Number Format",[],409);exit();
    }
    $salesPurchassRefno = !isset($salesPurchassRefno) || trim($salesPurchassRefno) === "" ? "" : trim($salesPurchassRefno);
    if($salesPurchassRefno === ""){
        echo res(false,"Referance Number is Missing",[],409);exit();
    }
    $salesHaveExp = !isset($salesHaveExp) || trim($salesHaveExp) === "" ? "No" : trim($salesHaveExp);
    
    $ISales = array(
        ":salesInvoice" => $salesInvoice,
        ":salesDate" => date_format(date_create($salesDate),'Y-m-d'),
        ":salesCustomerName" => "",
        ":salesCustomerPhone" => "",
        ":salesCustomerAddress" => "",
        ":salesProduct" => $salesProduct,
        ":salesQty" => $salesQty,
        ":salesPPrice" => $salesPPrice,
        ":salesSPrice" => $salesSPrice,
        ":salesCGSTpres" => $salesCGSTpres,
        ":salesCGSTval" => $salesCGSTval,
        ":salesSGSTpres" => $salesSGSTpres,
        ":salesSGSTval" => $salesSGSTval,
        ":salesOthers" => "0",
        ":salesPaymentType" => "",
        ":cBy" => $userId,
        ":eBy" => $userId,
        ":status" => "0",
        ":salesRefno" => $salesRefno,
        ":salesCustomerPaid" => "0",
        ":salesCustomerBalance" => "0",
        ":salesPaymentRefno" => "0",
        ":salestot" => $salestot,
        ":salestotgst" => $salestotgst,
        ":salessubtot" => $salessubtot,
        ":salesnetprice" => $salesnetprice,
        ":salesMrp" => $salesMrp,
        ":salesPurchassRefno" => $salesPurchassRefno,
        ":salesHaveExp" => $salesHaveExp,
        ":salesPackedDate" => $salesPackedDate,
        ":salesExpiryDate" => $salesExpiryDate,
    );
    echo $ssc->NewSales($ISales);
    exit();


   


?>