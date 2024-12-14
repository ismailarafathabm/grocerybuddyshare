import API_Services from "../../../src/apiservices.js";
import { CustomerModelGrid } from "../models/customermodels.js";
export default function CustomerController($scope, $compile) {
    if (!useraccess.customerview) {
        location.href = `${url}/index.php`
    }
    $scope.isLoading = false;
    const apis = new API_Services();
    const cols = CustomerModelGrid($scope, $compile);
    const gridOptions = apis._gridOptions(cols);
    gridOptions.onCellClicked = onEditGridCell;
    const gridDiv = document.querySelector('#myGrid');
    new agGrid.Grid(gridDiv, gridOptions);
    gridOptions.api.setRowData([]);

    getallcustomers();
    async function getallcustomers() {
        if ($scope.isLoading) return;
        gridOptions.api.setRowData([]);
        $scope.isLoading = true;
        const res = await apis.GET("customers/index.php");
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
    const customergetpointsmodal = document.getElementById("customergetpointsmodal");
    customergetpointsmodal.style.display = "none";
    $scope.customergetpointsmodalshowhide = (_dip) => showhidegetpointsdialog(_dip);
    function showhidegetpointsdialog(disp) {
        customergetpointsmodal.style.display = disp;
    }

    $scope.getpointlist = [];
    $scope.usepointlist = [];
    $scope.customerPhone = "";
    $scope.customerGetPoints = "";
    $scope.customerUsedPoint = "";

    $scope.sumOfInvoices = 0;

    $scope.showGetPoinst = async (customerPhone, totgetpoints) => await ShowCustomerGetPoints(customerPhone, totgetpoints);
    async function ShowCustomerGetPoints(customerPhone, totgetpoints) {
        m === "0" ? console.log(customerPhone, totgetpoints) : "";
        if ($scope.isLoading) return;
        $scope.getpointlist = [];
        $scope.isLoading = true;
        const res = await apis.GET(`customers/getpoints.php?customerPhone=${customerPhone}`);
        if (!res?.isSuccess) {
            alert(res.msg);
            $scope.isLoading = false;
            $scope.$apply();
            return;
        }
        $scope.isLoading = false;
        $scope.getpointlist = res.data;
        $scope.sumOfInvoices = res.data.reduce((a, b) => (+a) + (+b.salesInvoiceTotal), 0);
        $scope.customerPhone = customerPhone;
        $scope.customerGetPoints = totgetpoints;
        $scope.$apply();
        showhidegetpointsdialog('flex');
        return;
    }

    $scope.showUsedPoinst = async (customer, points) => await ShowCustomerUsedPoints(customer, points);
    async function ShowCustomerUsedPoints(customerPhone, totgetpoints) {
        m === "0" ? console.log(customerPhone, totgetpoints) : "";
        if ($scope.isLoading) return;
        $scope.usepointlist = [];
        $scope.isLoading = true;
        const res = await apis.GET(`customers/usedpoints.php?customerPhone=${customerPhone}`);
        if (!res?.isSuccess) {
            alert(res.msg);
            $scope.isLoading = false;
            $scope.$apply();
            return;
        }
        $scope.isLoading = false;
        $scope.usepointlist = res.data;
        //$scope.sumOfInvoices = res.data.reduce((a, b) => (+a) + (+b.salesInvoiceTotal), 0);
        $scope.customerPhone = customerPhone;
        $scope.customerUsedPoint = totgetpoints;
        $scope.$apply();
        //showhidepointsdialog('flex');
        return;
    }

    function onEditGridCell(event) {
        m === "0" ? console.log(event.data) : "";
        const { data, colDef } = event;
        if (colDef.field === "customerName") {
            EditUserFun(data);
        }
    }
    $scope.editcustomer = {
        _id: "",
        customerName: "",
        customerAddress: "",
        custoemrPhone: "",
    };
    const editcustomerModal = document.getElementById("editcustomerModal");
    function editcustomerModalshowhidefun(disp) {
        editcustomerModal.style.display = disp;
    }
    $scope.editcustomerModalshowhide = (disp) => editcustomerModalshowhidefun(disp);
    function EditUserFun(userdata) {
        $scope.editcustomer = userdata
        m === "0" ? console.log($scope.editcustomer) : "";
        editcustomerModalshowhidefun("flex");
        document.getElementById("customerName").focus();
        $scope.$apply();
        return;
    }
    $scope.movenxtedit = async ($event, nxt) => {
        if ($event.which === 13) {
            if (nxt === "updatecustomer") {
                await UpdateCustomerfun();
            } else {
                const ele = document.getElementById(nxt);
                ele.focus();
                ele.select();
            }
        }
    }
    $scope.updateuser_fun = async () => UpdateCustomerfun();
    async function UpdateCustomerfun() {
        if ($scope.isLoading) return;
        const fd = new FormData(
            document.getElementById("userupdate")
        );
        const id = $scope.editcustomer._id;
        gridOptions.api.setRowData([]);
        $scope.isLoading = false;
        const res = await apis.POST(`customers/update.php?id=${id}`, fd);
        if (!res.isSuccess) {
            alert(res.msg);
            $scope.isLoading = false;
            $scope.$apply();
            document.getElementById("customerName").focus();
            return;
        }
        alert("Data Has Update");
        $scope.isLoading = false;
        gridOptions.api.setRowData(res.data);
        $scope.$apply();
        return;
    }
}