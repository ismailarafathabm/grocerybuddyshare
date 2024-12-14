<div class="modal" id="newProductTypeModal">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add New Product Type</h5>
                <button type="button" class="close" ng-click="productModelshowhidefun('none')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form name="NewProductType" id="saveNewProductType">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="productTypeName" class="col-form-label">Product Type</label>
                        <input type="text" class="form-control" id="productTypeName" name="productTypeName" ng-model="productTypeName" ng-keydown="producttypesaveKeydown($event)" required/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" ng-click="productModelshowhidefun('none')">Close</button>
                    <button type="button" ng-click="saveNewproducttype_btnclick()" class="btn btn-primary" ng-disabled="NewProductType.$invalid" >Save</button>
                </div>
            </form>
        </div>
    </div>
</div>