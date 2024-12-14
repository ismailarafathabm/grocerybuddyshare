import API_Services from "../../../src/apiservices.js";
import * as models from "./../models/brandmodels.js"
export default function BrandListController($scope) {
    if (!useraccess.productview) {
        location.href = `${url}/index.php`
    }
    $scope.isLoading = false;
    const apis = new API_Services();

    const cols = models.brandList();
    const gridOptions = apis._gridOptions(cols);
    const gridDiv = document.querySelector('#myGrid');
    new agGrid.Grid(gridDiv, gridOptions);
    gridOptions.api.setRowData([]);
    
    getAllBrands();
    async function getAllBrands() {
        if ($scope.isLoading) return;
        gridOptions.api.setRowData([]);
        $scope.isLoading = true;
        const res = await apis.GET("brands/index.php");
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

    const newBrandModal = document.getElementById("newBrandModal");

    newBrandModal.style.display = "none";

    $scope.showNewBrandModal = (_display) => {
        newBrandModal.style.display = _display;
    }

    $scope.saveNewBrand_btnclick = async () => saveNewBrand();

    async function saveNewBrand() {
        if ($scope.isLoading) return;
        const frm = document.getElementById("NewBrandsave");
        const fd = new FormData(frm);
        $scope.isLoading = true;
        const res = await apis.POST('brands/new.php', fd);
        if (!res?.isSuccess) {
            $scope.isLoading = false;
            alert(res.msg);
            $scope.$apply();
            return;
        }
        $scope.isLoading = false;
        gridOptions.api.setRowData(res.data);
        document.getElementById("brandName").value = "";
        document.getElementById("brandName").focus();
        $scope.$apply();
        return;
    }

    $scope.savefromkeydown = async ($event) => {
        if ($event.which === 13) {
           await saveNewBrand();
        }
    }
    
}