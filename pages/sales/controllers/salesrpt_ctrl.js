import API_Services from './../../../src/apiservices.js';
import { salesRptModel } from '../models/salesmodels.js';
export default function SalesReportController($scope) {
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
    const apis = new API_Services();
    const cols = salesRptModel();
    const gridOptions = apis._gridOptions(cols);
    
    gridOptions.onFilterChanged = onFilterChanged;
    $scope.rpttot = {
        totrows: 0,
        totitemsqty: 0,
        totitemsubttotal: 0,
        totprofilt: 0
    };

    function Calc(datas) {
        let totrows = datas.length;
        let totitemsqty = 0;
        let totitemsubttotal = 0;
        let totprofilt = 0;
        datas.map((i) => {
            totitemsqty += (+i.salesQty) ;
            totitemsubttotal += (+i.salessubtot);
            totprofilt += (+i.profilt);
        })
        $scope.rpttot = {
            totrows : totrows,
            totitemsqty :totitemsqty,
            totitemsubttotal: totitemsubttotal,
            totprofilt: totprofilt
        };
        m === "0" ? console.log($scope.rpttot) : "";
    }
    function onFilterChanged() {
        let _data = [];
        gridOptions.api.forEachNodeAfterFilterAndSort((_) => {
            _data.push(_.data);
        })
        Calc(_data);
        $scope.$apply();
        return;
    }
    
    const gridDiv = document.querySelector('#myGrid');
    new agGrid.Grid(gridDiv, gridOptions);
    gridOptions.api.setRowData([]);

    $scope.rpttot = {
        totrows: 0,
        totitemsqty: 0,
        totitemsubttotal: 0,
        totprofilt: 0
    };
    SalesRpt();
    async function SalesRpt(stdate = "", enddate = "") {
        if ($scope.isLoading) return;
        gridOptions.api.setRowData([]);
        $scope.isLoading = true;
        const res = await apis.GET(`sales/rpt.php?stdate=${stdate}&enddate=${enddate}`);
        if (!res?.isSuccess) {
            alert(res.msg);
            $scope.isLoading = false;
            $scope.$apply();
            return;
        }
        gridOptions.api.setRowData(res.data);
        Calc(res.data);
        $scope.isLoading = false;
        $scope.$apply();
        return;
    }

    $scope.dashboard = {
        stdate: "",
        enddate : "",
    }

    $scope.getrpt = async () => {
        const sd = $scope?.dashboard?.stdate ?? ""
        const ed = $scope?.dashboard?.enddate ?? "";
        await SalesRpt(sd, ed);
    }

}