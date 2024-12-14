import API_Services from "../../../src/apiservices.js";
export default function dashBordContoller($scope) {
    $scope.summaryaccess = useraccess.homepagesummary;
    $scope.lowstockaccess = useraccess.homepagelowstock;
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
    $scope.dashboarddata = {
        products: {
            totProducts : 0,
            lowStocks : [],
        },
        purchase: {
            countOfPurhcase: 0,
            sumOfPurchase : 0,
        },
        sales: {
            cntBills: 0,
            cntCustomer: 0,
            sumofBills : 0,
        },
        suppliers: {
            balanceOfSuppliers: 0,
            countOfSuppliers : 0
        }

    };
    //$scope.dashboarddata.products.lowStocks = [];
    const apis = new API_Services();
    async function Dashboardfetch(stdate = "", enddate = "") {
        
        if ($scope.isLoading) return;
        $scope.isLoading = true;
        const res = await apis.GET(`dashboard/index.php?stdate=${stdate}&enddate=${enddate}`);
        if (!res?.isSuccess) {
            alert(res.msg);
            $scope.isLoading = false;
            $scope.dashboarddata = {};
            $scope.$apply();
            return;
        }
        $scope.isLoading = false;
        $scope.dashboarddata = res.data;
        $scope.$apply();
        return;
    }

    Dashboardfetch();

    $scope.getrpt = async() => {
        const stdate = $scope.dashboard?.stdate ?? "";
        const enddate = $scope.dashboard?.enddate ?? "";
        await Dashboardfetch(stdate, enddate);
    }
}