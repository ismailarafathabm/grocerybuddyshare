<?php
include_once './../../../db/conf.php';
session_start();
$gbuser = !isset($_SESSION['gbusername']) || trim($_SESSION['gbusername']) === "" ? "" : trim($_SESSION['gbusername']);
$gbtoken = !isset($_SESSION['gbtoken']) || trim($_SESSION['gbtoken']) === "" ? "" : trim($_SESSION['gbtoken']);
$gbrole = !isset($_SESSION['gbrole']) || trim($_SESSION['gbrole']) === "" ? "" : trim($_SESSION['gbrole']);
if ($gbuser === "" ) {
?>
    <script>
        location.href = "<?php echo $url ?>login.php";
    </script>
<?php
    exit();
    
}
if( $gbuser !== "superadmin"){
    ?>
    <script>
        location.href = "<?php echo $url ?>index.php";
    </script>
<?php
    exit();
}
?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Sales Report</h4>

        </div>
        <div class="page-btn" style="
        display: flex;
    flex-direction: row;
    align-items: center; 
    justify-content: start;
    gap: 10px;
        ">
            <div>
                <table class="table table-nowrap mb-0">
                    <thead>
                        <tr>
                            <th>Items</th>
                            <th>QTY</th>
                            <th>Total</th>
                            <th>Profit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{rpttot.totrows}}</td>
                            <td>{{rpttot.totitemsqty}}</td>
                            <td>{{(+rpttot.totitemsubttotal).toLocaleString(undefined,{maximumFractionDigits:2})}}</td>
                            <td>{{(+rpttot.totprofilt).toLocaleString(undefined,{maximumFractionDigits:2})}}</td>

                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="form-group">
                <div style="width: 100%;">
                    <input type="text" ng-model="dashboard.stdate" class="form-control" placeholder="dd-mm-yyyy" ng-hijri-gregorian-datepicker datepicker-config="gregorianDatepickerConfig" selected-date="val_arrivaldate" />
                </div>
            </div>
            <div class="form-grooup">
                <div style="width: 100%;">
                    <input type="text" ng-model="dashboard.enddate" class="form-control" placeholder="dd-mm-yyyy" ng-hijri-gregorian-datepicker datepicker-config="gregorianDatepickerConfig" selected-date="val_xarrivaldate" />
                </div>
            </div>
            <button ng-click="getrpt()" type="button" class="btn btn-added"><img src="assets/img/icons/purchase1.svg" alt="img" class="me-1">GET</button>
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