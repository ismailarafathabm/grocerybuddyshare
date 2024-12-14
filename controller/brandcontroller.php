<?php 
    include_once 'mac.php';
    class BrandController extends mac{
        private $cn;
        private $cm;
        private $sql;
        
        function __construct($db)
        {
            $this->cn = $db;
        }
        private function StatusToString($status){
            $statuslist = ['InActive','Active'];
            $statusTxt = $statuslist[$status];
            return $statusTxt;
        }
        private function IBrand($rows){
            extract($rows);
            $cols = [];
            $cols['_id'] = $_id;
            $cols['brandName'] = $brandName;
            $cols['status'] = $status;
            $cols['cBy'] = $cBy;
            $cols['eBy'] = $eBy;
            $cols['cDate'] = $cDate;
            $cols['eDate'] = $eDate;
            //user defined
            $cols['statusTxt'] = $this->StatusToString($status);
            return $cols;
        }
        private function _getAllBrands(){
            $brands = [];
            $this->sql = "SELECT *FROM brands order by brandName asc";
            $this->cm = $this->cn->prepare($this->sql);
            $this->cm->execute();
            while($rows = $this->cm->fetch(PDO::FETCH_ASSOC)){
                $brand = $this->IBrand($rows);
                $brands[] = $brand;
            }
            unset($this->cm,$this->sql,$rows);
            return $brands;
        }
        public function GetAllBrands(){
            $brands = $this->_getAllBrands();
            return $this->res(true,"ok",$brands,200);
            exit();
        }
        private function _checkBrand($brandName){
            $this->sql = "SELECT COUNT(brandName) as cnt from brands where brandName = :brandName";
            $this->cm = $this->cn->prepare($this->sql);
            $this->cm->bindParam(":brandName",$brandName);
            $this->cm->execute();
            $rows = $this->cm->fetch(PDO::FETCH_ASSOC);
            $cnt = (int)$rows['cnt'];
            unset($this->sql,$this->cm,$rows);
            return $cnt;
        }
        private function _saveNewBrand($IBrand){
            $this->sql = "INSERT INTO brands values(
                null,
                :brandName,
                :status,
                :cBy,
                :eBy,
                now(),
                now())";
            $this->cm = $this->cn->prepare($this->sql);
            $isSave = $this->cm->execute($IBrand);
            unset($this->sql,$this->cm);
            return $isSave;
        }

        public function SaveNewBrand($IBrand){
            $brandName = strtolower($IBrand[':brandName']);
            $IBrand[':brandName'] = $brandName;
            $cnt = (int)$this->_checkBrand($brandName);
            if($cnt !== 0){
                return $this->res(false,"Already {$brandName} found",[],409);
                exit();
            }
            $isSave = $this->_saveNewBrand($IBrand);
            if(!$isSave){
                return $this->res(false,"Error on saving data",[],500);
                exit();
            }
            $brands = $this->_getAllBrands();
            return $this->res(true,"ok",$brands,200);
            exit();
        }


    
    }
?>