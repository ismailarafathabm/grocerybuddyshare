<?php
include_once 'mac.php';
class SalesController extends mac
{
    private $cn, $cm, $sql;
    function __construct($db)
    {
        $this->cn = $db;
    }

    private function ISales($rows)
    {
        extract($rows);
        $cols = [];
        $cols['_id'] = $_id;
        $cols['salesInvoice'] = $salesInvoice;
        $cols['salesDate'] = $salesDate;
        $cols['salesCustomerName'] = $salesCustomerName;
        $cols['salesCustomerPhone'] = $salesCustomerPhone;
        $cols['salesCustomerAddress'] = $salesCustomerAddress;
        $cols['salesProduct'] = $salesProduct;
        $cols['salesQty'] = $salesQty;
        $cols['salesPPrice'] = $salesPPrice;
        $cols['salesSPrice'] = $salesSPrice;
        $cols['salesCGSTpres'] = $salesCGSTpres;
        $cols['salesCGSTval'] = $salesCGSTval;
        $cols['salesSGSTpres'] = $salesSGSTpres;
        $cols['salesSGSTval'] = $salesSGSTval;
        $cols['salesOthers'] = $salesOthers;
        $cols['salesPaymentType'] = $salesPaymentType;
        $cols['cBy'] = $cBy;
        $cols['eBy'] = $eBy;
        $cols['cDate'] = $cDate;
        $cols['eDate'] = $eDate;
        $cols['status'] = $status;
        $cols['salesRefNo'] = $salesRefNo;
        $cols['salesCustomerPaid'] = $salesCustomerPaid;
        $cols['salesCustomerBalance'] = $salesCustomerBalance;
        $cols['salesPaymentRefno'] = $salesPaymentRefno;
        $cols['salestot'] = $salestot;
        $cols['salestotgst'] = $salestotgst;
        $cols['salessubtot'] = $salessubtot;
        $cols['salesnetprice'] = $salesnetprice;
        $cols['cususedpoints'] = $cususedpoints;
        $cols['salesMrp'] = $salesMrp;
        $cols['payable'] = $payable;

        $cols['salesDates'] = $this->datemethod($salesDate);
        return $cols;
    }

    public function genInvoiceNo()
    {
        $salesRefno = 0;
        $this->sql = "SELECT MAX(salesRefno) as lastno from sales order by _id desc limit 1";
        $this->cm = $this->cn->prepare($this->sql);
        $this->cm->execute();
        if ($this->cm->rowCount() === 0) {
            $salesRefno = 1;
        } else {
            $rows = $this->cm->fetch(PDO::FETCH_ASSOC);
            $refno = (int)$rows['lastno'];
            $salesRefno = $refno + 1;
        }

        unset($this->cm, $this->sql, $rows);
        return $salesRefno;
    }

    private function _getAllSales($salesRefno)
    {
        //echo $salesRefno;
        $this->sql = "SELECT pu.*,pr.productName,pr.productNametamil,pr.productgst,uni.unitType FROM sales as pu 
        inner join 
            products as pr on pu.salesProduct = pr._id inner join productUnits as uni on pr.productUnitType = uni._id 
        where pu.salesRefno = :salesRefno";
        
        $this->cm = $this->cn->prepare($this->sql);
        $this->cm->bindParam(":salesRefno", $salesRefno);
        $this->cm->execute();
        $sales = [];
        while ($rows = $this->cm->fetch(PDO::FETCH_ASSOC)) {
            $sale = $this->ISales($rows);
            extract($rows);
            $sale['productName'] = $productName;
            $sale['productNametamil'] = $productNametamil;
            $sale['unitType'] = $unitType;
            $sale['productgst'] = $productgst;
            $sales[] = $sale;
        }
        unset($this->cm, $this->sql, $rows);
        //print_r($sales);
        return $sales;
    }

    public function GetAllSales($salesRefno)
    {
        $sales = $this->_getAllSales($salesRefno);
        return $this->res(true, "ok", $sales, 200);
        exit();
    }

    private function _salesview($year)
    {
        $sales = [];
        $stdate = "{$year}-01-01";
        $enddate = "{$year}-12-31";
        $this->sql = "SELECT salesInvoice,salesDate,salesCustomerName,salesCustomerPhone,salesCustomerAddress,salesRefNo,status,
        sum(salesQty) as sqty,sum(salestot) as stot,sum(salestotgst) as gstval,sum(salessubtot) as subtotval from sales 
        where salesDate >= '{$stdate}' and salesDate <= '{$enddate}' 
        group by salesInvoice,salesDate,salesCustomerName,salesCustomerPhone,salesCustomerAddress,salesRefNo,status order by cDate desc";
       // echo $this->sql;
        $this->cm = $this->cn->prepare($this->sql);
        // $this->cm->bindParam(":stdate",$stdate);
        // $this->cm->bindParam(":enddate",$enddate);
        $this->cm->execute();
        while($rows = $this->cm->fetch(PDO::FETCH_ASSOC)){
            extract($rows);
            $statustxtlist = ['on-edit','closed','hold'];
            $statustxt = $statustxtlist[$status];
            $sale = array(
                "salesInvoice" => $salesInvoice,
                "salesDate" => $salesDate,
                "salesCustomerName" => $salesCustomerName,
                "salesCustomerPhone" => $salesCustomerPhone,
                "salesCustomerAddress" => $salesCustomerAddress,
                "salesRefNo" => $salesRefNo,
                "sqty" => $sqty,
                "stot" => $stot,
                "gstval" => $gstval,
                "subtotval" => $subtotval,
                "salesDates" => $this->datemethod($salesDate),
                "status" => $status,
                "statusTxt" => $statustxt
            );
           // print_r($sale);
            $sales[] = $sale;
        }
        unset($this->sql,$this->cm,$rows);
        return $sales;
    }
    public function SalesView($year)
    {
        $sales = $this->_salesview($year);
        
        return $this->res(true, "ok", $sales, 200);
        exit();
    }

    private function _newSales($ISales)
    {
        $this->sql = "INSERT INTO sales values(
            null,
            :salesInvoice,
            :salesDate,
            :salesCustomerName,
            :salesCustomerPhone,
            :salesCustomerAddress,
            :salesProduct,
            :salesQty,
            :salesPPrice,
            :salesSPrice,
            :salesCGSTpres,
            :salesCGSTval,
            :salesSGSTpres,
            :salesSGSTval,
            :salesOthers,
            :salesPaymentType,
            :cBy,
            :eBy,
            now(),
            now(),
            :status,
            :salesRefno,
            :salesCustomerPaid,
            :salesCustomerBalance,
            :salesPaymentRefno,
            :salestot,
            :salestotgst,
            :salessubtot,
            :salesnetprice,
            :salesMrp,
            :salesPurchassRefno,
            :salesHaveExp,
            :salesPackedDate,
            :salesExpiryDate,
            0,0
        )";
        $this->cm = $this->cn->prepare($this->sql);
        $isSave = $this->cm->execute($ISales);
        unset($this->cm, $this->sql);
        return $isSave;
    }

    public function NewSales($ISales)
    {
        $salesRefno = $ISales[':salesRefno'];
        $isSave = $this->_newSales($ISales);
        if (!$isSave) {
            return $this->res(false, "Error on Save Data", [], 500);
            exit();
        }
        $sales = $this->_getAllSales($salesRefno);
        return $this->res(true, "ok", $sales, 201);
        exit();
    }

    private function _updatePayment($Payment){
        $this->sql = "UPDATE sales set 
        salesCustomerName = :salesCustomerName,
        salesCustomerPhone = :salesCustomerPhone,
        salesCustomerAddress = :salesCustomerAddress,
        salesPaymentType = :salesPayentType,
        status = :status,
        salesCustomerPaid = :salesCustomerPaid,
        salesCustomerBalance = :salesCustomerBalance,
        salesPaymentRefno = :salesPaymentRefno,
        cususedpoints = :cususedpoints,
        salesOthers = :salesOthers,
        payable = :payable     
        where salesRefNo = :salesRefNo";
        $this->cm = $this->cn->prepare($this->sql);
        $isUpdate = $this->cm->execute($Payment);
        unset($this->sql,$this->cm);
        return $isUpdate;
    }

    public function UpdateBillPayment($Payment){
        $isupdated = (bool)$this->_updatePayment($Payment);
        if(!$isupdated){
            return $this->res(false,"Error on update data",[],500);
            exit();
        }
        return $this->res(true,"Saved",[],200);
        exit();
    }

    private function _checkusernotclosed($userId){
        $this->sql = "SELECT count(_id) as cnt from sales where cBy = :cBy and status = 0";
        $this->cm = $this->cn->prepare($this->sql);
        $this->cm->bindParam(":cBy",$userId);
        $this->cm->execute();
        $rows = $this->cm->fetch(PDO::FETCH_ASSOC);
        $cnt = (int)$rows['cnt'];
        unset($this->cm,$this->sql,$rows);
        return $cnt;
    }

    private function _getusernotclosed($userId){
        $this->sql = "SELECT salesRefno FROM sales where cBy = :cBy and status = 0 order by _id desc limit 1";
        $this->cm = $this->cn->prepare($this->sql);
        $this->cm->bindParam(":cBy",$userId);
        $this->cm->execute();
        $rows = $this->cm->fetch(PDO::FETCH_ASSOC);
        $salesRefno = $rows['salesRefno'];
        unset($this->cm,$this->sql,$rows);
        return $salesRefno;
    }
    public function GetBillInfo($salesRefno){
        $sales = $this->_getAllSales($salesRefno);
       
        $data = array(
            "isOldBill" => true,
            "sales" => $sales
        );
        return $this->res(true, "ok", $data, 200);
        exit();
    }
    public function UnClosedBill($userId){
        $cnt = (int)$this->_checkusernotclosed($userId);
        if($cnt === 0 ){
            $data = array(
                "isOldBill" => false,
            );
            return $this->res(true,"OK",$data,200);
            exit();
        }
        $salesRefno = $this->_getusernotclosed($userId);
        $sales = $this->_getAllSales($salesRefno);
        $data = array(
            "isOldBill" => true,
            "sales" => $sales
        );
        return $this->res(true, "ok", $data, 200);
        exit();
    }

    private function _removeItem($id){
        $this->sql = "DELETE FROM sales where _id = :_id limit 1";
        $this->cm = $this->cn->prepare($this->sql);
        $this->cm->bindParam(":_id",$id);
        $isRemoved = $this->cm->execute();
        unset($this->sql,$this->cm);
        return $isRemoved;
    }
    public function RemoveItem($id,$salesRefNo){
        $isRemoved = (bool)$this->_removeItem($id);
        if(!$isRemoved){
            return $this->res(false,"Error on Remove Date",[],500);
            exit();
        } 
        $sales = $this->_getAllSales($salesRefNo);
        return $this->res(true, "ok", $sales, 200);
        exit();

    }

    private function countofbills($stdate,$enddate){
        $this->sql = "SELECT salesInvoice  from sales where salesDate >= :stdate and salesDate <= :enddate group by salesInvoice"  ;
        $this->cm = $this->cn->prepare($this->sql);
        $params = array(
            ":stdate" => $stdate,
            ":enddate" => $enddate
        );
        $this->cm->execute($params);
        $rows = $this->cm->fetch(PDO::FETCH_ASSOC);
        $cnt = (int)$this->cm->rowCount();
        unset($this->cm,$this->sql,$rows);
        return $cnt;
    }

    private function counttotalofbills($stdate,$enddate){
        $this->sql = "SELECT IFNULL(sum(salessubtot),0) as sumofsbill from sales where salesDate >= :stdate and salesDate <= :enddate";
        $this->cm = $this->cn->prepare($this->sql);
        $params = array(
            ":stdate" => $stdate,
            ":enddate" => $enddate
        );
        $this->cm->execute($params);
        $rows = $this->cm->fetch(PDO::FETCH_ASSOC);
        $sumofbills = (float)$rows['sumofsbill'];
        unset($this->cm,$this->sql,$rows);
        return $sumofbills;
    }

    private function customercount(){
        $this->sql = "SELECT salesCustomerPhone,IFNULL(count(salesCustomerPhone),0) as cnt from sales group by  salesCustomerPhone";
        $this->cm = $this->cn->prepare($this->sql);
        $this->cm->execute();
        $rows = $this->cm->fetch(PDO::FETCH_ASSOC);
        $cnt = (int)$rows['cnt'];
        unset($this->cm,$this->sql,$rows);
        return $cnt;
    }
    private function countCustomers(){
        $this->sql = "SELECT COUNT(_id) as cnt from customers";
        $this->cm = $this->cn->prepare($this->sql);
        $this->cm->execute();
        $rows = $this->cm->fetch(PDO::FETCH_ASSOC);
        $cnt = (int)$rows['cnt'];
        unset($this->sql,$this->cm,$rows);
        return $cnt;
    }
    public function DashboardCountsFromSales($stdate,$enddate){
        $cntCustomers = $this->countCustomers();
        $cntBills = $this->countofbills($stdate,$enddate);
        $sumofBills = $this->counttotalofbills($stdate,$enddate);
        $data = array(
            "cntCustomer" => $cntCustomers,
            "cntBills" => $cntBills,
            "sumofBills" => $sumofBills
        );
        return $data;
        exit;
    }

    private function _checkunclosedbills(){
        $this->sql = "SELECT count(salesRefNo) as cnt from sales where status = 0 ";
        $this->cm = $this->cn->prepare($this->sql);
        $this->cm->execute();
        $rows = $this->cm->fetch(PDO::FETCH_ASSOC);
        $cnt = is_null($rows['cnt']) ? 0 :  (int)$rows['cnt'] ;
        unset($this->cm,$this->sql,$rows);
        return $cnt;
    }
    private function _enableeditbill($salesRefNo){
        $this->sql = "UPDATE sales set status = 0 where salesRefNo = :salesRefNo";
        $this->cm = $this->cn->prepare($this->sql);
        $this->cm->bindParam(":salesRefNo",$salesRefNo);
        $isUpdate = $this->cm->execute();
        unset($this->cm,$this->sql);
        return $isUpdate;
    }
    public function EnableEditBill($salesRefNo){
        $cnt = $this->_checkunclosedbills();
        if($cnt !== 0){
            return $this->res(false,"Already another Bill is Edit Process Please Save The old Bill",[],409);
            exit();
        }
        $isUpdate = $this->_enableeditbill($salesRefNo);
        if(!$isUpdate){
            return $this->res(false,"Error on Edit Bill",[],500);
            exit();
        }
        return $this->res(true,"ok",[],200);
        exit();
    }

    private function _updateSalesProductInfo($IUpdate){
        $this->sql = "UPDATE sales set 
        salesSPrice = :salesSPrice,
        salesCGSTpres = :salesCGSTpres,
        salesCGSTval = :salesCGSTval,
        salesSGSTpres = :salesSGSTpres,
        salesSGSTval = :salesSGSTval,
        salestot = :salestot,
        salestotgst = :salestotgst,
        salessubtot = :salessubtot,
        salesnetprice = :salesnetprice 
        where 
        _id = :_id
        ";
        $this->cm = $this->cn->prepare($this->sql);
        $isUpdate = $this->cm->execute($IUpdate);
        unset($this->sql,$this->cm);
        return $isUpdate;
    }

    public function UpdateSalesProductInfo($Iupdate,$salesRefNo){
        $isUpdate = $this->_updateSalesProductInfo($Iupdate);
        if(!$isUpdate){
            return $this->res(false,"Error on update",[],500);
            exit();
        }
        $sales = $this->_getAllSales($salesRefNo);
        return $this->res(true, "ok", $sales, 200);
        exit();
        
    }

    private function _itemsaleshistory($purchaseProduct){
        $this->sql = "SELECT sal.*,pr.productBarcode,pr.productName,pr.productNametamil FROM sales as sal inner join products as pr on 
        sal.salesProduct = pr._id
         where salesProduct = :salesProduct";
        $this->cm = $this->cn->prepare($this->sql);
        $this->cm->bindParam(":salesProduct",$purchaseProduct);
        $this->cm->execute();
        $purchases = [];
        while($rows = $this->cm->fetch(PDO::FETCH_ASSOC)){
            $purchase = $this->ISales($rows);
            $purchase['productBarcode'] = $rows['productBarcode'];
            $purchase['productName'] = $rows['productName'];
            $purchase['productNametamil'] = $rows['productNametamil'];
            $purchases[] = $purchase;
        }
        unset($rows,$this->cm,$this->sql);
        return $purchases;
    }

    public function Itemsaleshistory($purchaseProduct){
        $purchases = $this->_itemsaleshistory($purchaseProduct);
        return $this->res(true,"ok",$purchases,200);
        exit();
    }

    private function _salesRpt($params){
        $this->sql = "SELECT sal.*,
                        pr.productBarcode,
                        pr.productName,
                        pr.productNametamil,
                        pb.brandName,
                        pt.productTypeName,
                        pu.unitType 
                        from sales as sal 
            inner join 
            products as pr on sal.salesProduct = pr._id 
            inner join brands as pb on pr.productBrand = pb._id 
            inner join productTypes as pt on pr.productType = pt._id 
            inner join productUnits as pu on pr.productUnitType = pu._id 
            where sal.salesDate >= :stdate and sal.salesDate <= :enddate 
            order by cDate desc
            ";
        $this->cm = $this->cn->prepare($this->sql);
        $this->cm->execute($params);
        $sales = [];
        while($rows = $this->cm->fetch(PDO::FETCH_ASSOC)){
            $sal = $this->ISales($rows);
            $sal['productBarcode'] = $rows['productBarcode'];
            $sal['productName'] = ucwords(strtolower( $rows['productName']));
            $sal['productNametamil'] = ucwords(strtolower( $rows['productNametamil']));
            $sal['brandName'] = ucwords(strtolower($rows['brandName']));
            $sal['productTypeName'] = ucwords(strtolower($rows['productTypeName']));
            $sal['unitType'] = $rows['unitType'];
            $sal['profilt'] = ((float)$rows['salesSPrice'] - (float)$rows['salesPPrice']) * (float)$rows['salesQty'];
            $sales[] = $sal;
        }
        unset($this->cm,$this->sql,$rows);
        return $sales;
    }

    public function SalesReport($params){
        $rpt = $this->_salesRpt($params);
        return $this->res(true,"ok",$rpt,200);
        exit;
    }

    private function _setholdbill($salesRefNo){
        $this->sql = "UPDATE sales set status = 2 where salesRefNo = :salesRefNo";
        $this->cm = $this->cn->prepare($this->sql);
        $this->cm->bindParam(":salesRefNo",$salesRefNo);
        $isUpdated = $this->cm->execute();
        unset($this->sql,$this->cm);
        return $isUpdated;
    }

    public function HoldBill($salesRefNo){
        $isUpdated = $this->_setholdbill($salesRefNo);
        if(!$isUpdated){
            return $this->res(false,"Error on holding Sales Bill",[],500);
            exit;
        }
        return $this->res(true,"ok",[],200);
        exit;
    }

   
}
