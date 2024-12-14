import API_Services from './../../../src/apiservices.js';
import { productModel } from './../../products/models/productmodels.js';
import { newSalesModel, newBillModel } from '../models/salesmodels.js';

export default function SalesNewController($scope) {
    if (!useraccess.salesnew) {
        location.href = `${url}/index.php`
    }
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
    let refno = "";


    $scope.productBrandList = [];
    $scope.productList = [];
    $scope.productTypeList = [];
    $scope.productUnitList = [];
    $scope.newproduct = productModel();
    $scope.newSales = newSalesModel();
    $scope.barcodeproduct = {};
    $scope.newsaleslist = [];
    $scope.invoiceNo = "";
    $scope.salesRefNo = "";
    $scope.sumofinvoice = 0;
    $scope.sumofCgst = 0;
    $scope.sumofSgst = 0;
    $scope.sumofsubtotal = 0;
    $scope.newbill = newBillModel;


    $scope.brandlist = [];
    $scope.proeucttypelist = [];
    $scope.unitlist = []

    const apis = new API_Services();
    
    $scope.newSales.salesDate = apis.getCurrentdate();

    // const getAllBrandList = async () => {
    //     const res = await apis.GET('brands/index.php');
    //     if (!res?.isSuccess) { return []; }
    //     return res.data;
    // }
    // const getAllProductTypeList = async () => {
    //     const res = await apis.GET('producttype/index.php');
    //     if (!res?.isSuccess) { return []; } return res.data;
    // }
    // const getAllUnitList = async () => {
    //     const res = await apis.GET('units/index.php');
    //     if (!res?.isSuccess) { return []; } return res.data;
    // };
    const getAllProductList = async () => {
        const res = await apis.GET('products/index.php');
        if (!res.isSuccess) { return res.data; } return res.data;
    }


    const initLoadDatas = async () => {
        const res = await Promise.all([getAllProductList()]);
        $scope.productList = res[0];
        $scope.$apply();
        return;
    }
    initLoadDatas();
    const GetUnClosedBill = async () => {
        if ($scope.isLoading) return;
        $scope.isLoading = true;
        const res = await apis.GET('sales/index.php');
        if (!res.isSuccess) {
            alert(res.msg);
            $scope.isLoading = false;
            document.getElementById("src_productBarcode").focus();
            location.reload();
            return;
        }
        if (res?.data.isOldBill) {
            $scope.isLoading = false;
            const _oldbilldata = res.data.sales;
            $scope.newsaleslist = _oldbilldata;
            $scope.invoiceNo = _oldbilldata[0].salesInvoice;
            $scope.salesRefNo = _oldbilldata[0].salesRefNo;

            $scope.newSales = {
                ...$scope.newSales,
                salesInvoice: $scope.invoiceNo,
                salesDate: _oldbilldata[0].salesDates.normal
            }
            let billinfo = res.data[0];
            m === "0" ?console.log(billinfo) : "";
            $scope.newbill = {
                ...$scope.newbill,
                salesCustomerName: _oldbilldata[0].salesCustomerName,
                salesCustomerPhone : _oldbilldata[0].salesCustomerPhone,
                salesCustomerAddress : _oldbilldata[0].salesCustomerAddress,
                salesOthers: _oldbilldata[0].salesOthers,
                payable: _oldbilldata[0].payable,
                salesPayentType : _oldbilldata[0].salesPayentType
            }
            
            _billCaluclations(_oldbilldata);
            $scope.isLoading = false;
            $scope.$apply();
            document.getElementById("src_productBarcode").focus();
            return;
        }
        $scope.isLoading = false;
        $scope.$apply();
        document.getElementById("src_productBarcode").focus();
        return;
    }
    GetUnClosedBill();
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
        //ßconsole.log($scope.isLoading);
        if (_barcode === "") {
            if ($scope.newsaleslist.length !== 0) {
                saveBillModel.style.display = "flex";
                $scope.newbill.salesOthers = 0;
                document.getElementById("salesCustomerName").focus();
                return;
            }
            return;
        };
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
        const id = res.data._id;
        $scope.$apply();
        await LoadPatchItems(id);
        document.getElementById("productExpiry").focus();
        return;

    }
    $scope.patchlist = [];
    $scope.selectedpatch = {};
    async function LoadPatchItems(id) {
        if ($scope.isLoading) return;
        $scope.isLoading = true;
        const res = await apis.GET(`products/patchno.php?id=${id}`);
        if (!res.isSuccess) {
            alert(res.msg);
            $scope.isLoading = false;
            $scope.$apply();
            return;
        }

        $scope.patchlist = res.data.filter(x => (+x.balance) > 0);
        if ($scope.patchlist.length !== 0)
        {
            $scope.searchitem.productExpiry = $scope.patchlist[0].purchaseUniqNo;
            selectPatchFun()
            $scope.searchitem.productQty = "1";
        }
        
        $scope.isLoading = false;
        $scope.$apply();
        return;
    }
    $scope.selectpatch = () => selectPatchFun();
    let salesPPrice = "";
    function selectPatchFun() {
        const _uniqno = $scope?.searchitem?.productExpiry ?? "";
        if (!_uniqno || _uniqno === "") {
            return;
        }
        $scope.selectedpatch = $scope.patchlist.find(x => x.purchaseUniqNo === _uniqno);
        $scope.searchitem.productSPrice = $scope.selectedpatch.purchaseSPrice;
        salesPPrice = $scope.selectedpatch.purchasePrice;
        m === "0" ? console.log($scope.selectedpatch) : "";
    }
    $scope.searchitemaddtolist = async ($event, nxt) => {
        m === "0" ? console.log($event.which) : "";
        if ($event.which === 13) {

            if (nxt === "savebill") {
                await SaveNewPurchase();
            }
        }
        if ($event.which === 39) {
            if (nxt !== "") {
                document.getElementById(nxt).focus();
            }
        }
        if ($event.which === 37) {
            if (prv !== "") {
                document.getElementById(prv).focus();
            }
        }

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

    const newProductModal = document.getElementById("newProductModal");
    newProductModal.style.display = "none";
    $scope.newProductModalshowhide = (_disp) => {
        newProductModal.style.display = _disp;
    }

    //for new brands




    $scope.searchitemaddtolist = async ($event, nxt) => {
       
        if ($event.which === 13) {
            if (nxt === "savebill") {
                await SaveNewItem();
            } else {
                
                document.getElementById(nxt).focus();
            }
        }
    }

    $scope.additemtolist = async () => SaveNewItem();
    $scope.totItems = 0;
    $scope.totQty = 0;
    function _billCaluclations(datas) {
        $scope.totItems = datas.length;
        $scope.totQty = datas.reduce((a, b) => (+a) + (+b.salesQty), 0);
        $scope.sumofinvoice = datas.reduce((a, b) => (+a) + (+b.salestot), 0);
        m === "0" ? console.log($scope.sumofinvoice) : "";
        $scope.sumofCgst = datas.reduce((a, b) => (+a) + (+b.salesCGSTval), 0);
        $scope.sumofSgst = datas.reduce((a, b) => (+a) + (+b.salesSGSTval), 0);
        $scope.sumofsubtotal = datas.reduce((a, b) => (+a) + (+b.salesnetprice), 0);
        _balanceCalc();
    }
    $scope.customerbalance = 0;
    $scope.customerpaid = 0;
    $scope.usedpoint = 0;
    $scope.isUsedPoint = "No";
    function _balanceCalc() {
        // let _billtotal = $scope.sumofsubtotal ?? 0;
        // $scope.newbill.salessubtot = _billtotal;

        // $scope.customerpaid = $scope.newbill.salesCustomerPaid;
        // const customerpoints = $scope.newbill?.customepoints ?? 0;
        // if ((+customerpoints) >= 100) {
        //     $scope.isUsedPoint = "Yes";
        //     if ((+customerpoints) >= (+_billtotal)) {
        //         _billtotal = (+customerpoints) - (+_billtotal);
        //         $scope.usedpoint = (+customerpoints) - (+_billtotal);
        //         console.log($scope.usedpoint);
        //     } else {
        //         _billtotal = (+_billtotal) - (+customerpoints);
        //    }
        // }
        // $scope.newbill.balancefrompoints = _billtotal;
        // const _paid = $scope.newbill.salesCustomerPaid ?? 0;
        // const _balance = (+_billtotal) - (+_paid);
        // $scope.newbill.salesCustomerBalance = _balance;
        // $scope.customerbalance = _balance;


        let invoiceTotal = $scope.sumofsubtotal ?? 0;
        $scope.newbill.salessubtot = invoiceTotal;
        let points = $scope?.newbill?.customepoints ?? 0;
        let discount = $scope?.newbill?.salesOthers ?? 0;

        let payable = 0;

        if ((+points) >= 100) {
            $scope.isUsedPoint = "Yes";
            if ((+invoiceTotal) >= (+points)) {
                payable = (+invoiceTotal) - (+points);
                $scope.usedpoint = points;

                payable = (+payable) - (+discount);

                $scope.newbill.payable = Math.round(payable);
                let customerpaid = $scope?.newbill?.salesCustomerPaid ?? 0;
                let balance = (+payable) - (+customerpaid);
                $scope.newbill.payable = Math.round(payable);
                $scope.newbill.salesCustomerBalance = Math.round(balance)
                $scope.customerbalance = balance;
            } else {
                payable = 0;
                let balance = 0;
                $scope.newbill.payable = Math.round(payable);
                $scope.newbill.salesCustomerBalance = Math.round(balance)
                $scope.customerbalance = balance;
                $scope.usedpoint = invoiceTotal;

            }
        } else {
        
            payable += (+invoiceTotal);
            payable = (+payable) - (+discount);
            let customerpaid = $scope?.newbill?.salesCustomerPaid ?? 0;
            let balance = (+payable) - (+customerpaid);
            $scope.newbill.payable = Math.round(payable);
            $scope.newbill.salesCustomerBalance = Math.round(balance)
            $scope.customerbalance = balance;
        }


    }
    async function SaveNewItem() {
        if ($scope.isLoading) return;
        const salesInvoice = $scope?.invoiceNo ?? "getnew";
        const salesInvoiceRef = $scope?.salesRefNo ?? "getnew";
        const salesDate = $scope?.newSales?.salesDate ?? "";
        if (!salesDate || salesDate.trim() === "") {
            alert("Enter date");
            return;
        }
        m === "0" ? console.log($scope.searchitem) : "";
        const productid = $scope.searchitem?._id.toString() ?? "";
        m === "0" ? console.log(productid) : "";
        if (!productid || productid.trim() === "") {
            alert("Enter Product Info");
            return;
        }
        const productSPrice = $scope?.searchitem?.productSPrice ?? "";
        if (!productSPrice || productSPrice === "") {
            alert("Enter Price");
            return;
        }
        const productQty = $scope?.searchitem?.productQty ?? "";
        if (!productQty || productQty.trim() === "") {
            alert("Enter Qty");
            return;
        }

        const salesMrp = $scope.selectedpatch.purchaseMrp;
        const salesPurchassRefno = $scope.selectedpatch.purchaseUniqNo;
        const salesHaveExp = $scope.selectedpatch.purcahseHaveExpi;
        const salesPackedDate = $scope.selectedpatch.purchaseManDate;
        const salesExpiryDate = $scope.selectedpatch.purchaseExpdate;
        const fd = new FormData();
        fd.append("salesDate", salesDate);
        fd.append("salesInvoice", salesInvoice);
        fd.append("salesProduct", $scope?.searchitem?._id ?? "");
        fd.append("salesQty", productQty);
        fd.append("salesSPrice", productSPrice);
        fd.append('salesRefno', salesInvoiceRef);
        fd.append("salesMrp", salesMrp);
        fd.append("salesPurchassRefno", salesPurchassRefno);
        fd.append("salesHaveExp", salesHaveExp);
        fd.append("salesPackedDate", salesPackedDate);
        fd.append("salesExpiryDate", salesExpiryDate);
        fd.append("salesPPrice", salesPPrice);

        $scope.isLoading = true;
        const res = await apis.POST("sales/new.php", fd);
        if (!res?.isSuccess) {
            alert(res.msg);
            $scope.isLoading = false;
            $scope.$apply();
            return;
        }

        // alert("saved");
        $scope.newsaleslist = [];
        $scope.isLoading = false;
        $scope.invoiceNo = res.data[0].salesInvoice;
        $scope.newSales.salesInvoice = res.data[0].salesInvoice;
        m === "1" ?  console.log($scope.newSales) : "";
        $scope.salesRefNo = res.data[0].salesRefNo;
        $scope.newsaleslist = res.data;
        _billCaluclations(res.data);

        $scope.searchitem = productModel();
        document.getElementById("src_productBarcode").focus();
        $scope.$apply();
        // console.log(res);
        return;
    }

    $scope.removeSales = async (id, refno) => RemoveSalesItem(id, refno);
    async function RemoveSalesItem(id, refno) {
        const c = confirm("Are You Sure Remove?");
        if (!c) return;
        if ($scope.isLoading) return;
        $scope.isLoading = true;
        const res = await apis.GET(`sales/remove.php?id=${id}&code=${refno}`);
        if (!res.isSuccess) {
            alert(res.msg);
            $scope.isLoading = false;
            $scope.$apply();
            document.getElementById("src_productBarcode").focus();
            return;
        }
        _billCaluclations(res.data);
        $scope.newsaleslist = [];
        $scope.newsaleslist = res.data;
        $scope.isLoading = false;
        $scope.$apply();
        document.getElementById("src_productBarcode").focus();
        return;
    }

    const saveBillModel = document.getElementById("saveBillModel");
    saveBillModel.style.display = "none";

    $scope.saveBillModelshowhide = (_disp) => {
        saveBillModel.style.display = _disp;
        if (_disp === "flex") {
            $scope.newbill.salesOthers = 0;
            
            document.getElementById("salesCustomerName").focus();
        }
    }

    $scope.CalculateBalance = () => {
        _balanceCalc();
    }

    $scope.newbillsave_submit = async () => SaveBillPayments();
    async function SaveBillPayments() {
        $scope.isUsedPoint = "No";
        _balanceCalc();
        if ($scope.isLoading) return;
        const salesCustomerName = $scope.newbill?.salesCustomerName ?? "";
        const salesCustomerPhone = $scope.newbill?.salesCustomerPhone ?? "";
        const salesCustomerAddress = $scope.newbill?.salesCustomerAddress ?? "";

        const salesPayentType = $scope.newbill?.salesPayentType ?? "";

        if (!salesPayentType || salesPayentType === "") {
            alert("Enter Payment Type");
            return;
        }

        m === "0" ? console.log($scope.customerpaid) : "";
        m === "0" ? console.log($scope.customerbalance) : "";

        const salesCustomerPaid = $scope.newbill?.salesCustomerPaid ?? 0;

        const salesCustomerBalance = $scope?.newbill?.salesCustomerBalance ?? $scope.sumofinvoice;
        const discount = $scope.newbill.salesOthers ?? 0

        const fd = new FormData();
        fd.append("salesCustomerName", salesCustomerName);
        fd.append("salesCustomerPhone", salesCustomerPhone);
        fd.append("salesCustomerAddress", salesCustomerAddress);
        fd.append("salesPayentType", salesPayentType);
        fd.append("salesCustomerPaid", salesCustomerPaid);
        fd.append("salesCustomerBalance", salesCustomerBalance);
        fd.append("billtotal", $scope.sumofinvoice);
        fd.append("salesRefNo", $scope.salesRefNo);
        fd.append("issaveupoints", $scope.isUsedPoint);
        fd.append("issaveupoints", $scope?.newbill?.salesOthers ?? 0);
        fd.append("isCustomerUsingpoints", $scope.isUsedPoint);
        fd.append("usingPoints", $scope.usedpoint);
        fd.append("salesOthers", discount);
        fd.append("payable", $scope.newbill?.payable ?? 0);
        $scope.isLoading = true;
        const res = await apis.POST('sales/billpayment.php', fd);
        if (!res?.isSuccess) {
            alert(res.msg);
            $scope.isLoading = false;
            $scope.$apply();
            return;
        }
       // alert("Data Has Saved");
        $scope.isLoading = false;
        $scope.$apply();
        window.open(`${url}salesbill.php?salesrefno=${$scope.salesRefNo}`, "_blank", "width=1200px;height=500px");
        location.reload();
        return;

    }

    $scope.paymenttypechange = () => {
        const _fullpayment = ['cash', 'upi', 'card', 'net banking'];
        const paymenttype = $scope.newbill?.salesPayentType ?? "";
        if (paymenttype === "") {
            return;
        }
        const isIncludeFullpayment = _fullpayment.includes(paymenttype);
        if (isIncludeFullpayment) {
            $scope.newbill.salesCustomerPaid = Math.round($scope.customerbalance);
            _balanceCalc();
        }
    }
    $scope.movenxt = async ($event, nxt) => {
        if ($event.which === 13) {
            if (nxt === "saveinvoice") {
                await SaveBillPayments()
            } else {
                const ele = document.getElementById(nxt);
                ele.focus();
                if (nxt !== "salesPayentType") {
                    ele.select();
                }
            }
        }
    }
    $scope.customerpoints = {};
    $scope.getcustomerinfo = async () => {
        if ($scope.isLoading) return;
        const customerPhone = $scope?.newbill?.salesCustomerPhone ?? "";
        if (!customerPhone || customerPhone.trim() === "") return;
        $scope.isLoading = true;
        const res = await apis.GET(`customers/get.php?customerPhone=${customerPhone}`);
        if (!res?.isSuccess) {
            
            $scope.isLoading = false;
            $scope.$apply();
            return;
        }
        $scope.customerpoints = res.data;
        $scope.newbill = {
            ...$scope.newbill,
            salesCustomerName: res.data.customerName,
            salesCustomerAddress: res.data.customerAddress,
            customepoints: Math.round(res.data.bpoint)
        }
        $scope.isLoading = false;
        _balanceCalc();
        $scope.$apply();
        return;
    }

    $scope.updateproducts = async(x) => {
       await UpdateProductPrice(x)
    }
    $scope.updatex = async ($event, x,nxt,_id) => {
        if ($event.which === 13) {
            if (nxt === "updateitem") {
                await UpdateProductPrice(x);
            }else{
                const ele = document.getElementById(nxt + "" + _id);
                ele.focus();
                ele.select();
            }
        }
        
    }
    async function UpdateProductPrice(x) {
        m === "0" ? console.log(x) : ""; 
        
        if ($scope.isLoading) return;
        $scope.isLoading = true;
        const fd = new FormData();
        fd.append("id", x._id);
        fd.append("salesQty", x.salesQty);
        fd.append("salesSPrice", x.salesSPrice);
        fd.append("productgst", x.productgst);
        fd.append("salesRefNo", x.salesRefNo);
        const res = await apis.POST('sales/update.php', fd);
        if (!res.isSuccess) {
            alert(res.msg);
            $scope.isLoading = false;
            $scope.$apply();
            document.getElementById("src_productBarcode").focus();
            return;
        }
        _billCaluclations(res.data);
        alert("data has updated");
        $scope.isLoading = false;
        $scope.$apply();
        document.getElementById("src_productBarcode").focus();
        return;
        
    }

    $scope.holdbill = async () => await HoldBillFun();

    async function HoldBillFun() {
        refno = $scope.newsaleslist[0].salesRefNo;
        
        if ($scope.isLoading) return;
        $scope.isLoading = true;
        const res = await apis.GET(`sales/holdbill.php?billno=${refno}`);
        if (!res.isSuccess) {
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

    $scope.selectedIndex = 0;
    $scope.handlekeydown = ($event,x) => {
        if ($event.key === "ArrowDown") {
            $scope.selectedIndex = ($scope.selectedIndex + 1) % $scope.productList.length;
            $event.preventDefault();

        }
        if ($event.key === "ArrowUp") {
            $scope.selectedIndex = ($scope.selectedIndex - 1) % $scope.productList.length;
            $event.preventDefault();

        }
        if ($event.which === 13) {
            m === "0" ? console.log(x.productBarcode) : "";
            productListModal.style.display = "none";
            ScanBarcode(x.productBarcode);
        }

        //$scope.$apply();
    }

    $scope.focusrow = (index) => {
        if ($scope.selectedIndex === index) {
            let rows = document.querySelectorAll("tr");
            rows[index].focus();
        }
    }
}