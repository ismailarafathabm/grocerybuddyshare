<?php 
    include_once './../gen.php';
    if($rq !== "POST"){
        echo res(false,"Page Not Found",[],404);
        exit();
    }

    extract($_POST);
    $supplierName = !isset($supplierName) || trim($supplierName) === "" ? '' : $supplierName;
    if($supplierName === ""){
        echo res(false,"Enter Supplier Name",[],409);
        exit();
    }

    $supplierCode = !isset($supplierCode) || trim($supplierCode) === "" ? '' : $supplierCode;
    if($supplierCode === ""){
        echo res(false,"Enter Supplier Code",[],409);
        exit();
    }

    $supplierPhone = !isset($supplierPhone) || trim($supplierPhone) === "" ? '' : $supplierPhone;
    if($supplierPhone === ""){
        echo res(false,"Enter Supplier Phone Number",[],409);
        exit();
    }

    $supplierAddress = !isset($supplierAddress) || trim($supplierAddress) === "" ? '' : $supplierAddress;
    $supplierGstNo = !isset($supplierGstNo) || trim($supplierGstNo) === "" ? '' : $supplierGstNo;
    $supplierPanNo = !isset($supplierPanNo) || trim($supplierPanNo) === "" ? '' : $supplierPanNo;

    include_once './../../db/db.php';
    $conn = new DBConnect();
    $cn = $conn->connectDB();
    
    include_once "./../../controller/userscontroller.php";
    $uc = new UsersController($cn);
    
    include_once './../auth.php';

    $cBy = $userId;
    $eBy = $userId;
    $ISupplier = array(
        ":supplierName" => $supplierName,
        ":supplierCode" => $supplierCode,
        ":supplierPhone" => $supplierPhone,
        ":supplierAddress" => $supplierAddress,
        ":supplierGstNo" => $supplierGstNo,
        ":supplierPanNo" => $supplierPanNo,
        ":cBy" => $cBy,
        ":eBy" => $eBy,
        ":status" => "1"
    );

    include_once './../../controller/suppliercontroller.php';
    $supc = new SupplierController($cn);
    echo $supc->SaveNewSupplier($ISupplier);
    exit();

    


