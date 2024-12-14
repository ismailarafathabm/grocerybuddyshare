<div class="modal bd-example-modal-lg" id="newProductModal">
    <div class="modal-dialog modal-lg card-body modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Product</h5>
                <button type="button" class="close" ng-click="newProductModalshowhide('none')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form name="saveNewProduct" id="newProductSave" autocomplete="off">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="productBarcode" class="col-form-label">Barcode</label>
                                <div class="input-groupicon">
                                
                                    <input type="text" class="form-control" id="productBarcode" name="productBarcode" ng-model="newproduct.productBarcode" ng-keydown="barcodescan_keypress($event)" required />
                                    <div class="addonset">
                                        <img src="assets/img/icons/scanners.svg" alt="img" ng-click="gennewbarcode()">
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="productSku" class="col-form-label">HSN Number</label>
                                <input type="text" class="form-control" id="productSku" name="productSku" ng-model="newproduct.productSku" ng-keydown="productNxtfoucs($event,'productName')" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label for="productName" class="col-form-label">Product Name</label>
                                <input type="text" class="form-control" id="productName" name="productName" ng-model="newproduct.productName" ng-keydown="productNxtfoucs($event,'productNametamil')" required />
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label for="productName" class="col-form-label">Product Name தமிழில் </label>
                                <input type="text" class="form-control" id="productNametamil" name="productNametamil" ng-model="newproduct.productNametamil" ng-keydown="productNxtfoucs($event,'productBrand')" required />
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="productBrand" class="col-form-label">Brand</label>
                                <div class="input-groupicon">
                                    <select class="form-control" id="productBrand" name="productBrand" ng-model="newproduct.productBrand" ng-keydown="productNxtfoucs($event,'productType')" required>
                                        <option value="">-select-</option>
                                        <option ng-repeat="x in brandlist  |orderBy : 'brandName'" value="{{x._id}}">{{x.brandName.toUpperCase()}}</option>
                                    </select>
                                    <div class="addonset">
                                        <img ng-click="showNewBrandModal('flex')" src="assets/img/icons/plus-circle.svg" alt="img">
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="productType" class="col-form-label">Type</label>
                                <div class="input-groupicon">
                                    <select class="form-control" id="productType" name="productType" ng-model="newproduct.productType" ng-keydown="productNxtfoucs($event,'productQty')" required>
                                        <option value="">-select-</option>
                                        <option ng-repeat="x in proeucttypelist |orderBy : 'productTypeName'" value="{{x._id}}">{{x.productTypeName.toUpperCase()}}</option>
                                    </select>
                                    <div class="addonset">
                                        <img ng-click="productModelshowhidefun('flex')" src="assets/img/icons/plus-circle.svg" alt="img">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="productQty" class="col-form-label">Qty</label>
                                <input type="text" class="form-control" id="productQty" name="productQty" ng-model="newproduct.productQty" ng-keydown="productNxtfoucs($event,'productUnitType')" required />
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="productUnitType" class="col-form-label">Unit</label>
                                <div class="input-groupicon">
                                    <select class="form-control" id="productUnitType" name="productUnitType" ng-model="newproduct.productUnitType" ng-keydown="productNxtfoucs($event,'productPPrice')" required>
                                        <option value="">-select-</option>
                                        <option ng-repeat="x in unitlist |orderBy : 'unitType'" value="{{x._id}}">{{x.unitType.toUpperCase()}}</option>
                                    </select>
                                    <div class="addonset">
                                        <img ng-click="unittModelshowhidefun('flex')" src="assets/img/icons/plus-circle.svg" alt="img">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="productPPrice" class="col-form-label">P.Price</label>
                                <input type="text" class="form-control" id="productPPrice" name="productPPrice" ng-model="newproduct.productPPrice" ng-keydown="productNxtfoucs($event,'productSPrice')" required />
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="productSPrice" class="col-form-label">S.Price</label>
                                <input type="text" class="form-control" id="productSPrice" name="productSPrice" ng-model="newproduct.productSPrice" ng-keydown="productNxtfoucs($event,'productMrp')" ng-change="changemrp()" required />
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="productMrp" class="col-form-label">MRP.Price</label>
                                <input type="text" class="form-control" id="productMrp" name="productMrp" ng-model="newproduct.productMrp" ng-keydown="productNxtfoucs($event,'productgst')" required />
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="productCgst" class="col-form-label">GST %</label>
                                <input type="text" class="form-control" id="productgst" name="productgst" ng-model="newproduct.productgst" ng-keyup="gstcal($event)" ng-keydown="productNxtfoucs($event,'productCgst')" required />
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="productCgst" class="col-form-label">CGST %</label>
                                <input type="text" class="form-control" id="productCgst" name="productCgst" ng-model="newproduct.productCgst" ng-keydown="productNxtfoucs($event,'productSgst')" required />
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="productSgst" class="col-form-label">SST %</label>
                                <input type="text" class="form-control" id="productSgst" name="productSgst" ng-model="newproduct.productSgst" ng-keydown="productNxtfoucs($event,'productLocationRack')" required />
                            </div>
                        </div>




                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="productLocationRack" class="col-form-label">Location / Rack</label>
                                <input type="text" class="form-control" id="productLocationRack" name="productLocationRack" ng-keydown="productNxtfoucs($event,'isHaveExp')" ng-model="newproduct.productLocationRack" />
                            </div>
                        </div>

                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="isHaveExp" class="col-form-label">Is Have Expiry</label>
                                <select class="form-control" id="isHaveExp" name="isHaveExp" ng-model="newproduct.isHaveExp" ng-keydown="productNxtfoucs($event,'purchaseManDate')">
                                    <option value="">-select-</option>
                                    <option value="No">No</option>
                                    <option value="Yes">Yes</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="purchaseManDate" class="col-form-label">Packed Date</label>
                                <div style="width: 100%;">
                                    <input type="text" class="form-control" id="purchaseManDate" name="purchaseManDate" ng-model="newproduct.purchaseManDate" ng-keydown="productNxtfoucs($event,'purchaseExpdate')" placeholder="dd-mm-yyyy" ng-hijri-gregorian-datepicker datepicker-config="gregorianDatepickerConfig" selected-date="val_pdate" />
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group" style="width: 100%;">
                                <label for="purchaseExpdate" class="col-form-label">Expiry Date</label>
                                <div style="width: 100%;">
                                    <input type="text" class="form-control" id="purchaseExpdate" name="purchaseExpdate" ng-model="newproduct.purchaseExpdate" ng-keydown="productNxtfoucs($event,'productMinQty')" placeholder="dd-mm-yyyy" ng-hijri-gregorian-datepicker datepicker-config="gregorianDatepickerConfigx" selected-date="val_edate" />
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="productMinQty" class="col-form-label">Mini Qty</label>
                                <input type="text" class="form-control" id="productMinQty" name="productMinQty" ng-model="newproduct.productMinQty" ng-keydown="productNxtfoucs($event,'savenewpurhcase')" required />
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" ng-click="newProductModalshowhide('none')">Close</button>
                    
                    <button type="button" ng-click="saveNewProduct_btnclick()" class="btn btn-primary" ng-disabled="saveNewProduct.$invalid">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>