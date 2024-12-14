<?php 
    // $sql = "SELECT 
    //         `purchaseUniqNo`,`purchaseDate`,`purchaseSupplier`,`purchaseLotCode`,`purchaseInvoiceno`,
    //         sum(purchaseSubtot + x.gstval) as totpurchase,sup.supplierName,
    //         x.gstval
    //         from purchases as pur 
    //         inner join (
    //             select purchaseQty,purchasePrice,purchaseUniqNo as uniqno,purchaseProduct,
    //             sum(round(p.purchasePrice*b.productgst/100,2)*p.purchaseQty) as gstval
    //             from purchases as p  inner join 
    //             products as b on p.purchaseProduct = b._id group by  purchaseQty,purchasePrice,purchaseUniqNo,purchaseProduct
    //         ) as x on pur.purchaseUniqNo = x.uniqno
    //         inner join suppliers as sup on pur.purchaseSupplier=sup._id 
    //         group by `purchaseUniqNo`,`purchaseDate`,`purchaseSupplier`,`purchaseLotCode`,`purchaseInvoiceno`
    //         ";
    include_once 'mac.php';
    class PurchaseController extends mac{
        private $cn;
        private $cm;
        private $sql;
        function __construct($db)
        {
            $this->cn = $db;
        }

        private function IPurchase($rows){
            extract($rows);
            $cols = [];
            $cols['_id'] = $_id;
            $cols['purchaseUniqNo'] = $purchaseUniqNo;
            $cols['purchaseDate'] = $purchaseDate;
            $cols['purchaseSupplier'] = $purchaseSupplier;
            $cols['purchaseLotCode'] = $purchaseLotCode;
            $cols['purchaseProduct'] = $purchaseProduct;
            $cols['purchaseQty'] = $purchaseQty;
            $cols['purchasePrice'] = $purchasePrice;
            $cols['purchaseSubtot'] = $purchaseSubtot;
            $cols['purchaseInvoiceno'] = $purchaseInvoiceno;
            $cols['cBy'] = $cBy;
            $cols['eBy'] = $eBy;
            $cols['cDate'] = $cDate;
            $cols['eDate'] = $eDate;
            $cols['purchaseothers'] = $purchaseothers;
            $cols['status'] = $status;
            $cols['purchaseType'] = $purchaseType;
            $cols['purchasePaid'] = $purchasePaid;
            $cols['purchasePaidRefno'] = $purchasePaidRefno;
            $cols['purcahseHaveExpi'] = $purcahseHaveExpi;
            $cols['purchaseManDate'] = $purchaseManDate;
            $cols['purchaseManDates'] = $this->datemethod($purchaseManDate);
            $cols['purchaseExpdates'] = $this->datemethod($purchaseExpdate);
            $cols['purchaseDiscounttype'] = $purchaseDiscounttype;
            $cols['purchaseDiscountval'] = $purchaseDiscountval;
            $cols['purchaseSPrice'] = $purchaseSPrice;
            $cols['purchaseMrp'] = $purchaseMrp;
            $cols['purchaseGst'] = $purchaseGst;
            $cols['purchaseCgst'] = $purchaseCgst;
            $cols['purchaseSgst'] = $purchaseSgst;
            $cols['purhcaseGstval'] = $purhcaseGstval;
            $cols['purhcaseGsttotval'] = $purhcaseGsttotval;
            //user defined

            $cols['purchaseDates'] = $this->datemethod($purchaseDate);
            return $cols;
        }
        public function AllPurchase(){
            $purchases = [];
            return $purchases;
          //  $this->sql = "SELECT purchaseUniqNo,purchaseDate,purchaseSupplier,purchaseInvoiceno,sum(purchaseQty) as totqty,sum(purchaseSubtot) as totalval FROM purchases group by purchaseUniqNo,purchaseDate,purchaseSupplier,purchaseInvoiceno order by purchaseDate desc";
        }
        
        public function _viewPurchaseinfo($purchaseUniqNo){
            $this->sql = "SELECT pu.*,
             pr.productName, pr.productNametamil, pr.productBarcode, pr.productgst, pr.productCgst, pr.productSgst, uni.unitType, 
             round(pu.purchasePrice*pr.productgst/100,2) as gstvals,
             round(pu.purchasePrice*pr.productgst/100,2) + pu.purchasePrice as itemprice,
             round(((pu.purchasePrice*pr.productgst/100) + pu.purchasePrice) * purchaseQty,2)  as itemsubtotal,
             (select purchaseDiscountval as discount from purchases where purchaseUniqNo=pu.purchaseUniqNo limit 1) as dis
             FROM purchases as pu inner join products as pr on pu.purchaseProduct = pr._id 
             inner join productUnits as uni on pr.productUnitType = uni._id 
             where pu.purchaseUniqNo = :purchaseUniqNo";
            $this->cm = $this->cn->prepare($this->sql);
            $this->cm->bindParam(":purchaseUniqNo",$purchaseUniqNo);
            $this->cm->execute();
            $purchases = [];
            while($rows = $this->cm->fetch(PDO::FETCH_ASSOC)){
                $purchase = $this->IPurchase($rows);
                extract($rows);
                $purchase['productName'] = $productName;
                $purchase['productNametamil'] = $productNametamil;
                $purchase['productBarcode'] = $productBarcode;
                $purchase['productgst'] = $productgst;
                $purchase['productCgst'] = $productCgst;
                $purchase['productSgst'] = $productSgst;
                $purchase['unitType'] = $unitType;
                $purchase['gstvals'] = $gstvals;
                $purchase['itemprice'] = $itemprice;
                $purchase['purchaseSubtot'] = $itemsubtotal;
                $purchase['dis'] = $dis;
                $purchases[] = $purchase;
            }
            unset($this->sql,$this->cm,$rows);
            return $purchases;
        }
        private function _saveNewPurchase($IPurchase){
            $this->sql = "INSERT INTO purchases values(
                null,
                :purchaseUniqNo,
                :purchaseDate,
                :purchaseSupplier,
                :purchaseLotCode,
                :purchaseProduct,
                :purchaseQty,
                :purchasePrice,
                :purchaseSubtot,
                :purchaseInvoiceno,
                :cBy,
                :eBy,
                now(),
                now(),
                :purchaseothers,
                :status,
                'paid',
                '0',
                :purchasePaidRefno,
                :purcahseHaveExpi,
                :purchaseManDate,
                :purchaseExpdate,
                :purchaseDiscounttype,
                :purchaseDiscountval,
                :purchaseSPrice,
                :purchaseMrp,
                :purchaseGst,
                :purchaseCgst,
                :purchaseSgst,
                :purhcaseGstval,
                :purhcaseGsttotval
            )";
            $this->cm = $this->cn->prepare($this->sql);
            $isSave = $this->cm->execute($IPurchase);
            unset($this->sql,$this->cm);
            return $isSave;
        }

        public function SaveNewPurchase($IPurchase){
            $isSave = $this->_saveNewPurchase($IPurchase);
            if(!$isSave){
                return $this->res(false,"Error on Purcahse",[],500);
                exit();
            }
            $purcahseinfo = $this->_viewPurchaseinfo($IPurchase[':purchaseUniqNo']);
            return $this->res(true,"ok",$purcahseinfo,200);
            exit();
        }

        private function _removePurchaseItem($id){
            $this->sql = "DELETE FROM purchases where _id = :_id";
            $this->cm = $this->cn->prepare($this->sql);
            $this->cm->bindParam(":_id",$id);
            $isDelete = $this->cm->execute();
            unset($this->cm,$this->sql);
            return $isDelete;
        }

        public function DeletePurchaseItem($id,$purchaseUniqNo){
            $isDelete = (bool)$this->_removePurchaseItem($id);
            if(!$isDelete){
                return $this->res(false,"Error on removeing data",[],500);
                exit();
            }
            $purcahseinfo = $this->_viewPurchaseinfo($purchaseUniqNo);
            return $this->res(true,"ok",$purcahseinfo,200);
            exit();
        }

        private function _purchaseView($year){
            $stdate = "{$year}-01-01";
            $enddate = "{$year}-12-31";
            $this->sql = "SELECT 
            `purchaseUniqNo`,`purchaseDate`,`purchaseSupplier`,`purchaseLotCode`,`purchaseInvoiceno`,
            sum(purchaseSubtot) as totpurchase,
            sum(purhcaseGsttotval) as totgst,
            sup.supplierName,
            (select purchaseDiscountval as discount from purchases where purchaseUniqNo=pur.purchaseUniqNo limit 1) as dis
            from purchases as pur 
            inner join suppliers as sup on pur.purchaseSupplier=sup._id where pur.purchaseDate >= :stardate and pur.purchaseDate <= :enddate 
            group by `purchaseUniqNo`,`purchaseDate`,`purchaseSupplier`,`purchaseLotCode`,`purchaseInvoiceno` order by pur.cDate desc";
            $this->cm = $this->cn->prepare($this->sql);
            $this->cm->bindParam(":stardate",$stdate);
            $this->cm->bindParam(":enddate",$enddate);
            $this->cm->execute();
            $purchases = array();
            while($rows = $this->cm->fetch(PDO::FETCH_ASSOC)){
                extract($rows);
                $purchase['purchaseUniqNo'] = $purchaseUniqNo;
                $purchase['purchaseDate'] = $purchaseDate;
                $purchase['purchaseSupplier'] = $purchaseSupplier;
                $purchase['purchaseLotCode'] = $purchaseLotCode;
                $purchase['purchaseInvoiceno'] = $purchaseInvoiceno;
                $purchase['totpurchase'] = $totpurchase;
                $purchase['totgst'] = $totgst;
                $purchase['dis'] = $dis;
                $purchase['subtotal'] = (float)$totgst - (float)$dis;
                $purchase['supplierName'] = $supplierName;
                $purchase['purchaseDates'] = $this->datemethod($purchaseDate);
                $purchases[] = $purchase;
            }
            unset($this->cm,$this->sql,$rows);
            return $purchases;
            exit();
        }

        public function GetAllPurchase($year){
            $purchases = $this->_purchaseView($year);
            return $this->res(true,"ok",$purchases,200);
            exit();
        }

        private function countOfInvoices($stdate,$enddate){
            $this->sql = "SELECT purchaseUniqNo  from purchases where purchaseDate >= :stdate and purchaseDate <= :enddate group by purchaseUniqNo";
            $this->cm = $this->cn->prepare($this->sql);
            $params = array(
                ":stdate" => $stdate,
                ":enddate" => $enddate,
            );
            $this->cm->execute($params);
            $rows = $this->cm->fetch(PDO::FETCH_ASSOC);
            $cnt = (int)$this->cm->rowCount();
            unset($this->sql,$this->cm,$rows);
            return $cnt;
        }
        private function sumOfPurchase($stdate,$enddate){
            $this->sql = "SELECT sum(purchasePaidRefno)  as sumofp from  purchases where purchaseDate >= :stdate and purchaseDate <= :enddate";
            $this->cm = $this->cn->prepare($this->sql);
            $params = array(
                ":stdate" => $stdate,
                ":enddate" => $enddate,
            );
            $this->cm->execute($params);
            $rows = $this->cm->fetch(PDO::FETCH_ASSOC);
            $sumofpurchase = $rows['sumofp'];
            unset($this->sql,$this->cm,$rows);
            return $sumofpurchase;
        }

        public function DashboardcountsforPurchase($stdate,$enddate){
            $countOfPurchase = $this->countOfInvoices($stdate,$enddate);
            $sumOfPurchase = $this->sumOfPurchase($stdate,$enddate);
            $data = array(
                "sumOfPurchase" => $sumOfPurchase,
                "countOfPurhcase" => $countOfPurchase
            );
            return $data;
            exit;
        
        }

        private function _checkLastinvoice(){
            $this->sql = "SELECT COUNT(_id) as cnt from purchases where status = 0";
            $this->cm = $this->cn->prepare($this->sql);
            $this->cm->execute();
            $rows = $this->cm->fetch(PDO::FETCH_ASSOC);
            $cnt = (int)$rows['cnt'];
            unset($rows,$this->cm,$this->sql);
            return $cnt;
        }

        private function _loadNotClosed($purchaseUniqNo){
            $this->sql = "SELECT pu.*,pr.productName,uni.unitType FROM purchases as pu inner join 
            products as pr on pu.purchaseProduct = pr._id inner join productUnits as uni on pr.productUnitType = uni._id  where pu.purchaseUniqNo = :purchaseUniqNo";
            $this->cm = $this->cn->prepare($this->sql);
            $this->cm->bindParam(":purchaseUniqNo",$purchaseUniqNo);
            $this->cm->execute();
            $purchases = [];
            while($rows = $this->cm->fetch(PDO::FETCH_ASSOC)){
                $purchase = $this->IPurchase($rows);
                extract($rows);
                $purchase['productName'] = $productName;
                $purchase['unitType'] = $unitType;
                $purchases[] = $purchase;
            }
            unset($this->sql,$this->cm,$rows);
            return $purchases;
        }

        private function _getLastNotClosed(){
            $this->sql = "SELECT purchaseUniqNo from purchases where status = 0 order by _id desc limit 1";
            $this->cm = $this->cn->prepare($this->sql);
            $this->cm->execute();
            $rows = $this->cm->fetch(PDO::FETCH_ASSOC);
            $purchaseUniqNo = $rows['purchaseUniqNo'];
            unset($this->sql,$rows,$this->cm);
            return $purchaseUniqNo;
        }

        public function LoadNotClosedPuchase(){
            $cnt = (int)$this->_checkLastinvoice();
            if($cnt === 0){
                return $this->res(false,"not found",[],404);
                exit();
            }
            $purchaseUniqNo = $this->_getLastNotClosed();
            $purchase = $this->_viewPurchaseinfo($purchaseUniqNo); 
            return $this->res(true,"ok",$purchase,200);
            exit();
        }

        public function ViewPurchaseInvoice($purchaseUniqNo){
            $purchase = $this->_viewPurchaseinfo($purchaseUniqNo); 
            return $this->res(true,"ok",$purchase,200);
            exit();
        }

        private function _savePurchaseBill($Isave){
            $this->sql = "UPDATE purchases 
            set 
            purchaseothers = :purchaseothers,
            purchaseType = :purchaseType,
            purchasePaid = :purchasePaid,
            purchaseDiscounttype = :purchaseDiscounttype,
            purchaseDiscountval = :purchaseDiscountval,
            status = 1 
            where purchaseUniqNo = :purchaseUniqNo";
            $this->cm = $this->cn->prepare($this->sql);
            $isSave = $this->cm->execute($Isave);
            unset($this->sql,$this->cm);
            return $isSave;
        }
        public function SaveNewBill($Isave){
            $isSave = $this->_savePurchaseBill($Isave);
            if(!$isSave){
                return $this->res(false,"error on update",[],500);
                exit();
            }
            return $this->res(true,"ok",[],200);
            exit();
        }

        private function _enableEditBill($purchaseUniqNo){
            $this->sql = "UPDATE purchases set status = 0 where purchaseUniqNo = :purchaseUniqNo";
            $this->cm = $this->cn->prepare($this->sql);
            $this->cm->bindParam(":purchaseUniqNo",$purchaseUniqNo);
            $isUpdated = $this->cm->execute();
            unset($this->sql,$this->cm);
            return $isUpdated;
        }

        public function EnableEditBill($purchaseUniqNo){
            $cnt = $this->_checkLastinvoice();
            if($cnt !== 0){
                return $this->res(false,"Already One Ivoice Opend please Close Invoice And Try again",[],409);
                exit();
            }
            $isUpdate = $this->_enableEditBill($purchaseUniqNo);
            if(!$isUpdate){
                return $this->res(false,"Error on Update",[],500);
                exit();
            }
            return $this->res(true,"ok",[],200);
            exit();
        }

        private function _updateproduct($IUpdate){
            $this->sql = "UPDATE purchases SET 
            purchaseQty = :purchaseQty,
            purchasePrice = :purchasePrice,
            purchaseSubtot = :purchaseSubtot,
            purcahseHaveExpi = :purcahseHaveExpi,
            purchaseManDate = :purchaseManDate,
            purchaseExpdate = :purchaseExpdate,
            purchaseSPrice = :purchaseSPrice,
            purchasePaidRefno = :purchasePaidRefno,
            purchaseMrp = :purchaseMrp,
            purchaseGst = :purchaseGst,
            purchaseCgst = :purchaseCgst,
            purchaseSgst = :purchaseSgst,
            purhcaseGstval = :purhcaseGstval,
            purhcaseGsttotval = :purhcaseGsttotval  
            where 
            _id = :id";
            $this->cm = $this->cn->prepare($this->sql);
            $isUpdated = $this->cm->execute($IUpdate);
            unset($this->sql,$this->cm);
            return $isUpdated;
        }

        public function UpdateProduct($IUpdate,$purchaseUniqNo){
            $isUpdated = $this->_updateproduct($IUpdate);
            if(!$isUpdated){
                $this->res(false,"Error on update",[],500);
                exit();
            }
            $purchase = $this->_viewPurchaseinfo($purchaseUniqNo); 
            return $this->res(true,"ok",$purchase,200);
            exit();

        }

        private function _itempurchasehistory($purchaseProduct){
            $this->sql = "SELECT pur.*,pr.productBarcode,pr.productName,pr.productNametamil,su.supplierName FROM purchases as pur inner join products as pr on 
            pur.purchaseProduct = pr._id inner join suppliers as su on pur.purchaseSupplier = su._id  where purchaseProduct = :purchaseProduct";
            $this->cm = $this->cn->prepare($this->sql);
            $this->cm->bindParam(":purchaseProduct",$purchaseProduct);
            $this->cm->execute();
            $purchases = [];
            while($rows = $this->cm->fetch(PDO::FETCH_ASSOC)){
                $purchase = $this->IPurchase($rows);
                $purchase['supplierName'] = $rows['supplierName'];
                $purchase['productBarcode'] = $rows['productBarcode'];
                $purchase['productName'] = $rows['productName'];
                $purchase['productNametamil'] = $rows['productNametamil'];
                $purchases[] = $purchase;
            }
            unset($rows,$this->cm,$this->sql);
            return $purchases;
        }

        public function ItemPurhcaseHistory($purchaseProduct){
            $purchases = $this->_itempurchasehistory($purchaseProduct);
            return $this->res(true,"ok",$purchases,200);
            exit();
        }

        
    }
?>