'use strict';
import API_Services from '../../../src/apiservices.js';
export default function StockEntryControllerView($scope, $routeParams) {
    
    const invoicerefno = $routeParams.invoiceno;
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
    $scope.newproduct = {};
    $scope.newpurchase = {};
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

    function InvoiceSumofCalc(datas) {
        //sum of invoice
        const sumofinvoice = datas.reduce((a, b) => (+a) + (+b.purchaseSubtot), 0);
        m === "0" ? console.log(sumofinvoice) : "";
        //total of items
        const totitems = datas.reduce((a, b) => (+a) + (+b.purchaseQty), 0)
        
        //tot rows 

        const totrows = datas.length;
        $scope.sumofinvoice = sumofinvoice;
        $scope.sumofitems = totitems;
        $scope.totalitems = totrows;
        $scope.$apply();
        
    }
    LoadolBill(invoicerefno);
    $scope.invoiceinfo = {};
    async function LoadolBill(invoicerefno) {
        if ($scope.isLoading) return;
        $scope.isLoading = true;
        const res = await apis.GET(`purchase/get.php?invno=${invoicerefno}`);
        if (!res?.isSuccess) {
            $scope.isLoading = false;
            $scope.$apply();
            return;
        }

        if (!res.data || res.data.length === 0) {
            
            $scope.isLoading = false;
            $scope.$apply();
            return;
        }

        let invoice = res.data[0];
        $scope.invoiceinfo = invoice;
        $scope.uniqcode = invoice.purchaseUniqNo;
        $scope.newpurchase = {
            ...$scope.newpurchase,
            purchaseSupplier: invoice.purchaseSupplier.toString(),
            purchaseDate: invoice.purchaseDates.normal,
            purchaseLotCode: invoice.purchaseLotCode,
            purchaseInvoiceno: invoice.purchaseInvoiceno,
            
        }
        $scope.newpurchaselist = res.data;
        m === "0" ? console.log(invoice, $scope.newpurchase) : "";
        $scope.isLoading = false;
        InvoiceSumofCalc(res.data);
        $scope.$apply();

    }
}