<?php 
    include_once 'mac.php';
    class SupplierController extends mac{
        private $cn,$cm,$sql;
        function __construct($db)
        {
            $this->cn = $db;
        }

        private function statusToString($status){
            $statuslist = ['InActive','Active'];
            $statusTxt = $statuslist[$status];
            return $statusTxt;
        }
        private function ISupplier($rows){
            extract($rows);
            $cols = [];
            $cols['_id'] = $_id;
            $cols['supplierName'] = $supplierName;
            $cols['supplierCode'] = $supplierCode;
            $cols['supplierPhone'] = $supplierPhone;
            $cols['supplierAddress'] = $supplierAddress;
            $cols['supplierGstNo'] = $supplierGstNo;
            $cols['supplierPanNo'] = $supplierPanNo;
            $cols['cBy'] = $cBy;
            $cols['eBy'] = $eBy;
            $cols['cDate'] = $cDate;
            $cols['eDate'] = $eDate;
            $cols['status'] = $status;

            //user defined
            $cols['statusTxt'] = $this->statusToString($status);
            return $cols;
        }

        private function checkSupplierCode($supplierCode){
            $this->sql = "SELECT COUNT(supplierCode) as cnt from suppliers where supplierCode = :supplierCode";
            $this->cm = $this->cn->prepare($this->sql);
            $this->cm->bindParam(":supplierCode",$supplierCode);
            $this->cm->execute();
            $rows = $this->cm->fetch(PDO::FETCH_ASSOC);
            $cnt = (int)$rows['cnt'];
            unset($this->cm,$this->sql,$rows);
            return $cnt;
            exit();
        }

        private function _getAllSuppliers(){
            $this->sql = "SELECT *FROM suppliers order by supplierName asc";
            $this->cm = $this->cn->prepare($this->sql);
            $this->cm->execute();
            $suppliers = [];
            while($rows = $this->cm->fetch(PDO::FETCH_ASSOC)){
                $supplier = $this->ISupplier($rows);
                $suppliers[] = $supplier;
            }
            unset($this->sql,$this->cm,$rows);
            return $suppliers;
        }

        public function GetAllSuppliers(){
            $suppliers = $this->_getAllSuppliers();
            return $this->res(true,"ok",$suppliers,200);
            exit();

        }

        private function _saveNewSupplier($ISupplier){
            $this->sql = "INSERT INTO suppliers values(
                null,
                :supplierName,
                :supplierCode,
                :supplierPhone,
                :supplierAddress,
                :supplierGstNo,
                :supplierPanNo,
                :cBy,
                :eBy,
                now(),
                now(),
                :status
            )";
            $this->cm = $this->cn->prepare($this->sql);
            $isSave = $this->cm->execute($ISupplier);
            unset($this->cm,$this->sql);
            return $isSave;
        }

        public function SaveNewSupplier($ISupplier){
            $supplierCode = strtolower($ISupplier[':supplierCode']);
            $ISupplier['supplierCode'] = $supplierCode;
            //check dublicate 
            $cnt = (int)$this->checkSupplierCode($supplierCode);
            if($cnt !== 0){
                return $this->res(false,"This {$supplierCode} Already Found",[],409);
                exit();
            }
            $isSave = $this->_saveNewSupplier($ISupplier);
            if(!$isSave){
                return $this->res(false,"Error on Saving Data",[],500);
                exit;
            }
            $suppliers = $this->_getAllSuppliers();
            return $this->res(true,"ok",$suppliers,200);
            exit();
        }

        private function cntSuppliers(){
            $this->sql = "SELECT COUNT(_id) as cnt from suppliers";
            $this->cm = $this->cn->prepare($this->sql);
            $this->cm->execute();
            $rows = $this->cm->fetch(PDO::FETCH_ASSOC);
            $cnt = (int)$rows['cnt'];
            unset($this->sql,$this->cm,$rows);
            return $cnt;
        }

        public function DashboardCountForSuppliers(){
            $cntOfSuppliers = $this->cntSuppliers();
            $balance = 0;
            $data = array(
                "countOfSuppliers" => $cntOfSuppliers,
                "balanceOfSuppliers" => $balance
            );

           return $data;
           exit();
        }
    
        //new code 
        private function _getsupplierinfo($id){
            $this->sql = "SELECT *FROM suppliers where _id = :id";
            $this->cm = $this->cn->prepare($this->sql);
            $this->cm->bindParam(":id",$id);
            $this->cm->execute();
            $rows = $this->cm->fetch(PDO::FETCH_ASSOC);
            $supplier = $this->ISupplier($rows);
            unset($rows,$this->cm,$this->sql);
            return $supplier;
        }
        public function GetSupplierInfo($id){
            $supplier = $this->_getsupplierinfo($id);
            return $this->res(true,"ok",$supplier,200);
            exit();
        }
        private function _updateSupplier($ISupplier){
            $this->sql = "UPDATE suppliers 
            set 
            supplierName = :supplierName,
            supplierPhone = :supplierPhone,
            supplierAddress = :supplierAddress,
            supplierGstNo = :supplierGstNo,
            supplierPanNo = :supplierPanNo,
            eBy = :eBy,
            eDate = now() 
            where 
            _id = :id";
            $this->cm = $this->cn->prepare($this->sql);
            $isUpdate = $this->cm->execute($ISupplier);
            unset($this->sql,$this->cm);
            return $isUpdate;
        }

        public function UpdateSupplier($ISupplier){
            $isUpdate = $this->_updateSupplier($ISupplier);
            if(!$isUpdate){
                return $this->res(false,"Error on update",[],500);
                exit();
            }
            $suppliers = $this->_getAllSuppliers();
            return $this->res(true,"ok",$suppliers,200);
            exit();
        }
    
    }
?>