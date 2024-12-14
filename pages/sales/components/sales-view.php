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
            <h4>Sales View</h4>
            <h6>Manage Sales View</h6>
        </div>
        <div class="page-btn" style="
        display: flex;
    flex-direction: row;
    /* align-items: center; */
    justify-content: start;
    gap: 10px;
        ">
        <div style="
           font-size: 1.5rem;
           ">w/o GST :</div>
            <div class="btn btn-danger"> {{
            (+wototal).toLocaleString(undefined,{maximumFractionDigits:2})
            }}</div>
         <div style="
           font-size: 1.5rem;
           ">W / GST :</div>
            <div class="btn btn-success"> {{
            (+wtotal).toLocaleString(undefined,{maximumFractionDigits:2})
            }}</div>
            <div class="form-group">
                <select id="getByyear" ng-model="src_year" ng-change="getRpt()" class="form-control">
                    <option value="<?php echo date('Y'); ?>">-select-</option>
                    <?php 
                        include_once './../../../src/option_years.php'
                    ?>
                </select>
            </div>
            <a href="#!stock-entry" type="button" class="btn btn-added"><img src="assets/img/icons/purchase1.svg" alt="img" class="me-1">Stock Entry</a>
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