<?php 
     include_once './../gen.php';
     if($rq !== "GET"){
         echo res(false,"Page Not Found",[],404);
         exit();
     }
     extract($_GET);
     $stdate = !isset($stdate) || trim($stdate) === "" ? date("Y-m-d") : trim($stdate);
     if(!date_create($stdate)){
        echo res(false,"check date format",[],409);
        exit();
     }
     $enddate = !isset($enddate) || trim($enddate) === "" ? date("Y-m-d") : trim($enddate);
     if(!date_create($enddate)){
        echo res(false,"check date format",[],409);
        exit();
     }
     include_once './../../db/db.php';
     $conn = new DBConnect();
     $cn = $conn->connectDB();
 
     include_once "./../../controller/userscontroller.php";
     $uc = new UsersController($cn);
     
     include_once './../auth.php';
     include_once './../../controller/salescontroller.php';
     $params = array(
        ":stdate" => date_format(date_create($stdate),"Y-m-d"),
        ":enddate" => date_format(date_create($enddate),"Y-m-d"),
     );
     $ssc = new SalesController($cn);
     echo $ssc->SalesReport($params);
     exit();
?>