<div class="modal bd-example-modal-lg" id="newProductModal">
    <div class="modal-dialog modal-lg card-body modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add New Product</h5>
                <button type="button" class="close" ng-click="newProductModalshowhide('none')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form name="saveNewProduct" id="newProductSave" ng-submit="newProductSave_submit()">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="productBarcode" class="col-form-label">Barcode</label>
                                <div class="input-groupicon">
                                    <input type="text" class="form-control" id="productBarcode" name="productBarcode" ng-model="newproduct.productBarcode" ng-keydown="productNxtfoucs($event,'productSku')" required />
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
                                    <select class="form-control" id="productType" name="productType" ng-model="newproduct.productType" ng-keydown="productNxtfoucs($event,'productUnitType')" required>
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
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="productPPrice" class="col-form-label">P.Price</label>
                                <input type="text" class="form-control" id="productPPrice" name="productPPrice" ng-model="newproduct.productPPrice" ng-keydown="productNxtfoucs($event,'productSPrice')" required />
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="productSPrice" class="col-form-label">S.Price</label>
                                <input type="text" class="form-control" id="productSPrice" name="productSPrice" ng-model="newproduct.productSPrice" ng-keydown="productNxtfoucs($event,'productMrp')" ng-change="changemrp()" required />
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="productMrp" class="col-form-label">MRP.Price</label>
                                <input type="text" class="form-control" id="productMrp" name="productMrp" ng-model="newproduct.productMrp" ng-keydown="productNxtfoucs($event,'productgst')" required />
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="productCgst" class="col-form-label">GST %</label>
                                <input type="text" class="form-control" id="productgst" name="productgst" ng-model="newproduct.productgst" ng-keyup="gstcal($event)" ng-keydown="productNxtfoucs($event,'productDiscount')" required />
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="productCgst" class="col-form-label">CGST %</label>
                                <input type="text" class="form-control" id="productCgst" name="productCgst" ng-model="newproduct.productCgst" required />
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="productSgst" class="col-form-label">SST %</label>
                                <input type="text" class="form-control" id="productSgst" name="productSgst" ng-model="newproduct.productSgst" required />
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="productDiscount" class="col-form-label">Discount%</label>
                                <input type="text" class="form-control" id="productDiscount" name="productDiscount" ng-keydown="productNxtfoucs($event,'productMinQty')" ng-model="newproduct.productDiscount" />
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12" style="display: none;">
                            <div class="form-group">
                                <label for="productOpeningstock" class="col-form-label">Opening Stock</label>
                                <input type="text" class="form-control" id="productOpeningstock" name="productOpeningstock" ng-keydown="productNxtfoucs($event,'productLocationRack')" ng-model="newproduct.productOpeningstock" />
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="productMinQty" class="col-form-label">Mini Qty</label>
                                <input type="text" class="form-control" id="productMinQty" name="productMinQty" ng-keydown="productNxtfoucs($event,'productLocationRack')" ng-model="newproduct.productMinQty" />
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="productLocationRack" class="col-form-label">Location / Rack</label>
                                <input type="text" class="form-control" id="productLocationRack" name="productLocationRack" ng-keydown="productNxtfoucs($event,'saveaction')" ng-model="newproduct.productLocationRack" />
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" ng-click="newProductModalshowhide('none')">Close</button>
                    <button type="button" ng-click="saveNewProduct_btnclick()" class="btn btn-primary" ng-disabled="saveNewProduct.$invalid">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>