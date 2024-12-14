<?php
session_start();
//you need to change configuration config and db file for run this project in your server
include_once './db/conf.php';
$sessionuser = !isset($_SESSION['gbusername']) || trim($_SESSION['gbusername']) === "" ? "" : $_SESSION['gbusername'];
$sessiontoken = !isset($_SESSION['gbtoken']) || trim($_SESSION['gbtoken']) === "" ? "" : $_SESSION['gbtoken'];
$sessionuserrole = !isset($_SESSION['gbrole']) || trim($_SESSION['gbrole']) === "" ? "" : $_SESSION['gbrole'];

if ($sessionuser === "") {
    header("http/1.0 402 Autorization Faild");
    header("location:login.php");
    exit();
}
if ($sessiontoken === "") {
    header("http/1.0 402 Autorization Faild");
    header("location:login.php");
    exit();
}
if ($sessionuserrole === "") {
    header("http/1.0 402 Autorization Faild");
    header("location:login.php");
    exit();
}
//roles 
$xaccess = array(
    "homepagesummary" => false,
    "homepagelowstock" => false,
    "salesnew" => false,
    "salesview" => false,
    "salesedit" => false,
    "purchasenew" => false,
    "purchaseview" => false,
    "purchaseedit" => false,
    "productview" => false,
    "productnew" => false,
    "productedit" => false,
    "productremove" => false,
    "supplierview" => false,
    "suppliernew" => false,
    "customerview" => false,
);
if($sessionuser === "superadmin"){
    $xaccess = array(
        "homepagesummary" => true,
        "homepagelowstock" => true,
        "salesnew" => true,
        "salesview" => true,
        "salesedit" => true,
        "purchasenew" => true,
        "purchaseview" => true,
        "purchaseedit" => true,
        "productview" => true,
        "productnew" => true,
        "productedit" => true,
        "productremove" => true,
        "supplierview" => true,
        "suppliernew" => true,
        "customerview" => true,
    );
}else{
    include_once './db/db.php';
    $cnn = new DBConnect();
    $cn = $cnn->connectDB();

    include_once './controller/userscontroller.php';
    $usc = new UsersController($cn);
    $xa = $usc->getuserrole($sessionuser);
    $xaccess = array(
        "homepagesummary" => !isset($xa->homepagesummary) ? false : $xa->homepagesummary,
        "homepagelowstock" => !isset($xa->homepagelowstock) ? false : $xa->homepagelowstock,
        "salesnew" => !isset($xa->salesnew) ? false : $xa->salesnew,
        "salesview" => !isset($xa->salesview) ? false : $xa->salesview,
        "salesedit" => !isset($xa->salesedit) ? false : $xa->salesedit,
        "purchasenew" => !isset($xa->purchasenew) ? false : $xa->purchasenew,
        "purchaseview" => !isset($xa->purchaseview) ? false : $xa->purchaseview,
        "purchaseedit" => !isset($xa->purchaseedit) ? false : $xa->purchaseedit,
        "productview" => !isset($xa->productview) ? false : $xa->productview,
        "productnew" => !isset($xa->productnew) ? false : $xa->productnew,
        "productedit" => !isset($xa->productedit) ? false : $xa->productedit,
        "productremove" => !isset($xa->productremove) ? false : $xa->productremove,
        "supplierview" => !isset($xa->supplierview) ? false : $xa->supplierview,
        "suppliernew" => !isset($xa->suppliernew) ? false : $xa->suppliernew,
        "customerview" => !isset($xa->customerview) ? false : $xa->customerview,
    );
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=0" />
    <meta name="description" content="POS - Bootstrap Admin Template" />
    <meta
        name="keywords"
        content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern,  html5, responsive" />
    <meta name="author" content="Dreamguys - Bootstrap Admin Template" />
    <meta name="robots" content="noindex, nofollow" />
    <title>SPARROW SUPER MARKET (POS) </title>

    <link
        rel="shortcut icon"
        type="image/x-icon"
        href="assets/img/favicon.jpg" />

    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="assets/plugins/toastr/toatr.css">
    <link rel="stylesheet" href="assets/css/animate.css" />
    <link rel="stylesheet" href="assets/js/node_modules/ng-hijri-gregorian-datepicker/dist/ng-hijri-gregorian-datepicker.css">

    <link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css" />

    <link
        rel="stylesheet"
        href="assets/plugins/fontawesome/css/fontawesome.min.css" />
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css" />

    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/angular-toastr.css">
    <style>
        .gridbtn {
            padding: 1px;
            display: flex;
            line-height: 1rem;
            height: 100%;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }
    </style>
</head>

<body ng-app="gbuddy">
    
    <div class="main-wrapper">
        <?php
        include_once './src/pageheader.php';
        include_once './src/sidebar.php';
        ?>
        <div class="page-wrapper pagehead" ng-view>


        </div>
    </div>

    <!-- <script src="assets/js/jquery-3.6.0.min.js"></script> -->
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/plugins/select2/js/select2.min.js"></script>
    <script src="assets/plugins/toastr/toastr.min.js"></script>
    <script src="assets/plugins/toastr/toastr.js"></script>
    <script src="assets/js/angular/angular.js"></script>
    <script src="assets/js/angular/angular-route.js"></script>
    <!-- for date time picker -->
    <script src="assets/js/node_modules/moment/moment.js"></script>
    <script src="assets/js/node_modules/moment/locale/ar-sa.js"></script>
    <script src="assets/js/node_modules/moment-hijri/moment-hijri.js"></script>
    <script src="assets/js/node_modules/ng-hijri-gregorian-datepicker/dist/ng-hijri-gregorian-datepicker.js"></script>
    <script src="assets/js/angular-animate.min.js"></script>
    <script src="assets/js/angular-toastr.tpls.js"></script>
    <!-- date time picker end -->
    <script src="assets/js/ism-grid.js"></script>
    <!-- <script src="assets/js/angular/angular.js" ></script> -->
    <!--
    <script src="assets/js/angular/angular-ui-router.js"></script> -->
    <script>
        const url = "<?php echo $url ?>";
        const api = "<?php echo $url ?>/api/";
        const m = "<?php echo $deployment?>";
        const useraccess =<?php echo json_encode($xaccess)?>;
        m === "0" ? console.log(useraccess.homepagesummary) : "";
        const gbusername = "<?php echo $sessionuser ?>";
        const gbtoken = "<?php echo $sessiontoken ?>";
        const app = angular.module('gbuddy', ['ngRoute', 'ngHijriGregorianDatepicker', 'toastr']);
        // const app = angular.module("gp",[]);
        // console.log(app)
    </script>
    <script src="./src/router.js"></script>

    <script type="module" src="./pages/home-page/dashboard.js"></script>
    <script type="module" src="./pages/users/index.js"></script>
    <script type="module" src="./pages/brands/index.js"></script>
    <script type="module" src="./pages/producttype/index.js"></script>
    <script type="module" src="./pages/productUnit/index.js"></script>
    <script type="module" src="./pages/products/index.js"></script>
    <script type="module" src="./pages/stock-entry/index.js"></script>
    <script type="module" src="./pages/suppliers/index.js"></script>
    <script type="module" src="./pages/sales/index.js"></script>
    <script type="module" src="./pages/customers/index.js"></script>

    <script src="assets/js/feather.min.js"></script>
    <script src="assets/js/jquery.slimscroll.min.js"></script>

    <script src="assets/js/bootstrap.bundle.min.js"></script>

    <script src="assets/js/script.js"></script>
</body>

</html>