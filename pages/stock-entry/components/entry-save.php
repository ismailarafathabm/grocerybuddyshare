<div class="modal" id="saveEntrymodal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Save Invoice</h5>
                <button type="button" class="close" ng-click="saveBillModelshowhide('none')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form name="Invoicesave" id="saveInvoice">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="brandName" class="col-form-label">Bill Total</label>
                                <input type="text" class="form-control" id="sumofinvoice" name="sumofinvoice" ng-model="sumofinvoice" required readonly />
                            </div>
                        </div>
                       
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="brandName" class="col-form-label">Discount % <input type="checkbox" ng-model="byprescal"></label>
                                <input type="text" class="form-control" id="purchaseDiscounttype" name="purchaseDiscounttype" ng-model="newbill.purchaseDiscounttype" required  ng-keyup="CalculateBalance()" ng-keydown="newbillnxtmove($event,'purchaseDiscountval')"/>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="brandName" class="col-form-label">Discount</label>
                                <input type="text" class="form-control" id="purchaseDiscountval" name="purchaseDiscountval" ng-model="newbill.purchaseDiscountval" required ng-keyup="CalculateBalance()" ng-keydown="newbillnxtmove($event,'purchaseothers')"/>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="brandName" class="col-form-label">Sub total</label>
                                <input type="text" class="form-control" id="salessubttoal" name="salessubttoal" ng-model="newbill.salessubttoal" required ng-keyup="CalculateBalance()" readonly/>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="brandName" class="col-form-label">Others</label>
                                <input type="text" class="form-control" id="purchaseothers" name="purchaseothers" ng-model="newbill.purchaseothers" required ng-keyup="CalculateBalance()" ng-keydown="newbillnxtmove($event,'purchaseType')"/>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="brandName" class="col-form-label">Payment Type</label>
                                <select class="form-control" id="purchaseType" name="purchaseType" ng-model="newbill.purchaseType" required ng-change="paymenttypechange()" ng-keydown="newbillnxtmove($event,'purchasePaid')">
                                    <option value="">-Select-</option>
                                    <option value="credit">Credit</option>
                                    <option value="cash">Cash</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="brandName" class="col-form-label">Net Amount</label>
                                <input type="text" class="form-control" id="purcahseNetAmount" name="purcahseNetAmount" ng-model="newbill.purcahseNetAmount" required ng-keyup="CalculateBalance()" readonly/>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="brandName" class="col-form-label">Paid Amount</label>
                                <input type="text" class="form-control" id="purchasePaid" name="purchasePaid" ng-model="newbill.purchasePaid" required ng-keyup="CalculateBalance()" ng-keydown="newbillnxtmove($event,'savebill')"/>
                            </div>
                        </div>
                        <div class="col-lg-12 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="brandName" class="col-form-label">Balance Amount</label>
                                <input type="text" class="form-control" id="purhcaseBalance" name="purhcaseBalance" ng-model="newbill.purhcaseBalance" required readonly />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" ng-click="saveBillModelshowhide('none')">Close</button>
                    <button type="button" ng-click="saveBill()" class="btn btn-primary" ng-disabled="Invoicesave.$invalid">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>