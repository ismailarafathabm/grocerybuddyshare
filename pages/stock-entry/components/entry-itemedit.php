<div class="modal bd-example-modal-lg" id="editProduct">
    <div class="modal-dialog modal-lg card-body modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Product</h5>
                <button type="button" class="close" ng-click="editProductModalshowHide('none')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form name="productupdate" id="updateproduct" autocomplete="off">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="edit_productBarcode" class="col-form-label">Barcode</label>
                                <input type="text" class="form-control" id="edit_productBarcode" name="productBarcode" ng-model="editproduct.productBarcode" ng-keydown="editproductkeynxt($event,'edit_productSku')" readonly/>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="edit_productSku" class="col-form-label">HSN Number</label>
                                <input type="text" class="form-control" id="edit_productSku" name="productSku" ng-model="editproduct.productSku" ng-keydown="editproductkeynxt($event,'edit_productName')" readonly />
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="edit_productName" class="col-form-label">Product Name</label>
                                <input type="text" class="form-control" id="edit_productName" name="productName" ng-model="editproduct.productName" ng-keydown="editproductkeynxt($event,'edit_productNametamil')" readonly />
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="edit_productNametamil" class="col-form-label">Product Name தமிழில் </label>
                                <input type="text" class="form-control" id="edit_productNametamil" name="productNametamil" ng-model="editproduct.productNametamil" ng-keydown="editproductkeynxt($event,'edit_productQty')" readonly />
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="edit_productQty" class="col-form-label">Qty</label>
                                <input type="text" class="form-control" id="edit_productQty" name="purchaseQty" ng-model="editproduct.purchaseQty" ng-keydown="editproductkeynxt($event,'edit_productPPrice')" required />
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="edit_productPPrice" class="col-form-label">P.Price</label>
                                <input type="text" class="form-control" id="edit_productPPrice" name="purchasePrice" ng-model="editproduct.purchasePrice" ng-keydown="editproductkeynxt($event,'edit_productSPrice')" required />
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="edit_productSPrice" class="col-form-label">S.Price</label>
                                <input type="text" class="form-control" id="edit_productSPrice" name="purchaseSPrice" ng-model="editproduct.purchaseSPrice" ng-keydown="editproductkeynxt($event,'edit_productMrp')" required />
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="edit_productMrp" class="col-form-label">MRP</label>
                                <input type="text" class="form-control" id="edit_productMrp" name="purchaseMrp" ng-model="editproduct.purchaseMrp" ng-keydown="editproductkeynxt($event,'edit_productgst')" required />
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="edit_productgst" class="col-form-label">GST%</label> 
                                <input type="text" class="form-control" id="edit_productgst" name="productgst" ng-model="editproduct.productgst" ng-change="splitgstedit()" ng-keydown="editproductkeynxt($event,'edit_productCgst')" required />
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="edit_productCgst" class="col-form-label">CGST%</label>
                                <input type="text" class="form-control" id="edit_productCgst" name="productCgst" ng-model="editproduct.productCgst" ng-keydown="editproductkeynxt($event,'edit_productSgst')" required />
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="edit_productSgst" class="col-form-label">SGST%</label>
                                <input type="text" class="form-control" id="edit_productSgst" name="productSgst" ng-model="editproduct.productSgst" ng-keydown="editproductkeynxt($event,'edit_isHaveExp')" required />
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="edit_isHaveExp" class="col-form-label">Is Have Expiry</label>
                                <select class="form-control" id="edit_isHaveExp" name="isHaveExp" ng-model="editproduct.isHaveExp" ng-keydown="editproductkeynxt($event,'edit_purchaseManDate')">
                                    <option value="">-select-</option>
                                    <option value="No">No</option>
                                    <option value="Yes">Yes</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="edit_purchaseManDate" class="col-form-label">Packed Date</label>
                                <div style="width: 100%;">
                                <input type="text" class="form-control" id="edit_purchaseManDate" name="purchaseManDate" ng-model="editproduct.purchaseManDate" ng-keydown="editproductkeynxt($event,'edit_purchaseExpdate')" required placeholder="dd-mm-yyyy" ng-hijri-gregorian-datepicker datepicker-config="gregorianDatepickerConfig" selected-date="val_xearrivaldate"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="edit_purchaseExpdate" class="col-form-label">Expiry Date</label>
                                <div style="width: 100%;">
                                <input type="text" class="form-control" id="edit_purchaseExpdate" name="purchaseExpdate" ng-model="editproduct.purchaseExpdate" ng-keydown="editproductkeynxt($event,'updateproductinfo')" required placeholder="dd-mm-yyyy" ng-hijri-gregorian-datepicker datepicker-config="gregorianDatepickerConfigx" selected-date="val_ddcarrivaldate"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" ng-click="editProductModalshowHide('none')">Close</button>
                    <div>
                    <input type="checkbox" ng-model="updateproductpricealso"> Update Also Product Price Informations 
                    <button type="button" ng-click="updateproductInfo()" class="btn btn-primary" ng-disabled="productupdate.$invalid">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>