<div class="modal" id="newBrandModal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add New Brand</h5>
                <button type="button" class="close" ng-click="showNewBrandModal('none')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form name="saveNewBrand" id="NewBrandsave">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="brandName" class="col-form-label">Brand Name</label>
                        <input type="text" class="form-control" id="brandName" name="brandName" ng-model="brandName" ng-keydown="savefromkeydown($event)" required/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" ng-click="showNewBrandModal('none')">Close</button>
                    <button type="button" ng-click="saveNewBrand_btnclick()" class="btn btn-primary" ng-disabled="saveNewBrand.$invalid" >Save</button>
                </div>
            </form>
        </div>
    </div>
</div>