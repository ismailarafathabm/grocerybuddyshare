import API_Services from "../../../src/apiservices.js";
import { salesViewModel } from "../models/salesmodels.js";
export default function SalesViewController($scope, $compile) {
    if (!useraccess.salesview) {
        location.href = `${url}/index.php`
    }
    $scope.isLoading = false;
    $scope.src_year = new Date().getFullYear().toString();
    const apis = new API_Services();
    const cols = salesViewModel($scope,$compile);
    const gridOptions = apis._gridOptions(cols);
    gridOptions.onCellClicked = onEditClick;
    const gridDiv = document.querySelector('#myGrid');
    new agGrid.Grid(gridDiv, gridOptions);
    gridOptions.api.setRowData([]);
    $scope.wototal = 0;
    $scope.wtotal = 0;
    LoadSales(new Date().getFullYear());
    $scope.getRpt = async () => {
        const xyear = $scope.src_year ?? _cyear;
        await LoadSales(xyear);
    }
    async function LoadSales(_year) {
        if ($scope.isLoading) return;
        gridOptions.api.setRowData([]);
        const res = await apis.GET(`sales/salesview.php?year=${_year}`);
        
        if (!res.isSuccess) {
            alert(res.msg);
            $scope.isLoading = false;
            $scope.$apply();
            return;
        }
        gridOptions.api.setRowData(res.data);
        $scope.wototal = res.data.reduce((a, b) => (+a) + (+b.stot), 0);;
        $scope.wtotal = res.data.reduce((a, b) => (+a) + (+b.subtotval), 0);;
        $scope.isLoading = false;
        $scope.$apply();
        return;

    }

    $scope.editbill = async (refno,cusphone) => {
        if ($scope.isLoading) return;
        $scope.isLoading = true;
        const res = await apis.GET(`sales/editbill.php?salesRefNo=${refno}&customerPhone=${cusphone}`);
        if (!res.isSuccess) {
            alert(res.msg);
            $scope.isLoading = false;
            $scope.$apply();
            return;
        }
        $scope.isLoading = false;
        $scope.$apply();
        location.href = `${url}index.php#!/sales-entry`;
        return;
    }

    function onEditClick(event) {
        const { data, colDef } = event;
        if (colDef.field === "salesInvoice") {
            location.href = `${url}/index.php#!/salesbill-view/${data.salesRefNo}`;
        }
    }
}