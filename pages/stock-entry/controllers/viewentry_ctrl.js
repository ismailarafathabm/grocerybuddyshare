import API_Services from "../../../src/apiservices.js";
import { purchaseViewModel } from "../models/purchasemodel.js";
export default function StockEntryViewController($scope, $compile) {
    if (!useraccess.purchaseview) {
        location.href = `${url}/index.php`
    }
    $scope.newentryaccess = useraccess.purchasenew;
    $scope.isLoading = false;
    const apis = new API_Services();
    const cols = purchaseViewModel($scope,$compile);
    const gridOptions = apis._gridOptions(cols);
    const gridDiv = document.querySelector('#myGrid');
    new agGrid.Grid(gridDiv, gridOptions);
    gridOptions.api.setRowData([]);
    const _cyear = new Date().getFullYear().toString();
    $scope.src_year = new Date().getFullYear().toString();

    $scope.src_year = _cyear;
    $scope.getRpt = async () => {
        const xyear = $scope.src_year ?? _cyear;
        await GetPurchase(xyear);
    }
    $scope.wototal = 0;
    $scope.wtotal = 0;
    GetPurchase(_cyear);
    async function GetPurchase(year) {
        if ($scope.isLoading) return;
        $scope.isLoading = true;
        const res = await apis.GET('purchase/index.php?year=' + year);
        if (!res.isSuccess) {
            alert(res.msg);
            $scope.isLoading = false;
            $scope.$apply();
            return;
        }
        $scope.isLoading = false;
        gridOptions.api.setRowData(res.data);
        $scope.wototal = res.data.reduce((a, b) => (+a) + (+b.subtotal), 0);
        $scope.wtotal = res.data.reduce((a, b) => (+a) + (+b.totgst), 0);
        $scope.$apply();
        return;
    }

    $scope.editbill = async (refno) => await EditBillFun(refno);

    async function EditBillFun(refno) {
        if ($scope.isLoading) return;
        $scope.isLoading = true;
        const res = await apis.GET(`purchase/edit.php?invno=${refno}`);
        if (!res.isSuccess) {
            alert(res.msg);
            $scope.isLoading = false;
            $scope.$apply();
            return;
        }
        $scope.isLoading = false;
        $scope.$apply();
        location.href = `${url}index.php#!/stock-entry`;
        return;
    }
}