<?php
include_once 'mac.php';
class SalesPointController extends mac
{
    private $cn, $cm, $sql;
    function __construct($db)
    {
        $this->cn = $db;
    }
    private function ICustomerSalesPoints($rows)
    {
        extract($rows);
        $cols = [];
        $cols['_id'] = $_id;
        $cols['customerPhone'] = $customerPhone;
        $cols['salesInvoice'] = $salesInvoice;
        $cols['salesInvoiceTotal'] = $salesInvoiceTotal;
        $cols['salesInvoicePointGet'] = $salesInvoicePointGet;
        $cols['cBy'] = $cBy;
        $cols['eBy'] = $eBy;
        $cols['cDate'] = $cDate;
        $cols['eDate'] = $eDate;
        return $cols;
    }


    public function AddSalesPoints($ICustomerSalesPoints)
    {
        $this->sql = "INSERT INTO customerSalesPoints values(
            null,
            :customerPhone,
            :salesInvoice,
            :salesInvoiceTotal,
            :salesInvoicePointGet,
            :cBy,
            :eBy,
            now(),
            now()
            )";
        $this->cm = $this->cn->prepare($this->sql);
        $isSave = $this->cm->execute($ICustomerSalesPoints);
        unset($this->sql, $this->cm);
        return $isSave;
    }

    private function _getCustomerGetpoinst($customerPhone)
    {
        $this->sql = "SELECT cus.*,
        sal.salesRefNo,sal.salesCustomerPhone,sal.salesDate,sal.salesInvoice,IFNULL(sqty,0) as sqlqty
        FROM customerSalesPoints as cus 
        left join (
            select salesRefNo,salesCustomerPhone,salesDate,salesInvoice,IFNULL(sum(salesQty),0) as sqty from sales 
            group by salesRefNo,salesCustomerPhone,salesDate,salesInvoice
        ) as sal on cus.customerPhone = sal.salesCustomerPhone and cus.salesInvoice = sal.salesRefNo
        where cus.customerPhone = :customerPhone";
        $this->cm = $this->cn->prepare($this->sql);
        $this->cm->bindParam(":customerPhone", $customerPhone);
        $this->cm->execute();
        $getpoints = [];
        while ($rows = $this->cm->fetch(PDO::FETCH_ASSOC)) {
            $getpoint = $this->ICustomerSalesPoints($rows);
            extract($rows);
            $getpoint['salesRefNo'] = $salesRefNo;
            $getpoint['salesCustomerPhone'] = $salesCustomerPhone;
            $getpoint['salesDate'] = $salesDate;
            $getpoint['salesDates'] = $this->datemethod($salesDate);
            $getpoint['salesInvoice'] = $salesInvoice;
            $getpoint['sqlqty'] = $sqlqty;
            
            $getpoints[] = $getpoint;
        }
        unset($this->sql,$this->cm,$rows);
        return $getpoints;
    }

    public function GetCusomerGetPoints($customerPhone){
        $getpoints = $this->_getCustomerGetpoinst($customerPhone);
        return $this->res(true,"ok",$getpoints,200);
        exit();
    }

    public function RemoveSalesPoint($customerPhone, $salesInvoice)
    {
        $this->sql = "DELETE FROM customerSalesPoints where customerPhone = :customerPhone and salesInvoice = :salesInvoice";
        $this->cm = $this->cn->prepare($this->sql);
        $this->cm->bindParam(":customerPhone", $customerPhone);
        $this->cm->bindParam(":salesInvoice", $salesInvoice);
        $isRemove = $this->cm->execute();
        unset($this->sql, $this->cm);
        return $isRemove;
    }

    private function ICustomerSalesPointUses($rows)
    {
        extract($rows);
        $cols = [];
        $cols['_id'] = $_id;
        $cols['useDate'] = $useDate;
        $cols['customerPhone'] = $customerPhone;
        $cols['usedPoints'] = $usedPoints;
        $cols['usePointSalesInvoiceNo'] = $usePointSalesInvoiceNo;
        $cols['cBy'] = $cBy;
        $cols['eBy'] = $cBy;
        $cols['cDate'] = $cDate;
        $cols['eDate'] = $eDate;
        $cols['useDates'] = $this->datemethod($useDates);
        return $cols;
    }

    public function UseSelesPoint($ICustomerSalesPointUses)
    {
        $this->sql = "INSERT INTO customerSalesPointUses values(
            null,
            :useDate,
            :customerPhone,
            :usedPoints,
            :usePointSalesInvoiceNo,
            :cBy,
            :eBy,
            now(),
            now()
            )";
        $this->cm = $this->cn->prepare($this->sql);
        $isSaved = $this->cm->execute($ICustomerSalesPointUses);
        unset($this->cm, $this->sql);
        return $isSaved;
    }

    public function RemoveUsedSalesPoints($customerPhone, $usePointSalesInvoiceNo)
    {
        $updateParams = array(
            ":customerPhone" => $customerPhone,
            ":usePointSalesInvoiceNo" => $usePointSalesInvoiceNo,
        );
        $this->sql = "DELETE FROM customerSalesPointUses where customerPhone = :customerPhone and usePointSalesInvoiceNo = :usePointSalesInvoiceNo";
        $this->cm = $this->cn->prepare($this->sql);
        $isRemove = $this->cm->execute($updateParams);
        unset($this->cm, $this->sql);
        return $isRemove;
    }

    private function _customerUsedPoints($customerPhone){
        $this->sql = "SELECT *from customerSalesPointUses where customerPhone = :customerPhone";
        $this->cm = $this->cn->prepare($this->sql);
        $this->cm->bindParam(":customerPhone",$customerPhone);
        $this->cm->execute();
        $usedpoints = [];
        while($rows = $this->cm->fetch(PDO::FETCH_ASSOC)){
            $point = $this->ICustomerSalesPointUses($rows);
            $usedpoints[] = $point;
        }
        unset($this->cm,$this->sql,$rows);
        return $usedpoints;
    }

    public function CusotmerUsedPoints($customerPhone){
        $usedpoints = $this->_customerUsedPoints($customerPhone);
        return $this->res(true,'ok',$usedpoints,200);
        exit();
    }
}
