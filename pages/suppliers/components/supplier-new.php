<div class="modal" id="newSupplierModal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add New Supplier</h5>
                <button type="button" class="close" ng-click="newSupplierModalhideshow('none')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form name="newsupplierfrm" id="savesupplierfrm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="brandName" class="col-form-label">Code</label>
                                <input type="text" class="form-control" id="supplierCode" name="supplierCode" ng-model="newsupplier.supplierCode" ng-keydown="suppliernxt($event,'supplierName')" required />
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="brandName" class="col-form-label">Name</label>
                                <input type="text" class="form-control" id="supplierName" name="supplierName" ng-model="newsupplier.supplierName" ng-keydown="suppliernxt($event,'supplierPhone')" required />
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="brandName" class="col-form-label">Phone</label>
                                <input type="text" class="form-control" id="supplierPhone" name="supplierPhone" ng-model="newsupplier.supplierPhone" ng-keydown="suppliernxt($event,'supplierAddress')" required />
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="brandName" class="col-form-label">Address</label>
                                <input type="text" class="form-control" id="supplierAddress" name="supplierAddress" ng-model="newsupplier.supplierAddress" ng-keydown="suppliernxt($event,'supplierGstNo')" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="brandName" class="col-form-label">GST NO</label>
                                <input type="text" class="form-control" id="supplierGstNo" name="supplierGstNo" ng-model="newsupplier.supplierGstNo" ng-keydown="suppliernxt($event,'supplierPanNo')" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="brandName" class="col-form-label">PAN NO</label>
                                <input type="text" class="form-control" id="supplierPanNo" name="supplierPanNo" ng-model="newsupplier.supplierPanNo" ng-keydown="suppliernxt($event,'saveaction')" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" ng-click="newSupplierModalhideshow('none')">Close</button>
                    <button type="button" ng-click="saveNewSupplierbtn_click()" class="btn btn-primary" ng-disabled="newsupplierfrm.$invalid">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>