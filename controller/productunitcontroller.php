<?php 
    include_once 'mac.php';
    class ProductUnitController extends mac{
        private $cn;
        private $cm;
        private $sql;

        function __construct($db)
        {
            $this->cn = $db;
        }

        private function StatusConvertToString($status){
            $statuslist = ['InActive','Active'];
            $statusTxt = $statuslist[$status];
            return $statusTxt;
        }

        private function IProductUnit($rows){
            extract($rows);
            $cols = [];
            $cols['_id'] = $_id;
            $cols['unitType'] = $unitType;
            $cols['status'] = $status;
            $cols['cBy'] = $cBy;
            $cols['eBy'] = $eBy;
            $cols['cDate'] = $cDate;
            $cols['eDate'] = $eDate;
            //out of table
            $cols['statusTxt'] = $this->StatusConvertToString($status);
            return $cols;
        }

        private function _checkUnit($unitType){
            $this->sql = "SELECT COUNT(unitType) as cnt from productUnits where unitType = :unitType";
            $this->cm = $this->cn->prepare($this->sql);
            $this->cm->bindParam(":unitType",$unitType);
            $this->cm->execute();
            $rows = $this->cm->fetch(PDO::FETCH_ASSOC);
            $cnt = (int)$rows['cnt'];
            unset($this->sql,$this->cm,$rows);
            return $cnt;
        }
        private function _getAllUnit(){
            $this->sql = "SELECT *FROM productUnits";
            $this->cm = $this->cn->prepare($this->sql);
            $this->cm->execute();
            $productunits = [];
            while($rows = $this->cm->fetch(PDO::FETCH_ASSOC)){
                $productunit = $this->IProductUnit($rows);
                $productunits[] = $productunit;
            }
            unset($this->sql,$this->cm,$rows);
            return $productunits;
        }
        public function GetAllUnits(){
            $productunits = $this->_getAllUnit();
            return $this->res(true,"ok",$productunits,200);
            exit();
        }

        private function _saveUnit($IProductUnit){
            $this->sql = "INSERT INTO productUnits values(
            null,
            :unitType,
            :status,
            :cBy,
            :eBy,
            now(),
            now()
            )";
            $this->cm = $this->cn->prepare($this->sql);
            $isSave = $this->cm->execute($IProductUnit);
            unset($this->sql,$this->cm);
            return $isSave;
        }

        public function SaveNewUnit($IProductUnit){
            $unitType = strtolower($IProductUnit[':unitType']);
            $IProductUnit[':unitType'] = $unitType;
            $cnt = (int)$this->_checkUnit($unitType);
            if($cnt !== 0){
                return $this->res(false,"Already This Unit Found",[],409);
                exit();
            }
            $isSave = (bool)$this->_saveUnit($IProductUnit);
            if(!$isSave){
                return $this->res(false,"Error On Saving Data",[],500);
                exit();
            }
            $productunits = $this->_getAllUnit();
            return $this->res(true,"ok",$productunits,201);
            exit();
        }
    }
?>