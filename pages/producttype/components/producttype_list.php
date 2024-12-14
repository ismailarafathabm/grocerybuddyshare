<?php
include_once './../../../db/conf.php';
session_start();
$gbuser = !isset($_SESSION['gbusername']) || trim($_SESSION['gbusername']) === "" ? "" : trim($_SESSION['gbusername']);
$gbtoken = !isset($_SESSION['gbtoken']) || trim($_SESSION['gbtoken']) === "" ? "" : trim($_SESSION['gbtoken']);
$gbrole = !isset($_SESSION['gbrole']) || trim($_SESSION['gbrole']) === "" ? "" : trim($_SESSION['gbrole']);
if ($gbuser === "") {
?>
    <script>
        location.href = "<?php echo $url ?>login.php";
    </script>
<?php
    exit();
}



?>
<div id="global-loader" ng-show="isLoading">
    <div class="whirly-loader"> </div>
</div>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Product Types</h4>
            <h6>Manage Product Types</h6>
        </div>
        <div class="page-btn" >
            <button type="button" class="btn btn-added" ng-click="productModelshowhidefun('flex')"><img src="assets/img/icons/plus.svg" alt="img" class="me-1" >Add Product Type</button>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body" style="    height: calc(100vh - 175px);">
                    <div class="table-responsive" style="height: 100%;">
                        <div ng-show="!isrptloading" id="myGrid" class="ag-theme-balham" style="height:100%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    include_once './producttype_new.php';
?>