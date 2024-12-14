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
            <h4>Purchase Add</h4>
            <h6>Add Purchase</h6>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Supplier Name</label>
                        <div class="input-groupicon">
                            <select class="form-control" ng-model="newpurchase.purchaseSupplier"  ng-change="getuniqcode()">
                                <option value="">-select-</option>
                                <option ng-repeat="x in supplierlist" value="{{x._id}}">{{x.supplierName}}</option>
                            </select>
                            <div class="addonset">
                                <img ng-click="newSupplierModalhideshow('flex')" src="assets/img/icons/plus-circle.svg" alt="img">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Purchase Date </label>
                        <div class="input-groupicon">
                            <input
                                type="text"
                                placeholder="dd-mm-yyyy" ng-hijri-gregorian-datepicker datepicker-config="gregorianDatepickerConfig" selected-date="val_arrivaldate"
                                class="form-control" ng-model="newpurchase.purchaseDate"/>
                            <div class="addonset">
                                <img src="assets/img/icons/calendars.svg" alt="img" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>LOT No : </label>
                        <div class="input-groupicon">
                            <input type="text" class="form-control" name="purchaseLotCode" ng-modle="newpurcahse.purchaseLotCode"/>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Invoice No</label>
                        <input type="text" name="purchaseInvoiceno" ng-model="newpurchase.purchaseInvoiceno"/>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 10px;">
                <div class="col-lg-2 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Barcode</label>
                        <div class="input-groupicon">
                            <Input class="form-control" type="text" id="src_productBarcode" name="productBarcode" ng-model="searchitem.productBarcode" ng-keydown="searchItemBarcode($event)">
                            <div class="addonset">
                                <img src="assets/img/icons/scanners.svg" alt="img">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Product Name</label>
                        <Input class="form-control" type="text" id="src_productName" name="productName" ng-model="searchitem.productName"  ng-keydown="Showproductlist($event)" readonly>
                        
                    </div>
                </div>
                <div class="col-lg-2 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Qty</label>
                        <Input class="form-control" id="search_productQty" name="productQty" ng-model="searchitem.productQty" type="text" ng-keydown="searchitemaddtolist($event,'purchasePrice','src_productName')">

                    </div>
                </div>
                <div class="col-lg-2 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Price</label>
                        <Input class="form-control" id="purchasePrice"  name="productPPrice" ng-model="searchitem.productPPrice" type="text" ng-keydown="searchitemaddtolist($event,'','search_productQty')" >

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
                                <th>Price($)</th>

                                <th>Total Cost ($)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="x in newpurchaselist">
                                <td>{{$index+1}}</td>
                                <td>{{x.productName}}</td>
                                <td>{{x.purchaseQty}}</td>
                                <td>{{x.unitType}}</td>
                                <td>{{x.purchasePrice}}</td>
                                
                                <td>{{x.purchaseSubtot}}</td>
                                <td>
                                    <a ng-click="removepurchase(x._id,x.purchaseUniqNo)"  ><img src="assets/img/icons/delete.svg" alt="svg" /></a>
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
                                <h4>Total</h4>
                                <h5> {{
                                    (+sumofinvoice).toLocaleString(undefined,{maximumSignificantDigits:2})
                                }}</h5>
                            </li>
                            <li class="total">
                                <h4>Grand Total</h4>
                                <h5>{{
                                    (+sumofinvoice).toLocaleString(undefined,{maximumSignificantDigits:2})
                                }}</h5>
                            </li>
                        </ul>
                    </div>
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
?>

<toaster-container></toaster-container>