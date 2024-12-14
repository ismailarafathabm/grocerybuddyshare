import API_Services from './../../../src/apiservices.js';
import * as models from './../models/productmodels.js';
export default function ProductListController($scope, $compile) {
    if (!useraccess.productview) {
        location.href = `${url}/index.php`
    }
    $scope.accesNewProduct = useraccess.productnew;
    
    const apis = new API_Services();
    $scope.isLoading = false;
    const newProductModal = document.getElementById("newProductModal");
    newProductModal.style.display = "none";
    $scope.newProductModalshowhide = (_disp) => {
        newProductModal.style.display = _disp;
        $scope.mode = 1;
        $scope.newproduct = models.productModel();
    }
    $scope.newproduct = models.productModel();
    m === "0" ? console.log($scope.newproduct) : "";
    LoadAllProducts();
    async function LoadAllProducts() {

    }
    $scope.brandlist = [];
    $scope.proeucttypelist = [];
    $scope.unitlist = []

    const getBrandlist = async () => {
        const res = await apis.GET('brands/index.php');
        if (!res?.isSuccess) {
            return [];
        }
        return res.data.filter(x => (+x.status) === 1);;
    }
    const getProductTypelist = async () => {
        const res = await apis.GET('producttype/index.php');
        if (!res.isSuccess) {
            return [];
        }
        return res.data.filter(x => (+x.status) === 1);
    }

    const getUnitsList = async () => {
        const res = await apis.GET('units/index.php');
        if (!res?.isSuccess) {
            return [];
        }
        return res.data.filter(x => (+x.status) === 1);;
    }
    const getAllProductList = async () => {
        const res = await apis.GET('products/index.php');
        if (!res.isSuccess) { return res.data; } return res.data;
    }
    $scope.productList = [];
    const GetAll = async () => {
        const res = await Promise.all([getBrandlist(), getProductTypelist(), getUnitsList(), getAllProductList()]);
        $scope.brandlist = res[0];
        $scope.proeucttypelist = res[1];
        $scope.unitlist = res[2];
        $scope.productList = res[3];
        gridOptions.api.setRowData($scope.productList);
        $scope.$apply();
    }


    //for new brands
    const newBrandModal = document.getElementById("newBrandModal");
    $scope.showNewBrandModal = (_dis) => { newBrandModal.style.display = _dis; }

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
        $scope.brandlist = res.data;
        document.getElementById("brandName").value = "";
        document.getElementById("brandName").focus();
        $scope.$apply();
        return;
    }

    //for new product type

    const newProductTypeModal = document.getElementById("newProductTypeModal");
    newProductTypeModal.style.display = "none";

    $scope.productModelshowhidefun = (_disp) => { newProductTypeModal.style.display = _disp; }
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
        //alert(res.msg);
        $scope.isLoading = false;
        $scope.proeucttypelist = res.data;
        document.getElementById("productTypeName").value = "";
        $scope.$apply();
        return;
    }

    //for units
    const newUnitTypeModal = document.getElementById("newUnitTypeModal");
    newUnitTypeModal.style.display = "none";
    $scope.unittModelshowhidefun = (_disp) => { newUnitTypeModal.style.display = _disp; }

    $scope.unittypesave_btnclick = async () => await saveUnitTypefun();

    async function saveUnitTypefun() {
        if ($scope.isLoading) return;
        const frm = document.getElementById("saveNewUnit");
        const fd = new FormData(frm);
        $scope.isLoading = true;
        const res = await apis.POST('units/new.php', fd);
        if (!res.isSuccess) {
            alert(res.msg);
            $scope.isLoading = false;
            $scope.$apply();
            return;
        }
        // alert(res.msg);
        $scope.unitlist = res.data;
        $scope.isLoading = false;
        const unitType = document.getElementById("unitType");
        unitType.value = "";
        unitType.focus();
        $scope.$apply();
        return;
    }

    $scope.changemrp = () => {
        // console.log($scope.newproduct);
        $scope.newproduct.productMrp = $scope.newproduct.productSPrice;
    }
    //save product

    $scope.saveNewProduct_btnclick = async () => _savenewProduct();
    $scope.mode = 1;
    async function _savenewProduct() {
        if ($scope.isLoading) return;
        if ($scope.mode === 1) {
            await Product_saveAction();
        } else {
            await Product_updateAction();
        }

    }
    async function Product_saveAction() {
        const frm = document.getElementById("newProductSave");
        const fd = new FormData(frm);
        $scope.isLoading = true;
        const res = await apis.POST('products/new.php', fd);
        if (!res.isSuccess) {
            alert(res.msg);
            $scope.isLoading = false;
            $scope.$apply();
            return;
        }

        alert(res.msg);
        //document.getElementById("newProductSave").reset();
        $scope.isLoading = false;
        // console.log(models.EmptyproductModel())
        $scope.newproduct = models.productModel();
        m === "0" ? console.log($scope.newproduct) : "";

        gridOptions.api.setRowData(res.data);
        $scope.$apply();
        document.getElementById("productName").focus();
        return;
    }
    
    async function Product_updateAction() {
        const frm = document.getElementById("newProductSave");
        const fd = new FormData(frm);
        $scope.isLoading = true;
        const res = await apis.POST(`products/update.php?id=${$scope.newproduct._id}`, fd);
        if (!res.isSuccess) {
            alert(res.msg);
            $scope.isLoading = false;
            $scope.$apply();
            return;
        }

        alert("data has updated");
        //document.getElementById("newProductSave").reset();
        $scope.isLoading = false;
        // console.log(models.EmptyproductModel())
       

        gridOptions.api.setRowData(res.data);
        $scope.$apply();
        document.getElementById("productName").focus();
        return;
    }
    //grid actions
    const cols = models.productListModel($scope,$compile);
    const gridOptions = apis._gridOptions(cols);

    const gridDiv = document.querySelector('#myGrid');
    new agGrid.Grid(gridDiv, gridOptions);
    gridOptions.api.setRowData([]);
    GetAll();





    //new code for enter key press

    //product brands

    $scope.savefromkeydown = async ($event) => {
        if ($event.which === 13) {
            await saveNewBrand();
        }
    }

    //product type


    $scope.producttypesaveKeydown = async ($event) => {
        if ($event.which === 13) {
            await saveProductTypefun();
        }
    }
    //unit type

    $scope.unittypesave_keydown = async ($evnet) => {
        if ($evnet.which === 13) {
            await saveUnitTypefun()
        }
    }

    //product model 
    $scope.productNxtfoucs = async ($event, nxt) => {
        if ($event.which === 13) {
            if (nxt === "saveaction") {
                //save 
                await _savenewProduct()
            } else {
                document.getElementById(nxt).focus();
            }
        }
    }

    $scope.gstcal = ($event) => {
        const gst = $scope.newproduct?.productgst ?? 0;
        if (!gst && gst === 0) {
            $scope.newproduct.productCgst = 0;
            $scope.newproduct.productSgst = 0;
            return;
        }
        const dgst = gst / 2;
        $scope.newproduct.productCgst = dgst;
        $scope.newproduct.productSgst = dgst;
        return
    }

    $scope.getProductInfo = async (barcode) => GetBarcodeinfo(barcode);

    async function GetBarcodeinfo(barcode) {
        if ($scope.isLoading) return;
        $scope.isLoading = true;
        const res = await apis.GET(`products/get.php?barcode=${barcode}`);
        if (!res?.isSuccess) {
            alert(res.msg);
            $scope.isLoading = false;
            $scope.$apply();
            return;
        }
        $scope.newproduct = res.data;
        $scope.newproduct.productBrand = res.data.productBrand.toString();
        $scope.newproduct.productType = res.data.productType.toString();
        $scope.newproduct.productUnitType = res.data.productUnitType.toString();
        $scope.isLoading = false;
        $scope.$apply();
        $scope.mode = 2;
        newProductModal.style.display = "flex";
        return;
    }

    $scope.gennewbarcode = async () => await getnewbarcode();

    async function getnewbarcode() {
        if ($scope.isLoading) return;
        $scope.isLoading = true;
        const res = await apis.GET('products/newbarcode.php');
        if (!res.isSuccess) {
            alert(res.msg);
            $scope.isLoading = false;
            $scope.$apply();
            return;
        }
        $scope.newproduct.productBarcode = res.data.productBarcode;
        $scope.itemtype = "new";
        $scope.isLoading = false;
        $scope.$apply();
        document.getElementById("productSku").focus();
        return;
    }

    //delet items
    $scope.deleteitem = async (id) => await deleteitemfun(id);
    async function deleteitemfun(id) {
        if ($scope.isLoading) false;
        const c = confirm("Are You Sure Remove This Product?");
        if (!c) return;
        $scope.isLoading = true;
        const res = await apis.GET(`products/delete.php?id=${id}`);
        if (!res.isSuccess) {
            alert(res.msg);
            $scope.isLoading = false;
            $scope.$apply();
            return;
        }
        alert("Product Has Removed");
        $scope.productList = res.data;
        gridOptions.api.setRowData(res.data);
        $scope.isLoading = false;
        $scope.$apply();
        return;
    }

    $scope.getallpurchase = async (id) => await ItemPurchaseHistory(id);
    $scope.purchaseList = [];
    $scope.totPurchaseAmount = 0;
    $scope.totPurchaseQty = 0

    const useraccessModal = document.getElementById("useraccessModal");
    useraccessModalshowhidefun('none')
    $scope.useraccessmodalshowhide = (disp) => useraccessModalshowhidefun(disp)
    function useraccessModalshowhidefun(disp) {
        useraccessModal.style.display = disp
    }
    
    async function ItemPurchaseHistory(id) {
        if ($scope.isLoading) return;
        $scope.isLoading = true;
        const res = await apis.GET(`products/purchase.php?id=${id}`);
        m === "0" ? console.log(res) : "";
        if (!res.isSuccess) {
            alert(res.msg);
            $scope.isLoading = false;
            $scope.$apply();
            return;
        }
        $scope.purchaseList = res.data;
        $scope.totPurchaseAmount = res.data.reduce((a, b) => (+a) + (+b.purhcaseGsttotval), 0);
        $scope.totPurchaseQty = res.data.reduce((a, b) => (+a) + (+b.purchaseQty), 0);
        $scope.isLoading = false;
        $scope.$apply();
        useraccessModalshowhidefun("flex");
        return;
    }

    $scope.getallsales = async (id) => await ItemSalesHistory(id);
    $scope.salesList = [];
    $scope.totSalesAmont = 0;
    $scope.totSalesQty = 0;
    const productsalesModal = document.getElementById("productsalesModal");
    productsalesModalshowhidefun("none");
    $scope.productSalesmodalhideshow = (disp) => productsalesModalshowhidefun(disp);
    function productsalesModalshowhidefun(disp) {
        productsalesModal.style.display = disp;
    }
    
    async function ItemSalesHistory(id) {
        if ($scope.isLoading) return;
        $scope.isLoading = true;
        const res = await apis.GET(`products/sales.php?id=${id}`);
        m === "0" ? console.log(res) : "";
        if (!res.isSuccess) {
            alert(res.msg);
            $scope.isLoading = false;
            $scope.$apply();
            return;
        }
        $scope.salesList = res.data;
        $scope.totSalesAmont = res.data.reduce((a, b) => (+a) + (+b.salesnetprice), 0);
        $scope.totSalesQty = res.data.reduce((a, b) => (+a) + (+b.salesQty), 0);
        $scope.isLoading = false;
        $scope.$apply();
        productsalesModalshowhidefun("flex");
        return;
    }
}