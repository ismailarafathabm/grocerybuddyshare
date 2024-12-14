<?php 
       include_once './../gen.php';
       if($rq !== "GET"){
           echo res(false,"Page Not Found",[],404);
           exit();
       }
       extract($_GET);
       $customerPhone = !isset($customerPhone) || trim($customerPhone) === "" ? "" : trim($customerPhone);
       if($customerPhone === ""){
            echo res(false,"Customer Phone missing",[],409);
            exit();
       }
       include_once './../../db/db.php';
       $conn = new DBConnect();
       $cn = $conn->connectDB();
   
       include_once "./../../controller/userscontroller.php";
       $uc = new UsersController($cn);
   
       include_once './../auth.php';
       
       require_once './../../controller/salespointscontroller.php';
       $cusc = new SalesPointController($cn);
       echo $cusc->GetCusomerGetPoints($customerPhone);
       exit();
?>