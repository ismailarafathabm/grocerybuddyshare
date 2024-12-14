import API_Services from "../../../src/apiservices.js";
import * as models from "./../models/suppliermodels.js";
export default function SupplierListController($scope, $compile) {
    if (!useraccess.supplierview) {
        location.href = `${url}/index.php`
    }
    $scope.newsupplieraccess = useraccess.suppliernew;
    $scope.isLoading = false;
    $scope.mode = 1;
    const apis = new API_Services();
    const cols = models.supplierListModel($scope,$compile);
    const gridOptions = apis._gridOptions(cols);
    const gridDiv = document.querySelector('#myGrid');
    new agGrid.Grid(gridDiv, gridOptions);
    gridOptions.api.setRowData([]);
    LoadAllSuppliers();
    async function LoadAllSuppliers() {
        if ($scope.isLoading) return;
        $scope.isLoading = true;
        gridOptions.api.setRowData([]);
        const res = await apis.GET('suppliers/index.php');
        if (!res?.isSuccess) {
            $scope.isLoading = false;
            alert(res.msg);
            $scope.$apply();
            return;
        }
        $scope.isLoading = false;
        gridOptions.api.setRowData(res.data);
        $scope.$apply();
        return;
    }

    const newSupplierModal = document.getElementById("newSupplierModal");
    newSupplierModal.style.display = "none";
    $scope.newsupplier = models.newSupplierModel();
    $scope.newSupplierModalhideshow = (_disp) => {

        newSupplierModal.style.display = _disp;
        if (_disp === "flex") {
            
            $scope.mode = 1;
            $scope.newsupplier = models.newSupplierModel();
            document.getElementById("supplierCode").focus();
        }
    }

    $scope.saveNewSupplierbtn_click = async () => supplierSaveFun();
    async function supplierSaveFun() {
        if ($scope.isLoading) return;
        if ($scope.mode === 1) {
            await Supplier_SaveFunction();
        } else {
            await Supplier_UpdateFunction();
        }
    }

    async function Supplier_SaveFunction() {
        const frm = document.getElementById('savesupplierfrm');
        const fd = new FormData(frm);
        $scope.isLoading = true;
        const res = await apis.POST('suppliers/new.php', fd);
        if (!res.isSuccess) {
            $scope.isLoading = false;
            alert(res.msg);
            $scope.$apply();
            return;
        }
        alert("Data Has Saved");
        $scope.isLoading = false;
        gridOptions.api.setRowData(res.data);
        $scope.newsupplier = models.newSupplierModel();
        document.getElementById("supplierCode").focus();
        $scope.$apply();
        return;
    }
    async function Supplier_UpdateFunction() {
        const frm = document.getElementById('savesupplierfrm');
        const fd = new FormData(frm);
        $scope.isLoading = true;
        const res = await apis.POST(`suppliers/update.php?id=${$scope.newsupplier._id}`, fd);
        if (!res.isSuccess) {
            $scope.isLoading = false;
            alert(res.msg);
            $scope.$apply();
            return;
        }
        alert("Data Has Saved");
        $scope.isLoading = false;
        gridOptions.api.setRowData(res.data);
       // $scope.newsupplier = models.newSupplierModel();
        document.getElementById("supplierCode").focus();
        $scope.$apply();
        return;
    }
   

    $scope.getsupplierinfo = async (id) => GetSupplierInfofun(id);
    async function GetSupplierInfofun(id) {
        if ($scope.isLoading) return;
        $scope.isLoading = true;
        const res = await apis.GET(`suppliers/get.php?id=${id}`);
        if (!res?.isSuccess) {
            alert(res.msg);
            $scope.isLoading = false;
            $scope.$apply();
            return;
        }

        $scope.isLoading = false;
        $scope.newsupplier = res.data;
        $scope.mode = 2;
        newSupplierModal.style.display = "flex";
        $scope.$apply();
        return;
    }
    
    $scope.suppliernxt = async ($event, nxt) => {
        if ($event.which === 13) {
            if (nxt === "saveaction") {
                await supplierSaveFun();
            } else {
                const ele = document.getElementById(nxt);
                ele.focus();
                ele.select();
            }
        }
    }

    
}