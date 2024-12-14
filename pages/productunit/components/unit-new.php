<div class="modal" id="newUnitTypeModal">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add New Unit</h5>
                <button type="button" class="close" ng-click="unittModelshowhidefun('none')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form name="newUnit" id="saveNewUnit">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="unitType" class="col-form-label"> Unit</label>
                        <input type="text" class="form-control" id="unitType" name="unitType" ng-model="unitTypes" ng-keydown="unittypesave_keydown($event)" required/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" ng-click="unittModelshowhidefun('none')">Close</button>
                    <button type="button" ng-click="unittypesave_btnclick()" class="btn btn-primary" ng-disabled="newUnit.$invalid" >Save</button>
                </div>
            </form>
        </div>
    </div>
</div>