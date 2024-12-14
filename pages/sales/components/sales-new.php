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
            <h4>New Sales</h4>
            <h6>Add Sales</h6>
        </div>
        <div class="page-btn" style=" 
        display: flex;
    gap: 3px;
    font-size: 1.5rem;
    background: #ffb2b2;
    padding: 10px 20px;
    border-radius: 10px;
    align-items: center;
    ">
            <div style="
           font-size: 1.5rem;
           ">Total :</div>
            <div> {{
            (+sumofsubtotal).toLocaleString(undefined,{maximumFractionDigits:2})
            }}</div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Bill No</label>
                        <div class="input-groupicon">
                            <input type="text" class="form-control" name="salesInvoice" ng-model="newSales.salesInvoice" readonly />

                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Date </label>
                        <div class="input-groupicon">
                            <input
                                type="text"
                                placeholder="dd-mm-yyyy" ng-hijri-gregorian-datepicker datepicker-config="gregorianDatepickerConfig" selected-date="val_arrivaldate"
                                class="form-control" name="salesDate" ng-model="newSales.salesDate" />
                            <div class="addonset">
                                <img src="assets/img/icons/calendars.svg" alt="img" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="row" style="margin-top: 10px;">
                <div class="col-lg-2 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Barcode</label>
                        <div class="input-groupicon">
                            <Input class="form-control" type="text" id="src_productBarcode" name="productBarcode" ng-model="searchitem.productBarcode" ng-keydown="searchItemBarcode($event)" autocomplete="off">
                            <div class="addonset">
                                <img src="assets/img/icons/scanners.svg" alt="img">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Product Name</label>
                        <Input class="form-control" type="text" id="src_productName" name="productName" ng-model="searchitem.productName" ng-keydown="Showproductlist($event)" readonly>

                    </div>
                </div>
                <div class="col-lg-2 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Expiry Date</label>
                        <select class="form-control" type="text" id="productExpiry" name="productExpiry" ng-change="selectpatch()" ng-model="searchitem.productExpiry" ng-keydown="searchitemaddtolist($event,'search_productQty')">
                            <option value="">-select-</option>
                            <option ng-repeat="x in patchlist" value="{{x.purchaseUniqNo}}">{{ x.purcahseHaveExpi === 'No' ? '-' : x.purchaseExpdatedisp}} - <span>&#8377;</span> {{x.purchaseSPrice}}</option>
                        </select>

                    </div>
                </div>
                <div class="col-lg-1 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Qty</label>
                        <Input class="form-control" id="search_productQty" name="productQty" ng-model="searchitem.productQty" type="text" ng-keydown="searchitemaddtolist($event,'purchasePrice')">

                    </div>
                </div>
                <div class="col-lg-2 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Price</label>
                        <Input readonly class="form-control" id="purchasePrice" name="productPPrice" ng-model="searchitem.productSPrice" type="text" ng-keydown="searchitemaddtolist($event,'savebill')">

                    </div>
                </div>
                <div class="col-lg-1 col-sm-6 col-12">
                    <div class="form-group">
                        <button type="button" ng-click="additemtolist()" class="btn btn-primary" style="margin-top:20px">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Product</th>

                                <th>Qty</th>
                                <th>Unit</th>
                                <th>MRP</th>
                                <th>SRP</th>
                                <th>Sub Tot</th>

                                <th>GST (%)</th>
                                <th>Total Cost (<span>&#8377;</span>)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="x in newsaleslist">
                                <td>{{$index+1}}</td>
                                <td>{{x.productNametamil}}</td>
                                <td>
                                    {{x.salesQty}}
                                </td>
                                <td>{{x.unitType}}</td>
                                <td>{{x.salesMrp}}</td>
                                <td style="width:75px">
                                    {{x.salesSPrice}}
                                </td>

                                <td style="width:100px">
                                    {{x.salestot}}
                                </td>
                                <td style="width:75px">
                                    {{x.productgst}}


                                </td>
                                <td>{{x.salesnetprice}}</td>
                                <td>
                                    <a ng-click="removeSales(x._id,x.salesRefNo)"><img src="assets/img/icons/delete.svg" alt="svg" /></a>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 float-md-right">
                    <div class="total-order">
                        <ul>
                            <li>
                                <h4>Total Items</h4>
                                <h5> {{
                                    (+totItems).toLocaleString(undefined,{maximumFractionDigits:2})
                                }}</h5>
                            </li>
                            <li>
                                <h4>Total Qty</h4>
                                <h5> {{
                                    (+totQty).toLocaleString(undefined,{maximumFractionDigits:2})
                                }}</h5>
                            </li>
                            <li>
                                <h4>Sub</h4>
                                <h5> {{
                                    (+sumofinvoice).toLocaleString(undefined,{maximumFractionDigits:2})
                                }}</h5>
                            </li>
                            <li>
                                <h4>CGST</h4>
                                <h5> {{
                                    (+sumofCgst).toLocaleString(undefined,{maximumFractionDigits:2})
                                }}</h5>
                            </li>
                            <li>
                                <h4>SGST</h4>
                                <h5> {{
                                    (+sumofSgst).toLocaleString(undefined,{maximumFractionDigits:2})
                                }}</h5>
                            </li>
                            <li class="total">
                                <h4>Grand Total</h4>
                                <h5>{{
                                    (+sumofsubtotal).toLocaleString(undefined,{maximumFractionDigits:2})
                                }}</h5>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row" ng-show="newsaleslist.length !== 0">
                <div class="col-lg-12 float-md-right">
                    <button ng-click="holdbill()" type="button" class="btn btn-outline-danger">
                        <i class="fa fa-pause"></i>
                        Hold
                    </button>
                    <button ng-click="saveBillModelshowhide('flex')" type="button" class="btn btn-primary">
                        <i class="fa fa-check"></i>
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include_once './productlist.php';
include_once './../../products/components/product-new.php';
include_once './../../brands/components/brand-new.php';
include_once './../../producttype/components/producttype_new.php';
include_once './../../productunit/components/unit-new.php';
include_once './../../suppliers/components/supplier-new.php';
include_once './save-bill.php';
?>

<toaster-container></toaster-container>