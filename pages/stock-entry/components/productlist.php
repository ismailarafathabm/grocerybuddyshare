<style>
    .table tr{
        word-break: break-all;
    }
</style>
<div class="modal bd-example-modal-lg" id="productListModal">
    <div class="modal-dialog modal-lg card-body modal-dialog-centered" role="document" style="max-width: 1200px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Product List</h5>
                <button type="button" class="close" ng-click="productlistshowhide('none')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-12">
                        <div class="form-group">
                            <div class="input-groupicon">
                                <input type="text" class="form-control" id="srcproductname_india" ng-model="srcproduct" ng-keydown="selectProduct($event)"/>
                                <div class="addonset">
                                    <img ng-click="newProductModalshowhide('flex')" src="assets/img/icons/plus-circle.svg" alt="img">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-12 col-12" style="max-height: 400px;overflow: auto;">

                        <table class="table">
                            <tr>
                                <th>SNO#</th>
                                <th>Barcode</th>
                                <th>Product Name</th>
                                <th>Type</th>
                                <th>Brand</th>
                                <th>Available</th>
                                <th>Unit</th>
                                <th></th>

                            </tr>
                            <tr ng-repeat="x in productList | filter:srcproduct">
                                <td>{{$index+1}}</td>
                                <td>{{x.productBarcode}}</td>
                                <td>{{x.productName}}</td>
                                <td>{{x.productTypeNamedisp}}</td>
                                <td>{{x.productBrandName}}</td>
                                <td>{{x.balqty}}</td>
                                <td>{{x.productUnitTypeName}}</td>
                                <td>
                                    <button type="buton" ng-click="getproductinfobtn(x.productBarcode)" class="btn btn-primary">Select</button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>