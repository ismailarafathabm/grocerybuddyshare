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
                <div class="col-lg-2 col-sm-6 col-12">
                    <div class="form-group">
                        <label>LOT No : </label>
                        <div class="input-groupicon">
                            <input type="text" class="form-control" name="purchaseLotCode" ng-modle="newpurcahse.purchaseLotCode"/>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Invoice No</label>
                        <input type="text" name="purchaseInvoiceno" ng-model="newpurchase.purchaseInvoiceno"/>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-6 col-12">
                    <div class="form-group" style="margin-top:22px">
                       <button class="btn btn-primary" type="button" ng-click="AddNewPurchaseItem()">
                        <i class="fa fa-plus"></i>
                        Add Items
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
                                <th>barcode</th>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Unit</th>
                                <th>GST</th>
                                <th>Price</th>
                                <th>G.Price</th>
                                <th>Total Cost </th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="x in newpurchaselist">
                                <td>{{$index+1}}</td>
                                <td>{{x.productBarcode}}</td>
                                <td>{{x.productNametamil}}</td>
                                <td>{{x.purchaseQty}}</td>
                                <td>{{x.unitType}}</td>
                                <td>{{x.gstvals}}</td>
                                <td><span>&#8377;</span> {{
                                    (+x.purchasePrice).toLocaleString(undefined,{maximumFractionDigits:2})
                                }}</td>
                                <td><span>&#8377;</span> {{
                                    (+x.purhcaseGstval).toLocaleString(undefined,{maximumFractionDigits:2})
                                }}</td>
                                <td><span>&#8377;</span>{{
                                    (+x.purhcaseGsttotval).toLocaleString(undefined,{maximumFractionDigits:2})
                                }}</td>
                                <td>
                                    <img ng-click="editcurrentitem(x)" src="assets/img/icons/edit-5.svg" alt="edit_img"/>
                                </td>
                                <td>
                                    <a ng-click="removepurchase(x._id,x.purchaseUniqNo)"  ><img src="assets/img/icons/delete.svg" alt="svg" /></a>
                                </td>
                                <td>
                                    <a target="_blank" href="<?php echo $url?>barcode.php?barcode={{x.productBarcode}}&pname={{x.productNametamil}}&pqty={{x.purchaseQty}}&psp={{x.purchaseSPrice}}&pmrp={{x.purchaseMrp}}&isex={{x.purcahseHaveExpi}}&pdate={{x.purchaseManDate}}&edate={{x.purchaseExpdate}}">
                                        <img src="assets/img/icons/printer.svg" alt="print barcode"/>

                                    </a>
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
                                    (+totalitems).toLocaleString(undefined,{maximumFractionDigits:2})
                                }}</h5>
                            </li>
                            <li>
                                <h4>Total Item's Qty</h4>
                                <h5> {{
                                    (+sumofitems).toLocaleString(undefined,{maximumFractionDigits:2})
                                }}</h5>
                            </li>
                            <li class="total">
                                <h4>Sum of Invoice</h4>
                                <h5>
                                <span>&#8377;</span>
                                {{
                                    (+sumofinvoice).toLocaleString(undefined,{maximumFractionDigits:2})
                                }}</h5>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row" ng-show="newpurchaselist.length !== 0">
                <div class="col-lg-12 float-md-right">
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
include_once './entry-product.php';
include_once './entry-itemedit.php';
include_once './../../brands/components/brand-new.php';
include_once './../../producttype/components/producttype_new.php';
include_once './../../productunit/components/unit-new.php';
include_once './../../suppliers/components/supplier-new.php';
include_once './productlist.php';
include_once './entry-save.php';

?>

<toaster-container></toaster-container>