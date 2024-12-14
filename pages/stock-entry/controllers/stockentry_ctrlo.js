'use strict';
import API_Services from '../../../src/apiservices.js'; 
import { productModel } from '../../products/models/productmodels.js';
import { newPurchaseModel } from '../models/purchasemodel.js';
import { newSupplierModel } from '../../suppliers/models/suppliermodels.js';
export default function StockEntryController($scope) {
    $scope.gregorianDatepickerConfig = {
        allowFuture: true,
        dateFormat: 'DD-MM-YYYY',
        defaultDisplay: 'gregorian',
        allowFuture: false,
    };
    moment.locale('en');
    $scope.locale = moment.locale();
    $scope.switchLocale = function (value) {
        moment.locale(value);
        $scope.locale = moment.locale();
    };
    $scope.isLoading = false;
    $scope.supplierlist = [];
    $scope.productBrandList = [];
    $scope.productList = [];
    $scope.productTypeList = [];
    $scope.productUnitList = [];
    $scope.newproduct = productModel(); 
    $scope.newpurchase = newPurchaseModel();
    $scope.barcodeproduct = {};
    $scope.newpurchaselist = [];
    $scope.uniqcode = "";
    $scope.sumofinvoice = 0;

    $scope.brandlist = [];
    $scope.proeucttypelist = [];
    $scope.unitlist = []
    //need sales table
    
    const apis = new API_Services();
    console.log(apis.getCurrentdate());
    $scope.newpurchase.purchaseDate = apis.getCurrentdate();

    const getAllBrandList = async () => {
        const res = await apis.GET('brands/index.php');
        if (!res?.isSuccess) { return []; }
        return res.data;
    }
    const getAllProductTypeList = async () => {
        const res = await apis.GET('producttype/index.php');
        if (!res?.isSuccess) { return []; } return res.data;
    }
    const getAllUnitList = async () => { 
        const res = await apis.GET('units/index.php');
        if (!res?.isSuccess) { return []; } return res.data;
    };
    const getAllProductList = async () => {
        const res = await apis.GET('products/index.php');
        if(!res.isSuccess){ return res.data; } return res.data; 
    }
    const getAllSupplierList = async () => {
        const res = await apis.GET('suppliers/index.php');
        if(!res.isSuccess){ return res.data; } return res.data; 
    }

    const initLoadDatas = async () => {
        const res = await Promise.all([getAllBrandList(), getAllProductTypeList(), getAllUnitList(), getAllProductList(), getAllSupplierList()]);
        console.log(res);
        $scope.productBrandList = res[0];
        $scope.brandlist = res[0];
        $scope.productTypeList = res[1];
        $scope.proeucttypelist = res[1];
        $scope.productUnitList = res[2];
        $scope.unitlist = res[2];
        $scope.productList = res[3];
        $scope.supplierlist = res[4];
        $scope.$apply();
        return;
    }

    initLoadDatas();
    
    //get purchase uniq code 
    $scope.getuniqcode = () => {
        console.log($scope.newpurchase.purchaseSupplier);
        GetUniqCode($scope.newpurchase.purchaseSupplier);
    } 
    async function GetUniqCode(id) {
        if ($scope.uniqcode !== "") return;
        if ($scope.isLoading) return;
        $scope.isLoading = true;
        const res = await apis.GET(`purchase/getuniqid.php?id=${id}`);
        if (!res.isSuccess) {
            $scope.isLoading = false;
            alert(res.msg);
            $scope.$apply();
            return;
        }
        $scope.uniqcode = res.data.pcode;
        $scope.isLoading = false;
        $scope.$apply();
        return;
    }
    const productListModal = document.getElementById("productListModal");
    productListModal.style.display = "none";
    $scope.Showproductlist = () => {
        productListModal.style.display = "flex";
        document.getElementById("srcproductname_india").vlaue = "";
        document.getElementById("srcproductname_india").focus();
    }
    $scope.productlistshowhide = (_disp) => {
        productListModal.style.display = _disp;
        
    }
    $scope.getproductinfobtn = (_barcode) => {
        productListModal.style.display = "none";
        ScanBarcode(_barcode);
    }
    async function ScanBarcode(_barcode) {
        if ($scope.isLoading) return;
        $scope.isLoading = true;
        const res = await apis.GET('products/get.php?barcode=' + _barcode);
        if (!res.isSuccess) {
            alert(res.msg);
            $scope.isLoading = false;
            $scope.$apply();
            return;
        }
        $scope.barcodeproduct = res.data;
        $scope.searchitem = res.data;
        document.getElementById("search_productQty").focus();
        $scope.isLoading = false;
        $scope.$apply();
        return;

    }

    $scope.searchitemaddtolist = async ($event,nxt,prv) => {
        console.log($event.which);
        if ($event.which === 13) {
            if (nxt === "") {
                await SaveNewPurchase();
                return;
            }
            document.getElementById(nxt).focus();
            document.getElementById(nxt).select();

        }
        if ($event.which === 39) {
            if (nxt !== "") {
                document.getElementById(nxt).focus();
                document.getElementById(nxt).select();
            }
        }
        if ($event.which === 37) {
            if (prv !== "") {
                document.getElementById(prv).focus();
            }
        }
        
    }

    $scope.additemtolist = async () => SaveNewPurchase();

    async function SaveNewPurchase() {
        console.log($scope.isLoading);
        if ($scope.isLoading) return;
        ///check validate
        const purchaseSupplier = $scope?.newpurchase?.purchaseSupplier ?? "";
        if (!purchaseSupplier || purchaseSupplier.trim() === "") {
            toastr.error('Select Supplier', 'Input Missing');
            return;
        }

        const purchaseDate = $scope?.newpurchase?.purchaseDate ?? "";
        if (!purchaseDate || purchaseDate.trim() === "") {
            toastr.error('Select Purchase Date', "Input Missing");
            return;
        }

        const purchaseInvoiceno = $scope?.newpurchase?.purchaseInvoiceno ?? "";
        if (!purchaseInvoiceno || purchaseInvoiceno.trim() === "") {
            toastr.error("Enter Invoice Number", "Input Missing");
            return;
        }

        const purchaseLotCode = $scope?.newpurchase?.purchaseLotCode ?? "";
        

        if (!$scope.uniqcode || $scope.uniqcode === "") {
            toastr.error("Purchase Code Missing - Please Re-load this Page", "Input Missing");
            return
        }
        const productId = $scope?.searchitem?._id ?? "";
        if (!productId || productId === "") {
            toastr.error("Enter Product", "Input Missing");
        }
        const productQty = $scope?.searchitem?.productQty ?? "";
        if (!productQty || productQty === 0)
        {
            toastr.error("Enter Qty", "Input Missing");
            return;
        }
        const productPPrice = $scope?.searchitem?.productPPrice ?? '';
        if (!productPPrice && productPPrice === "") {
            toastr.error("Enter Price", "Input Missing");
            return;
        }
        const fd = new FormData();
        fd.append("purchaseUniqNo", $scope.uniqcode);
        fd.append("purchaseDate", purchaseDate);
        fd.append("purchaseSupplier", purchaseSupplier);
        fd.append("purchaseLotCode", purchaseLotCode);
        fd.append("purchaseProduct", $scope?.searchitem?._id ?? "");
        fd.append("purchaseQty", productQty);
        fd.append("purchaseQty", productQty);
        fd.append("purchasePrice", productPPrice);
        fd.append("purchaseInvoiceno", purchaseInvoiceno);
        
        $scope.isLoading = true;
        const res = await apis.POST('purchase/new.php', fd);
        if (!res?.isSuccess) {
            $scope.isLoading = false;
            alert(res.msg);
            $scope.$apply();
            return;
        }

        $scope.isLoading = false;
        $scope.newpurchaselist = res.data;
        const tot = res.data.reduce((a, b) => { return (+a) + (+b.purchaseSubtot); }, 0);
        console.log(tot);
        $scope.sumofinvoice = tot;
        $scope.searchitem = {
            productBarcode: "", 
            productName: "",
            productQty: "",
            productPPrice : "",
        }
        document.getElementById("src_productBarcode").focus();
        $scope.$apply();
        return;
      
    }

    //new suppllier modeal

    $scope.newsupplier = newSupplierModel();
    const newSupplierModal = document.getElementById("newSupplierModal");
    newSupplierModal.style.display = "none;"
    $scope.newSupplierModalhideshow = (_disp) => {
        newSupplierModal.style.display = _disp;
        if (_disp === "flex") {
            document.getElementById("supplierCode").focus();
        }
    } 

    $scope.saveNewSupplierbtn_click = async () => supplierSaveFun();
    async function supplierSaveFun() {
        if ($scope.isLoading) return;
        $scope.supplierList = [];
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
        //alert("Data Has Saved");
        $scope.isLoading = false;
        $scope.supplierlist = res.data;
        console.log($scope.supplierlist);
        $scope.newsupplier = newSupplierModel();
        document.getElementById("supplierCode").focus();
        $scope.$apply();
        return;
    }

    $scope.removepurchase = async(_id, code) => {
        if ($scope.isLoading) true;
        const c = confirm("Are You Sure Remove this data?");
        if (!c) return;
        const res = await apis.GET(`purchase/remove.php?id=${_id}&code=${code}`);
        if (!res?.isSuccess) {
            alert(res.msg);
            $scope.isLoading = false;
            $scope.$apply();
            return;
        } 

        alert("Data Has Removed");
        $scope.isLoading = false;
        $scope.newpurchaselist = res.data;
        const tot = res.data.reduce((a, b) => { return (+a) + (+b.purchaseSubtot); }, 0);
        $scope.sumofinvoice = tot;
        $scope.$apply();
        return;
        
    }
    $scope.searchItemBarcode = async ($event) => {
        if ($event.which === 13) {
            await ScanBarcode($event.target.value);
        }
        if ($event.which === 40) {
            productListModal.style.display = "flex";
            document.getElementById("srcproductname_india").value = "";
            document.getElementById("srcproductname_india").focus();
        } 
    }
    //new products
    const newProductModal = document.getElementById("newProductModal");
    newProductModal.style.display = "none";
    $scope.newProductModalshowhide = (_disp) => {
        newProductModal.style.display = _disp;
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
       // alert(res.msg);
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
        const res = await apis.POST('units/new.php',fd);
        if (!res.isSuccess) {
            alert(res.msg);
            $scope.isLoading = false;
            $scope.$apply();
            return;
        }
        //alert(res.msg);
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

    async function _savenewProduct() {
        if ($scope.isLoading) return;
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
        $scope.productList = res.data;
       // console.log(models.EmptyproductModel())
        $scope.newproduct = productModel();
        console.log($scope.newproduct);
        $scope.$apply();
        return;
        
    }

    //enter key foucs 
    //suppliers
    $scope.suppliernxt = async ($event, nxt) => {
        if ($event.which === 13) {
            if (nxt === "saveaction") {
                 await supplierSaveFun();
            } else {
                document.getElementById(nxt).focus();
            }
        }
    }

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

    $scope.selectProduct = ($event) => {
        if ($event.which === 13) {
            let fil = $scope.srcproduct === "" ? "" : $scope.srcproduct;
            if (fil === undefined) {
                let _barcode = $scope.productList[0].productBarcode;
                ScanBarcode(_barcode);
                document.getElementById("productListModal").style.display = "none";
                return;
            }
            console.log(fil);
            console.log($scope.productList[0])
            const filterOpj = $scope.productList.filter((x) =>
                x.productNametamil.toLowerCase() === fil.toLowerCase() || 
                x.productName.toLowerCase() === fil.toLowerCase() || 
                x.productBarcode.toLowerCase() === fil.toLowerCase() 
            );
            console.log(filterOpj);
            let _barcode = $scope.filterOpj[0].productBarcode;
            ScanBarcode(_barcode);
            document.getElementById("productListModal").style.display = "none";
        }
    }
    
    
}