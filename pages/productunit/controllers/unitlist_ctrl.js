import API_Services from "../../../src/apiservices.js";
import * as models from "../models/unitsmodels.js";
export default function ProductUnitsContollers($scope) {
    if (!useraccess.productview) {
        location.href = `${url}/index.php`
    }
    $scope.isLoading = false;
    const apis = new API_Services();

    const cols = models.unitlists();
    const gridOptions = apis._gridOptions(cols);
    const gridDiv = document.querySelector('#myGrid');
    new agGrid.Grid(gridDiv, gridOptions);
    gridOptions.api.setRowData([]);

    getAllUnits();
    async function getAllUnits() {
        if ($scope.isLoading) return;
        gridOptions.api.setRowData([]);
        $scope.isLoading = true;
        const res = await apis.GET("units/index.php");
        if (!res.isSuccess) {
            $scope.isLoading = false;
            alert(res.msg);
            $scope.$apply();
            return;
        }
        gridOptions.api.setRowData(res.data);
        $scope.isLoading = false;
        $scope.$apply();
        return;
    }

    const newUnitTypeModal = document.getElementById("newUnitTypeModal");
    $scope.unittModelshowhidefun = (_disp) => { newUnitTypeModal.style.display = _disp }

    $scope.unittypesave_btnclick = async () => await saveUnitTypefun();

    async function saveUnitTypefun() {
        if ($scope.isLoading) return;
        const frm = document.getElementById("saveNewUnit");
        const fd = new FormData(frm);
        $scope.isLoading = true;
        const res = await apis.POST('units/new.php',fd);
        if (!res.isSuccess) {
            alert(res.msg);
            $scope.isLoading = false;
            $scope.$apply();
            return;
        }
        alert(res.msg);
        gridOptions.api.setRowData(res.data);
        $scope.isLoading = false;
        const unitType = document.getElementById("unitType");
        unitType.value = "";
        unitType.focus();
        $scope.$apply();
        return;

    }

    $scope.unittypesave_keydown = async ($evnet) => {
        if ($evnet.which === 13) {
            await saveUnitTypefun()
        }
    }

    

}   