<?php 
    include_once 'mac.php';
    class ProductTypeController extends mac{
        private $cm;
        private $sql;
        private $cn;

        function __construct($db)
        {
            $this->cn = $db;
        }
        private function ProductTypeStatusToString($status){
            $statusLists  = ['InActive','Active'];
            $statusTxt = $statusLists[$status];
            return $statusTxt;
        }
        private function IProductTypes($rows){
            extract($rows);
            $cols = [];
            $cols['_id'] = $_id;
            $cols['productTypeName'] = $productTypeName;
            $cols['status'] = $status;
            $cols['cBy'] = $cby;
            $cols['eBy'] = $eby;
            $cols['cDate'] = $cDate;
            $cols['eDate'] = $eDate;
            //-out of table column
            $cols['statusTxt'] = $this->ProductTypeStatusToString($status);
            return $cols;
        }
       

        private function _getAllProductTypes(){
            $this->sql = "SELECT *FROM productTypes";
            $this->cm = $this->cn->prepare($this->sql);
            $this->cm->execute();
            $productTypes = [];
            while($rows = $this->cm->fetch(PDO::FETCH_ASSOC)){
                $productType = $this->IProductTypes($rows);
                $productTypes[] = $productType;
            }
            unset($this->sql,$this->cm,$rows);
            return $productTypes;
            exit();

        }

        public function GetAllProductTYpes(){
            $producttypes = $this->_getAllProductTypes();
            return $this->res(true,"ok",$producttypes,200);
            exit();
        }

        private function _checkProductType($productTypeName){
            $this->sql = "SELECT COUNT(productTypeName) as cnt from productTypes where productTypeName = :productTypeName";
            $this->cm = $this->cn->prepare($this->sql);
            $this->cm->bindParam(":productTypeName",$productTypeName);
            $this->cm->execute();
            $rows = $this->cm->fetch(PDO::FETCH_ASSOC);
            $cnt = (int)$rows['cnt'];
            unset($this->cm,$this->sql,$rows);
            return $cnt;
        }

        private function _SaveProductType($IProductType){
            $this->sql = "INSERT INTO productTypes values(
            null,
            :productTypeName,
            :status,
            :cBy,
            :eBy,
            now(),
            now()
            )";
            $this->cm = $this->cn->prepare($this->sql);
            $isSave = $this->cm->execute($IProductType) ? true : false;
            unset($this->cm,$this->sql);
            return $isSave;
        }

        public function SaveNewProductType($IProductType){
            $productTypeName = strtolower($IProductType[':productTypeName']);
            $IProductType[':productTypeName'] = $productTypeName;
            $cnt = (int)$this->_checkProductType($productTypeName);
            if($cnt !== 0){
                return $this->res(false,"Already This Product Type Found",[],409);
                exit();
            }

            $isSave = $this->_SaveProductType($IProductType);
            if(!$isSave){
                return $this->res(false,"Error On Saving Data",[],500);
                exit();
            }

            $producttypes = $this->_getAllProductTypes();
            return $this->res(true,"Saved",$producttypes,201);
            exit();
        }

        private function _changeStatusOfType($id,$status){
            $this->sql = "UPDATE productTypes set status = :status where _id = :_id";
            $this->cm = $this->cn->prepare($this->sql);
            $this->cm->bindParam(":status",$status);
            $this->cm->bindParam(":_id",$id);
            $isUpdate = $this->cm->execute();
            unset($this->sql,$this->cm);
            return $isUpdate;
        }

        public function UpdateProductTypeStatus($id,$status){
            $isUpdate = $this->_changeStatusOfType($id,$status);
            if(!$isUpdate){
                return $this->res(false,"Error or update",[],500);
                exit();
            }

            $producttypes = $this->_getAllProductTypes();
            return $this->res(true,"Saved",$producttypes,200);
            exit();
        }

        private function _checkProductTypeInProducts($id){
            $this->sql = "SELECT COUNT(productType) as cnt from products where productType = :productType";
            $this->cm = $this->cn->prepare($this->sql);
            $this->cm->bindParam(":productType",$id);
            $this->cm->execute();
            $rows = $this->cm->fetch(PDO::FETCH_ASSOC);
            $cnt = (int)$rows['cnt'];
            unset($this->sql,$rows,$this->cm);
            return $cnt;
        }
        private function _deleteProductType($id){
            $this->sql = "DELETE FROM productTypes where _id = :_id limit 1";
            $this->cm = $this->cn->prepare($this->sql);
            $this->cm->bindParam(":_id",$id);
            $isDelete =  $this->cm->execute();
            unset($this->cm,$this->sql);
            return $isDelete;
        }

        public function DeleteProductType($id){
            $cnt = (int)$this->_checkProductTypeInProducts($id);
            if($cnt !== 0){
                return $this->res(false,"This Product Type is Assigned with some product so we could not delete",[],409);
                exit();
            }
            $isDelete = (bool)$this->_deleteProductType($id);
            if(!$isDelete){ 
                return $this->res(false,"Error on Delete Data",[],500);
                exit();
            }
            $producttypes = $this->_getAllProductTypes();
            return $this->res(true,"Saved",$producttypes,200);
            exit();
        }
    }
?>