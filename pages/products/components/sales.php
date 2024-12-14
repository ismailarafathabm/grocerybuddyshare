<style>
    .table tr {
        word-break: break-all;
    }
</style>
<div class="modal bd-example-modal-lg" id="productsalesModal">
    <div class="modal-dialog modal-lg card-body modal-dialog-centered" role="document"  style="max-width: 1200px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Sales History</h5>
                <button type="button" class="close" ng-click="productSalesmodalhideshow('none')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-12">
                        <div class="form-group">
                            <div class="input-groupicon">
                                <input type="text" class="form-control" id="src_sales" ng-model="srcsales" placeholder="search...." />
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
                                    <th>Customer Name</th>
                                    <th>Customer Phone</th>
                                    <th>Barcode</th>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="x in salesList | filter : srcsales">
                                    <td>{{$index+1}}</td>

                                    <td>
                                        <a href="<?php echo $url?>salesbill.php?salesrefno={{x.salesRefNo}}" target="_blank">
                                        {{x.salesInvoice}}
                                        </a>
                                    </td>
                                    <td>{{x.salesDates.display}}</td>
                                    <td>{{x.salesCustomerName}}</td>
                                    <td>{{x.salesCustomerPhone}}</td>
                                    <td>{{x.productBarcode}}</td>
                                    <td>{{x.productNametamil}}</td>
                                    <td>{{x.salesQty}}</td>
                                    <td>{{
                                            (+x.salesSPrice).toLocaleString(undefined,{maximumFractionDigits:2})
                                        }}</td>
                                    <td>{{
                                            (+x.salestot).toLocaleString(undefined,{maximumFractionDigits:2})
                                        }}</td>

                                </tr>
                                <tr>
                                    <td colspan="7"></td>
                                    <td>{{
                                            (+totSalesQty).toLocaleString(undefined,{maximumFractionDigits:2})
                                        }}</td>
                                    <td></td>
                                    <td>{{
                                            (+totSalesAmont).toLocaleString(undefined,{maximumFractionDigits:2})
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