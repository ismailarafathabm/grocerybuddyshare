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
            <h4>Stock Entry</h4>
            <h6>Manage Stock Entry</h6>
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
           ">Total :</div>
            <div class="btn btn-danger"> {{
            (+wototal).toLocaleString(undefined,{maximumFractionDigits:2})
            }}</div>
         
            <div class="form-group">
                <select id="getByyear" ng-model="src_year" ng-change="getRpt()" class="form-control">
                    <option value="<?php echo date('Y');?>">-select-</option>
                    <?php 
                        include_once './../../../src/option_years.php'
                    ?>
                    <!-- <option value="2024">2024</option>
                    <option value="2025">2025</option>
                    <option value="2026">2026</option>
                    <option value="2027">2027</option>
                    <option value="2028">2028</option>
                    <option value="2029">2029</option>
                    <option value="2030">2030</option> -->
                </select>
            </div>
            
            <a ng-if="newentryaccess" href="#!stock-entry" type="button" class="btn btn-added"><img src="assets/img/icons/purchase1.svg" alt="img" class="me-1">Stock Entry</a>
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