<?php 
    include_once 'mac.php';
    class CustomerController extends mac{
        private $cn,$sql,$cm;

        function __construct($db)
        {
            $this->cn = $db;
        }

        private function ICustomer($rows){
            extract($rows);
            $cols = [];
            $cols['_id'] = $_id;
            $cols['customerName'] = $customerName;
            $cols['customerPhone'] = $customerPhone;
            $cols['customerAddress'] = $customerAddress;
            return $cols;
        }

        private function _getAllCustomers(){
            $this->sql = "SELECT cu.*,IFNULL(gp.gpoints,0) as getpoints,IFNULL(up.upoints,0) as usepoints,
IFNULL(gp.gpoints,0) - IFNULL(up.upoints,0) as balpoints 
FROM customers as cu left join ( select customerPhone,sum(salesInvoicePointGet) gpoints from customerSalesPoints group by  customerPhone) as gp on cu.customerPhone = gp.customerPhone
left join (select customerPhone,sum(usedPoints) as upoints from customerSalesPointUses group by customerPhone) as up 
on cu.customerPhone = up.customerPhone";
            $this->cm = $this->cn->prepare($this->sql);
            $this->cm->execute();
            $customers = [];
            while($rows = $this->cm->fetch(PDO::FETCH_ASSOC)){
                $customer = $this->ICustomer($rows);
                extract($rows);
                $customer['getpoints'] = $getpoints;
                $customer['usedpoints'] = $usepoints;
                $customer['bpoint'] = $balpoints;
                $customers[] = $customer;
            }
            unset($this->sql,$rows,$this->cm);
            return $customers;
        }

        private function _getCustomerInfo($customerPhone){
            $this->sql = "SELECT customer.*,
            IFNULL(spoint.getpoints,0) as getpoints,
            IFNULL(upoint.usedpoint,0) as usedpoints, 
            IFNULL(spoint.getpoints,0) -  IFNULL(upoint.usedpoint,0) as bpoint
            FROM customers as customer
            left join 
            (select customerPhone,IFNULL(sum(salesInvoicePointGet),0) as getpoints from customerSalesPoints group by customerPhone) as spoint 
            on customer.customerPhone = spoint .customerPhone 
            left join 
            (select customerPhone,IFNULL(sum(usedPoints),0) as usedpoint from customerSalesPointUses group by customerPhone) as upoint 
            on customer.customerPhone = upoint.customerPhone 
            where customer.customerPhone = :customerPhone";
            
            $this->cm = $this->cn->prepare($this->sql);
            $this->cm->bindParam(":customerPhone",$customerPhone);
            $this->cm->execute();
            $customer = [];
            $rows = $this->cm->fetch(PDO::FETCH_ASSOC);
            $customer = $this->ICustomer($rows);
            extract($rows);
            $customer['getpoints'] = $getpoints;
            $customer['usedpoints'] = $usedpoints;
            $customer['bpoint'] = $bpoint;
            unset($this->sql,$rows,$this->cm);
            return $customer;
        }


        public function GetAllCustomers(){
            $customers = $this->_getAllCustomers();
            return $this->res(true,"ok",$customers,200);
            exit();
        }

        public function GetCustomer($customerPhone){
            //check customer
            $cnt = $this->_checkCustomerPhoneNumber($customerPhone);
            if($cnt === 0){
                return $this->res(false,"no data found",[],404);
                exit();
            }
            $customer = $this->_getCustomerInfo($customerPhone);
            return $this->res(true,"ok",$customer,200);
            exit();
        }

        private function _checkCustomerPhoneNumber($customerPhone){
            $this->sql = "SELECT COUNT(customerPhone) as cnt from customers where customerPhone = :customerPhone";
            $this->cm = $this->cn->prepare($this->sql);
            $this->cm->bindParam(":customerPhone",$customerPhone);
            $this->cm->execute();
            $rows = $this->cm->fetch(PDO::FETCH_ASSOC);
            $cnt = (int)$rows['cnt'];
            unset($this->sql,$this->cm,$rows);
            return $cnt;
        }

        private function _saveNewCustomer($ICustomer){
            $this->sql = 'INSERT INTO customers value(null,:customerName,:customerPhone,:customerAddress)';
            $this->cm = $this->cn->prepare($this->sql);
            $isSave = $this->cm->execute($ICustomer);
            unset($this->cm,$this->sql);
            return $isSave;
        }

        public function SaveNewCustomer($ICustomer){

            //check dublicates

            $cnt = (int)$this->_checkCustomerPhoneNumber($ICustomer[':customerPhone']);
            if($cnt !== 0){
                return $this->res(false,"Dublicate found",[],409); exit();
            }
            //check is saved
            $isSave = (bool)$this->_saveNewCustomer($ICustomer);
            if(!$isSave){
                return $this->res(false,"Error on Save New Customer",[],500);
                exit();
            }
            //get all customers list
            $customers = $this->_getAllCustomers();
            return $this->res(true,"ok",$customers,200);
            exit();
        }
        private function _checkphonenumber($params){
            $this->sql = "SELECT COUNT(customerPhone) as cnt from customers where customerPhone = :customerPhone and _id <> :id";
            $this->cm = $this->cn->prepare($this->sql);
            $this->cm->execute($params);
            $rows = $this->cm->fetch(PDO::FETCH_ASSOC);
            $cnt = (int)$rows['cnt'];
            unset($this->sql,$rows,$this->cm);
            return $cnt;

        }
        private function _updatecustomer($Icustomer){
            $this->sql = "UPDATE customers set customerName = :customerName,
            customerAddress = :customerAddress 
            where _id = :id limit 1";
            $this->cm = $this->cn->prepare($this->sql);
            $isUpdated = $this->cm->execute($Icustomer);
            unset($this->sql,$this->cm);
            return $isUpdated;
        }
        
        public function UpdateCustomer($ICustomer){
            // $params = array(
            //     ":customerPhone" => $ICustomer[':customerPhone'],
            //     ":id" => $ICustomer[':id'],
            // );
            // $cnt = (int)$this->_checkphonenumber($params);
            // if($cnt !== 0){
            //     return $this->res(false,"Already This Mobile Number Registred with another user",[],409);
            //     exit;
            // }
            $isUpdated = $this->_updatecustomer($ICustomer);
            if(!$isUpdated){
                return $this->res(false,"Error on Update Customer Data",[],500);
                exit;
            }
            $customers = $this->_getAllCustomers();
            return $this->res(true,"ok",$customers,200);
            exit;
        }
    }
?>