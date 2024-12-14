<style>
    .table tr{
        word-break: break-all;
    }
</style>

<div class="modal bd-example-modal-lg" id="customergetpointsmodal">
    <div class="modal-dialog modal-lg card-body modal-dialog-centered" role="document" style="max-width: 1200px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{customerPhone}} - Has Get Points On Sales Bill</h5>
                <button type="button" class="close" ng-click="customergetpointsmodalshowhide('none')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-12">
                        <div class="form-group">
                            <div class="input-groupicon">
                                <input type="text" class="form-control"  ng-model="srcgetpoinst" />
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-12 col-12" style="max-height: 400px;overflow: auto;">

                        <table class="table">
                            <tr>
                                <th>SNO#</th>
                                <th>Bill No</th>
                                <th>Date</th>
                                <th>Items Qty</th>
                                <th>Total Amount</th>
                                <th>Points</th>

                                
                            </tr>
                            <tr ng-repeat="x in getpointlist | filter:srcgetpoinst">
                                <td>{{$index+1}}</td>
                                <td>
                                    <a href="<?php echo $url?>salesbill.php?salesrefno={{x.salesRefNo}}" target="_blank">
                                        {{x.salesInvoice}}
                                    </a>
                                </td>
                                <td>{{x.salesDates.display}}</td>
                                <td>{{x.sqlqty}}</td>
                                <td>{{(+x.salesInvoiceTotal).toLocaleString(undefined,{maximumFractionDigits:2})}}</td>
                                <td>{{(+x.salesInvoicePointGet).toLocaleString(undefined,{maximumFractionDigits:2})}}</td>
                            </tr>
                            <tr>
                                <td colspan="4" style="text-align: right;"></td>
                                <td>{{(+sumOfInvoices).toLocaleString(undefined,{maximumFractionDigits:2})}}</td>
                                <td>{{(+customerGetPoints).toLocaleString(undefined,{maximumFractionDigits:2})}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>