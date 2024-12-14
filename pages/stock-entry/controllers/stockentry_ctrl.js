'use strict';
import API_Services from '../../../src/apiservices.js';
import { productModel } from '../../products/models/productmodels.js';
import { newPurchaseModel } from '../models/purchasemodel.js';
import { newSupplierModel } from '../../suppliers/models/suppliermodels.js';
export default function StockEntryController($scope) {
    if (!useraccess.purchasenew) {
        location.href = `${url}/index.php`
    }
    $scope.gregorianDatepickerConfig = {

        dateFormat: 'DD-MM-YYYY',
        defaultDisplay: 'gregorian',
        allowFuture: false,
    };
    $scope.gregorianDatepickerConfigx = {

        dateFormat: 'DD-MM-YYYY',
        defaultDisplay: 'gregorian',
        allowFuture: true,
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
    $scope.sumofitems = 0;
    $scope.totalitems = 0;
    $scope.itemtype = "";

    $scope.brandlist = [];
    $scope.proeucttypelist = [];
    $scope.unitlist = []

    const apis = new API_Services();
    //console.log(apis.getCurrentdate());

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
        if (!res.isSuccess) { return res.data; } return res.data;
    }
    const getAllSupplierList = async () => {
        const res = await apis.GET('suppliers/index.php');
        if (!res.isSuccess) { return res.data; } return res.data;
    }

    const initLoadDatas = async () => {
        const res = await Promise.all([getAllBrandList(), getAllProductTypeList(), getAllUnitList(), getAllProductList(), getAllSupplierList()]);
        //console.log(res);
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

    //get old 

    //calculation

    function InvoiceSumofCalc(datas) {
        //sum of invoice
        const sumofinvoice = datas.reduce((a, b) => (+a) + (+b.purchaseSubtot), 0);
        //console.log(sumofinvoice);
        //total of items
        const totitems = datas.reduce((a, b) => (+a) + (+b.purchaseQty), 0)
        
        //tot rows 

        const totrows = datas.length;
        $scope.sumofinvoice = sumofinvoice;
        $scope.sumofitems = totitems;
        $scope.totalitems = totrows;
        $scope.$apply();
        
    }
    LoadolBill();
    async function LoadolBill() {
        if ($scope.isLoading) return;
        $scope.isLoading = true;
        const res = await apis.GET('purchase/notclosed.php');
        if (!res?.isSuccess) {
            $scope.isLoading = false;
            $scope.$apply();
            return;
        }

        if (!res.data || res.data.length === 0) {
            //console.log("no data");
            $scope.isLoading = false;
            $scope.$apply();
            return;
        }

        let invoice = res.data[0];
        $scope.uniqcode = invoice.purchaseUniqNo;
        $scope.newpurchase = {
            ...$scope.newpurchase,
            purchaseSupplier: invoice.purchaseSupplier.toString(),
            purchaseDate: invoice.purchaseDates.normal,
            purchaseLotCode: invoice.purchaseLotCode,
            purchaseInvoiceno: invoice.purchaseInvoiceno,
        }
        $scope.newpurchaselist = res.data;
       // console.log(invoice, $scope.newpurchase)
        $scope.isLoading = false;
        InvoiceSumofCalc(res.data);
        $scope.$apply();

    }

    $scope.getuniqcode = async () => GetInvoiceUniqCode();

    async function GetInvoiceUniqCode() {
        const id = $scope.newpurchase.purchaseSupplier;
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

    $scope.removepurchase = async (_id, code) => {
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
        InvoiceSumofCalc(res.data);
        $scope.$apply();
        return;

    }

    //supplier

    $scope.suppliernxt = async ($event, nxt) => {
        if ($event.which === 13) {
            if (nxt === "saveaction") {
                await supplierSaveFun();
            } else {
                document.getElementById(nxt).focus();
            }
        }
    }

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
       // console.log($scope.supplierlist);
        $scope.newsupplier = newSupplierModel();
        document.getElementById("supplierCode").focus();
        $scope.$apply();
        return;
    }



    ///brnad controllers
    $scope.savefromkeydown = async ($event) => {
        if ($event.which === 13) {
            await saveNewBrand();
        }
    }
    const newBrandModal = document.getElementById("newBrandModal");
    $scope.showNewBrandModal = (_dis) => {
        newBrandModal.style.display = _dis; 
        if (_dis === "flex") {
            document.getElementById("brandName").focus();
        }
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
        $scope.brandlist = res.data;
        document.getElementById("brandName").value = "";
        document.getElementById("brandName").focus();
        $scope.$apply();
        return;
    }

    //product type

    $scope.producttypesaveKeydown = async ($event) => {
        if ($event.which === 13) {
            await saveProductTypefun();
        }
    }

    const newProductTypeModal = document.getElementById("newProductTypeModal");
    newProductTypeModal.style.display = "none";

    $scope.productModelshowhidefun = (_disp) => {
        newProductTypeModal.style.display = _disp;
        if (_disp === "flex") {
            document.getElementById("productTypeName").focus();
        }
    }
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
        document.getElementById("productTypeName").focus();
        $scope.$apply();
        return;
    }

    //product units
    $scope.unittypesave_keydown = async ($evnet) => {
        if ($evnet.which === 13) {
            await saveUnitTypefun()
        }
    }

    const newUnitTypeModal = document.getElementById("newUnitTypeModal");
    newUnitTypeModal.style.display = "none";
    $scope.unittModelshowhidefun = (_disp) => {
        newUnitTypeModal.style.display = _disp;
        if (_disp === "flex") {
            document.getElementById("unitType").focus();
        }
    }

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
        //alert(res.msg);
        $scope.unitlist = res.data;
        $scope.isLoading = false;
        const unitType = document.getElementById("unitType");
        unitType.value = "";
        unitType.focus();
        $scope.$apply();
        return;
    }

    //product 
    const newProductModal = document.getElementById("newProductModal");
    newProductModal.style.display = "none";
    $scope.newProductModalshowhide = (dip) => {
        newProductModal.style.display = dip;
    }

    $scope.AddNewPurchaseItem = () => {
        newProductModal.style.display = "flex";
        document.getElementById("productBarcode").focus();
        document.getElementById("productBarcode").select();
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
    const productListModal = document.getElementById("productListModal");
    productListModal.style.display = "none";
    $scope.getproductinfobtn = async (barcode) => await scanbarcode(barcode);
    async function scanbarcode(barcode) {
        if ($scope.isLoading) return;
        $scope.isLoading = true;
        const res = await apis.GET(`products/get.php?barcode=${barcode}`);
        if (!res?.isSuccess) {
            // alert(res.msg);
            $scope.itemtype = "new";
            document.getElementById('productSku').focus();
            $scope.isLoading = false;
            $scope.$apply();
            return;
        }
        $scope.isLoading = false;
        $scope.newproduct = res.data;
        productListModal.style.display = "none";
        $scope.newproduct.productType = res.data.productType.toString();
        $scope.newproduct.productUnitType = res.data.productUnitType.toString();
        $scope.newproduct.productBrand = res.data.productBrand.toString();
        $scope.itemtype = "old";
        document.getElementById('productQty').focus();
        document.getElementById("productQty").select();
        
        $scope.$apply();
        return;
    }
    
    $scope.productlistshowhide = (_disp) => {
        productListModal.style.display = _disp;
    }
    $scope.barcodescan_keypress = ($event) => {
        //console.log($event.which);
        if ($event.which === 13) {
            const data = $event.target.value;
            if (!data) {
                alert("Enter Barcode");
                return;
            }
            scanbarcode(data);
            return;
        }
        if ($event.which === 40) {
            productListModal.style.display = "flex";
            document.getElementById("srcproductname_india").focus();
            document.getElementById("srcproductname_india").select();
        }
        
    }
    $scope.saveNewProduct_btnclick = async () => await SavePurchaseOption();
    $scope.productNxtfoucs = async ($event, nxt) => {
        if ($event.which === 13) {
            if (nxt === "savenewpurhcase") {
                await SavePurchaseOption();
            } else {
           
                document.getElementById(nxt).focus();
                if (nxt === "productUnitType" || nxt === "isHaveExp" || nxt === "productBrand" || nxt === "productType") {

                } else {
                    document.getElementById(nxt).select();
                }
            
            }
        }
    }
    $scope.gstcal = ($event) => {
        const gst = $event.target.value;
        const sp = (+gst) / 2;
        $scope.newproduct.productCgst = sp.toString();
        $scope.newproduct.productSgst = sp.toString();
    }

    async function SavePurchaseOption() {
        if ($scope.isLoading) return; 
        const purchaseSupplier = $scope?.newpurchase?.purchaseSupplier ?? "";
        if (!purchaseSupplier || purchaseSupplier === "") {
            alert("Enter Supplier");
            return;
        }
        const purchaseDate = $scope?.newpurchase?.purchaseDate ?? "";
        if (!purchaseDate || purchaseDate === "") {
            alert("Enter Purchase Date");
            return;
        }
        const purchaseInvoiceno = $scope?.newpurchase?.purchaseInvoiceno ?? "";
        if (!purchaseInvoiceno || purchaseInvoiceno === "") {
            alert("Enter Purchase Invoice No");
            return;
        }
        const purchaseLotCode = $scope?.newpurchase.purchaseLotCode ?? "";
        
        const frm = document.getElementById("newProductSave");
        const fd = new FormData(frm);
        fd.append("purchaseUniqNo", $scope.uniqcode);
        fd.append("purchaseSupplier",purchaseSupplier);
        fd.append("purchaseDate", purchaseDate);
        fd.append("purchaseLotCode", purchaseLotCode);
        fd.append("purchaseInvoiceno", purchaseInvoiceno);
        fd.append("itemtype", $scope.itemtype);
        const purchaseProduct =  $scope?.newproduct?._id ?? ""
        fd.append("purchaseProduct", purchaseProduct);

        $scope.isLoading = true;
        const res = await apis.POST('purchase/new.php', fd);
        if (!res.isSuccess) {
            alert(res.msg);
            $scope.isLoading = false;
            $scope.$apply();
            return;
        }

        $scope.isLoading = false;
        $scope.newpurchaselist = res.data;
        InvoiceSumofCalc(res.data);
        $scope.newproduct = productModel();
        document.getElementById('productBarcode').focus();
        $scope.$apply();
        return;

        
    }

    const saveEntrymodal = document.getElementById("saveEntrymodal");
    saveEntrymodal.style.display = "none";
    $scope.saveBillModelshowhide = (_disp) => {
        saveEntrymodal.style.display = _disp;
        if (_disp === "flex") {
            
            //calc();
            document.getElementById("purchaseType").focus();
            document.getElementById("purchaseDiscounttype").focus();
            //calc();
        }
    }

    $scope.saveBill = async() => {
        await SaveBillfun();
    }
    async function SaveBillfun() {
        if ($scope.isLoading) return;
        const fd = new FormData();
        fd.append("purchaseothers", $scope?.newbill?.purchaseothers ?? 0);
        fd.append("purchaseType", $scope?.newbill?.purchaseType ?? 0);
        fd.append("purchasePaid", $scope?.newbill?.purchaseothers ?? 0);
        fd.append("purchasePaidRefno", $scope.uniqcode0);
        fd.append("purchaseDiscounttype", $scope?.newbill?.purchaseDiscounttype ?? 0);
        fd.append("purchaseDiscountval", $scope?.newbill?.purchaseDiscountval ?? 0);
        fd.append("purchaseUniqNo", $scope.uniqcode);
        $scope.isLoading = true;
        const res = await apis.POST("purchase/savebill.php", fd);
        if (!res?.isSuccess) {
            alert(res.msg); 
            $scope.isLoading = false;
            $scope.$apply();
            return;
        }
        $scope.isLoading = false;
        $scope.$apply();
        location.reload();
        return;
        
    }
    $scope.newbillnxtmove = async($event, nxt) => {
        
        if ($event.which === 13) {
            if (nxt === "savebill") {
                await SaveBillfun();
            } else {

                document.getElementById(nxt).focus();
                if (nxt !== "purchaseType") {
                    document.getElementById(nxt).select();
                }
            }
        }
    }
    $scope.paymenttypechange = () => {
        if ($scope.newbill.purchaseType === "cash") {
            calc();
            $scope.newbill.purchasePaid = $scope.newbill.salessubttoal;
            calc();
        } else {
            calc();
            $scope.newbill.purhcaseBalance = $scope.newbill.salessubttoal;
            calc();
        }
    }
    $scope.byprescal = true;
    function calc() {
        const invoiceAmount = $scope?.sumofinvoice ?? 0;
        //console.log(invoiceAmount);
        //$scope.newbill.salessubtot = $scope?.sumofinvoice ?? 0;
        const invoiceDiscount = $scope?.newbill?.purchaseDiscounttype ?? 0;
        let discountAmount;
        //console.log($scope.byprescal ? "val" : "pres");
        if (!$scope.byprescal) {
            const discoountval = $scope?.newbill?.purchaseDiscountval;
            const discountpres = Math.round((+discoountval) / (+invoiceAmount) * 100);
            $scope.newbill.purchaseDiscounttype = discountpres;
            discountAmount = $scope?.newbill?.purchaseDiscountval ?? 0;
        } else {
            discountAmount = ((+invoiceAmount) / 100) * (+invoiceDiscount);
            $scope.newbill.purchaseDiscountval = discountAmount;
        }

        const subtotal = (+invoiceAmount) - (+discountAmount);
        $scope.newbill.salessubttoal = subtotal;
        const otheramount = $scope?.newbill?.purchaseothers ?? 0;
        const netamount = (+subtotal) + (+otheramount);
        $scope.newbill.purcahseNetAmount = netamount;
        const purchasePaid = $scope?.newbill?.purchasePaid ?? 0;
        const balance = (+netamount) - (+purchasePaid);
        $scope.newbill.purhcaseBalance = balance;
    }
    $scope.CalculateBalance = () => calc();
    
    //edit option

    const editProduct = document.getElementById("editProduct");
    editProduct.style.display = "none";
    $scope.editProductModalshowHide = (_dip) => {
        editProduct.style.display = _dip;
        if (_dip === "flex") {
            document.getElementById("edit_productBarcode").focus();
        }
    }
    $scope.editproduct = {};
    $scope.editcurrentitem = (item) => {
        //console.log(item);
        $scope.editproduct = item;
        //console.log(item.purcahseHaveExpi);
        ///console.log($scope.editproduct);
        //console.log($scope.editProduct?.isHaveExp ?? "NOT SELETELE");
        $scope.editproduct = {
            ...$scope.editproduct,
            isHaveExp: item.purcahseHaveExpi.toString(),
            purchaseManDate: item.purchaseManDates.normal,
            purchaseExpdate : item.purchaseExpdates.normal,
        }
       
        
        editProduct.style.display = "flex";
        document.getElementById("edit_productBarcode").focus();
    }
    $scope.editproductkeynxt =async ($event, nxt) => {
        if ($event.which === 13) {
            if (nxt === "updateproductinfo") {
                await ProductInfoUpdateFun();
            } else {
                const ele = document.getElementById(nxt);
                ele.focus();
                if (nxt !== "edit_isHaveExp") {
                    ele.select();
                }
            }
        }
    }
    $scope.splitgstedit = () => {
        const gst = $scope.editproduct.productgst ?? 0;
        $scope.editproduct.productSgst = (+gst) / 2;
        $scope.editproduct.productCgst = (+gst) / 2;
    }
    $scope.updateproductpricealso = false;
    $scope.updateproductInfo = async () => ProductInfoUpdateFun();
    async function ProductInfoUpdateFun() {
        if ($scope.isLoading) return;
        const fd = new FormData();
        fd.append("purchaseQty", $scope.editproduct.purchaseQty);
        fd.append("purchasePrice", $scope.editproduct.purchasePrice);
        fd.append("purchaseManDate", $scope.editproduct.purchaseManDate);
        fd.append("purchaseExpdate", $scope.editproduct.purchaseExpdate);
        fd.append("purchaseSPrice", $scope.editproduct.purchaseSPrice);
        fd.append("purchaseMrp", $scope.editproduct.purchaseMrp);
        fd.append("purchaseMrp", $scope.editproduct.purchaseMrp);
        fd.append("updateproduct", $scope.updateproductpricealso ? "Yes" : "No");
        const gst1 = $scope.editproduct?.productCgst ?? 0;
        const gst2 = $scope.editproduct?.productSgst ?? 0;
        const gst = (+gst1) + (+gst2);
        fd.append("gst", gst);
        fd.append("id", $scope.editproduct._id);
        fd.append("purchaseUniqNo", $scope.editproduct.purchaseUniqNo);
        fd.append("purcahseHaveExpi", $scope.editproduct.isHaveExp);
        fd.append("prid", $scope.editproduct.purchaseProduct);

        $scope.isLoading = true;
        const res = await apis.POST('purchase/update.php', fd);
        if (!res.isSuccess) {
            alert(res.msg);
            $scope.isLoading = false;
            $scope.$apply();
            return;
        }
        alert("Data Has Updated");
        $scope.isLoading = false;
        $scope.newpurchaselist = res.data;
        InvoiceSumofCalc(res.data);
        $scope.$apply();
        return;


    }
}