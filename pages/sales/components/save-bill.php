<div class="modal" id="saveBillModel">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Save Bill</h5>
                <button type="button" class="close" ng-click="saveBillModelshowhide('none')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form name="savenewbill" id="newbillsave" >
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="brandName" class="col-form-label">Customer Name</label>
                                <input type="text" class="form-control" id="salesCustomerName" name="salesCustomerName" ng-model="newbill.salesCustomerName" ng-keydown="movenxt($event,'salesCustomerPhone')" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="brandName" class="col-form-label">Phone</label>
                                <input type="text" class="form-control" id="salesCustomerPhone" name="salesCustomerPhone" ng-model="newbill.salesCustomerPhone" ng-keydown="movenxt($event,'salesCustomerAddress')" ng-blur="getcustomerinfo()"/>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="brandName" class="col-form-label">Adress</label>
                                <input type="text" class="form-control" id="salesCustomerAddress" name="salesCustomerAddress" ng-model="newbill.salesCustomerAddress" ng-keydown="movenxt($event,'salesOthers')"/>
                            </div>
                        </div>
                        
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="brandName" class="col-form-label">Bill Total</label>
                                <input type="text" class="form-control" id="salessubtot" name="salessubtot" ng-model="newbill.salessubtot" required readonly ng-keydown="movenxt($event,'salesOthers')"/>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="customepoints" class="col-form-label">Customer Points</label>
                                <input type="text" class="form-control" id="customepoints" name="customepoints"  ng-model="newbill.customepoints"  readonly ng-keydown="movenxt($event,'salesOthers')"/>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="salesOthers" class="col-form-label">Discount</label>
                                <input type="text" class="form-control" id="salesOthers" name="salesOthers" ng-model="newbill.salesOthers" ng-keyup="CalculateBalance()" ng-keydown="movenxt($event,'salesPayentType')"/>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="salesOthers" class="col-form-label">Payble</label>
                                <input type="text" class="form-control" id="payable" name="payable" readonly ng-model="newbill.payable" required ng-keydown="movenxt($event,'salesPayentType')"/>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="brandName" class="col-form-label">Payment Type</label>
                                <select class="form-control" id="salesPayentType" name="salesPayentType" ng-model="newbill.salesPayentType" required ng-change="paymenttypechange()" ng-keydown="movenxt($event,'salesCustomerPaid')">
                                    <option value="">-Select-</option>
                                    <option value="cash">Cash</option>
                                    <!-- <option value="point">Cash</option>
                                    <option value="upi">UPI</option>
                                    <option value="card">Card</option>
                                    <option value="net banking">Net Banking</option> -->
                                </select>

                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="brandName" class="col-form-label">Paid Amount</label>
                                <input type="text" class="form-control" id="salesCustomerPaid" name="salesCustomerPaid" ng-model="newbill.salesCustomerPaid" required ng-keyup="CalculateBalance()" ng-keydown="movenxt($event,'saveinvoice')"/>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="brandName" class="col-form-label">Balance Amount</label>
                                <input type="text" class="form-control" id="salesCustomerBalance" name="salesCustomerBalance" ng-model="newbill.salesCustomerBalance" required readonly />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" ng-click="saveBillModelshowhide('none')">Close</button>
                    <button type="button" ng-click="newbillsave_submit()" class="btn btn-primary" ng-disabled="savenewbill.$invalid">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>