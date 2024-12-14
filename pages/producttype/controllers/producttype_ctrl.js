import API_Services from "../../../src/apiservices.js";
import * as models from './../models/producttypemodel.js';
export default function ProductTypeController($scope) {
    if (!useraccess.productview) {
        location.href = `${url}/index.php`
    }
    $scope.isLoading = false;
    const apis = new API_Services();

    const cols = models.productTypeList();
    const gridOptions = apis._gridOptions(cols);
    const gridDiv = document.querySelector('#myGrid');
    new agGrid.Grid(gridDiv, gridOptions);
    gridOptions.api.setRowData([]);

    getAllProductType();
    async function getAllProductType() {
        if ($scope.isLoading) return;
        gridOptions.api.setRowData([]);
        $scope.isLoading = true;
        const res = await apis.GET("producttype/index.php");
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
    const newProductTypeModal = document.getElementById("newProductTypeModal");
    newProductTypeModal.style.display = "none";
    $scope.productModelshowhidefun = (_display) => { newProductTypeModal.style.display = _display };

    $scope.saveNewproducttype_btnclick = async () => saveProductTypefun();

    async function saveProductTypefun() {
        if ($scope.isLoading) true;
        const frm = document.getElementById("saveNewProductType");
        const fd = new FormData(frm);
        $scope.isLoading = true;
        const res = await apis.POST('producttype/new.php', fd);
        if (!res.isSuccess) {
            $scope.isLoading = false;
            alert(res.msg);
            $scope.$apply();
            return;
        }
        alert(res.msg);
        $scope.isLoading = false;
        gridOptions.api.setRowData(res.data);
        document.getElementById("productTypeName").value = "";
        $scope.$apply();
        return;
    }

    $scope.producttypesaveKeydown = async ($event) => {
        if ($event.which === 13) {
            await saveProductTypefun();
        }
    }
}