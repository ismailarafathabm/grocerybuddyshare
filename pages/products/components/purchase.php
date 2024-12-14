<style>
    .table tr {
        word-break: break-all;
    }
</style>
<div class="modal bd-example-modal-lg" id="useraccessModal">
    <div class="modal-dialog modal-lg card-body modal-dialog-centered" role="document" style="max-width: 1200px;" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Purchase Histroy</h5>
                <button type="button" class="close" ng-click="useraccessmodalshowhide('none')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-12">
                        <div class="form-group">
                            <div class="input-groupicon">
                                <input type="text" class="form-control" id="src_purcahse" ng-model="srcpurcahse" placeholder="search...." />
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-12 col-12" style="max-height: 400px;overflow: auto;">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>SNO#</th>
                                    <th>INV#</th>
                                    <th>Date</th>
                                    <th>Supplier</th>
                                    <th>Barcode</th>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="x in purchaseList | filter : srcpurcahse">
                                    <td>{{$index+1}}</td>
                                    <td>{{x.purchaseInvoiceno}}</td>
                                    <td>{{x.purchaseDates.display}}</td>
                                    <td>{{x.supplierName}}</td>
                                    <td>{{x.productBarcode}}</td>
                                    <td>{{x.productNametamil}}</td>
                                    <td>{{x.purchaseQty}}</td>
                                    <td>{{
                                            (+x.purhcaseGstval).toLocaleString(undefined,{maximumFractionDigits:2})
                                        }}</td>
                                    <td>{{
                                            (+x.purhcaseGsttotval).toLocaleString(undefined,{maximumFractionDigits:2})
                                        }}</td>

                                </tr>
                                <tr>
                                    <td colspan="6"></td>
                                    <td>{{
                                            (+totPurchaseQty).toLocaleString(undefined,{maximumFractionDigits:2})
                                        }}</td>
                                    <td></td>
                                    <td>{{
                                            (+totPurchaseAmount).toLocaleString(undefined,{maximumFractionDigits:2})
                                        }}</td>
                                </tr>
                            </tbody>

                        </table>
                    </div>

                </div>
            </div>


        </div>
    </div>
</div>