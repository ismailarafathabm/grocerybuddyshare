<div class="modal" id="editcustomerModal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Update Customer Informations</h5>
                <button type="button" class="close" ng-click="editcustomerModalshowhide('none')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form name="updateuser" id="userupdate" >
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label for="customerName" class="col-form-label">Customer Name</label>
                                <input type="text" class="form-control" id="customerName" name="customerName" ng-model="editcustomer.customerName" ng-keydown="movenxtedit($event,'customerAddress')" required/>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="customerPhone" class="col-form-label">Phone</label>
                                <input type="text" class="form-control" id="customerPhone" name="customerPhone" ng-model="editcustomer.customerPhone" ng-keydown="movenxtedit($event,'customerAddress')" required readonly/>
                            </div>
                        </div>
                        <div class="col-lg-12 col-sm-12 col-12">
                            <div class="form-group">
                                <label for="customerAddress" class="col-form-label">Address</label>
                                <input type="text" class="form-control" id="customerAddress" name="customerAddress" ng-model="editcustomer.customerAddress" ng-keydown="movenxtedit($event,'updatecustomer')" required/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" ng-click="editcustomerModalshowhide('none')">Close</button>
                    <button type="button" ng-click="updateuser_fun()" class="btn btn-primary" ng-disabled="updateuser.$invalid">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>