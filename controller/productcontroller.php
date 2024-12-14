<?php
//composer require picqer/php-barcode-generator
// use Picqer\Barcode\BarcodeGeneratorPNG;

// $generator = new BarcodeGeneratorPNG();
// $barcode = $generator->getBarcode($item['barcode'], $generator::TYPE_CODE_128);
// file_put_contents("barcodes/{$item['barcode']}.png", $barcode);
include_once 'mac.php';
class ProductController extends mac
{
    private $cn;
    private $cm;
    private $sql;

    function __construct($db)
    {
        $this->cn = $db;
    }

    private function StatusToString($status)
    {
        $statusList = ['InAcive', 'Active'];
        $statusTxt = $statusList[$status];
        return $statusTxt;
    }

    private function IProduct($rows)
    {
        $cols = [];
        extract($rows);
        $cols['_id'] = $_id;
        $cols['productBarcode'] = $productBarcode;
        $cols['productBrand'] = $productBrand;
        $cols['productType'] = $productType;
        $cols['productName'] = $productName;
        $cols['productSku'] = $productSku;
        $cols['productUnitType'] = $productUnitType;
        $cols['productStatus'] = $productStatus;
        $cols['productMinQty'] = $productMinQty;
        $cols['productPPrice'] = $productPPrice;
        $cols['productSPrice'] = $productSPrice;
        $cols['productMrp'] = $productMrp;
        $cols['productCgst'] = $productCgst;
        $cols['productSgst'] = $productSgst;
        $cols['productDiscount'] = $productDiscount;
        $cols['productLocationRack'] = $productLocationRack;
        $cols['cBy'] = $cBy;
        $cols['eBy'] = $eBy;
        $cols['peBy'] = $peBy;
        $cols['cDate'] = $cDate;
        $cols['eDate'] = $eDate;
        $cols['cpUpdate'] = $cpUpdate;
        $cols['status'] = $productStatus;
        $cols['productNametamil'] = $productNametamil;
        $cols['productgst'] = $productgst;
        //user defined
        $cols['statusTxt'] = $this->StatusToString($productStatus);
        return $cols;
    }

    private function _getAllProducts()
    {
        $products = [];
        $this->sql = "SELECT pr.*,
        brand.brandName as productBrandName,
        prtype.productTypeName as productTypeNamedisp,
        prunit.unitType as productUnitTypeName,
        IFNULL(pur.pqty,0) as pqty,
        IFNULL(sals.sqty,0) as sqty,
        IFNULL(pur.totpurcahse,0) as totpurcahseval,
        IFNULL(sals.totsales,0) as totsalesval,
        IFNULL(sals.salesprofit,0) as salesprofitval,
        (IFNULL(pur.pqty,0) - IFNULL(sals.sqty,0)) as balanceqty
         FROM products as pr inner join 
        brands as brand on pr.productBrand = brand._id inner join 
        productTypes as prtype on pr.productType = prtype._id inner join 
        productUnits as prunit on pr.productUnitType = prunit._id left join
        (select purchaseProduct,IFNULL(sum(purchaseQty),0) as pqty,IFNULL(sum(purhcaseGsttotval),0) as totpurcahse from purchases group by purchaseProduct) as pur 
        on pr._id = pur.purchaseProduct left join 
        (select salesProduct,IFNULL(sum(salesQty),0) as sqty,IFNULL(sum(salessubtot),0) as totsales,IFNULL(salesSPrice -salesPPrice ,0) as salesprofit from  sales group by salesProduct) as sals
        on pr._id = sals.salesProduct";
        $this->cm = $this->cn->prepare($this->sql);
        $this->cm->execute();
        while($rows = $this->cm->fetch(PDO::FETCH_ASSOC)){
            $product = $this->IProduct($rows);
            extract($rows);
            $product['pqty'] = (float)$pqty;
            $product['sqty'] = (float)$sqty;
            $product['balqty'] = (float)$balanceqty;
            $product['productBrandName'] = $productBrandName;
            $product['productTypeNamedisp'] = $productTypeNamedisp;
            $product['productUnitTypeName'] = $productUnitTypeName;
            $product['totpurcahseval'] = $totpurcahseval;
            $product['totsalesval'] = $totsalesval;
            $product['salesprofitval'] = (float)$salesprofitval * (float)$sqty;
           
            $products[] = $product;
        }
        unset($this->cm,$this->sql,$rows);
        return $products;
    }
    public function getproductinfobyid($id)
    {
        $product = [];
        $this->sql = "SELECT pr.*,
        brand.brandName as productBrandName,
        prtype.productTypeName as productTypeNamedisp,
        prunit.unitType as productUnitTypeName,
        IFNULL(pur.pqty,0) as pqty,
        IFNULL(sals.sqty,0) as sqty,
        (IFNULL(pur.pqty,0) - IFNULL(sals.sqty,0)) as balanceqty
         FROM products as pr inner join 
        brands as brand on pr.productBrand = brand._id inner join 
        productTypes as prtype on pr.productType = prtype._id inner join 
        productUnits as prunit on pr.productUnitType = prunit._id left join
        (select purchaseProduct,IFNULL(sum(purchaseQty),0) as pqty from purchases group by purchaseProduct) as pur 
        on pr._id = pur.purchaseProduct left join 
        (select salesProduct,IFNULL(sum(salesQty),0) as sqty from  sales group by salesProduct) as sals
        on pr._id = sals.salesProduct where pr._id = :_id";
        $this->cm = $this->cn->prepare($this->sql);
        $this->cm->bindParam(":_id",$id);
        $this->cm->execute();
        while($rows = $this->cm->fetch(PDO::FETCH_ASSOC)){
            $product = $this->IProduct($rows);
            extract($rows);
            $product['pqty'] = (float)$pqty;
            $product['sqty'] = (float)$sqty;
            $product['balqty'] = (float)$balanceqty;
            $product['productBrandName'] = $productBrandName;
            $product['productTypeNamedisp'] = $productTypeNamedisp;
            $product['productUnitTypeName'] = $productUnitTypeName;
            $products[] = $product;
        }
        unset($this->cm,$this->sql,$rows);
        return $product;
    }
    public  function GetAllProducts()
    {
        $products = $this->_getAllProducts();
        return $this->res(true, "ok", $products, 200);
        exit();
    }
    //check productbarcode

    private function _checkproductbarcode($productBarcode): int
    {
        $this->sql = "SELECT COUNT(productBarcode) as cnt from products where productBarcode = :productBarcode";
        $this->cm = $this->cn->prepare($this->sql);
        $this->cm->bindParam(":productBarcode", $productBarcode);
        $this->cm->execute();
        $rows = $this->cm->fetch(PDO::FETCH_ASSOC);
        $cnt = (int)$rows['cnt'];
        unset($this->sql, $this->cm, $rows);
        return $cnt;
    }

    private function _gennewbarcode(){
        $isTokenok = false;
        $newtoken =  $newtoken = $this->tokenx(4);
        while($isTokenok){
            $cnt = (int)$this->_checkproductbarcode($newtoken);
            if($cnt === 0){
                $isTokenok = true;
            }else{
                $newtoken = $this->token(4);
            }
        }
        return $newtoken;
    }

    public function newBarcode(){
        $productBarcode = $this->_gennewbarcode();
        $data = array("productBarcode"=> $productBarcode);
        return $this->res(true,"ok",$data,200);
        exit();
    }
    private function _saveNewProduct($IProduct)
    {
        $this->sql = "INSERT INTO products values(
                null,
                :productBarcode,
                :productBrand,
                :productType,
                :productName,
                :productSku,
                :productUnitType,
                :productStatus,
                :productMinQty,
                :productPPrice,
                :productSPrice,
                :productMrp,
                :productCgst,
                :productSgst,
                :productDiscount,
                :productLocationRack,
                :cBy,
                :eBy,
                :peBy,
                now(),
                now(),
                now(),
                :productNametamil,
                :productgst
                )";
        $this->cm = $this->cn->prepare($this->sql);
        $isSave = $this->cm->execute($IProduct);
        unset($this->cm, $this->sql, $rows);
        return $isSave;
    }

    public function saveNewProductInPurchase($IProduct){
        $cnt = (int)$this->_checkproductbarcode($IProduct[':productBarcode']);
        if($cnt !== 0){
            return 0;
        }
        $isSave = $this->_saveNewProduct($IProduct);
        if(!$isSave){
            return -1;
        }
        $lastid = $this->cn->lastInsertId();
        return $lastid;
    }
    private function _saveNewPurchase($IPurchase)
    {
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
                1
            )";
        $this->cm = $this->cn->prepare($this->sql);
        $isSave = $this->cm->execute($IPurchase);
        unset($this->sql, $this->cm);
        return $isSave;
    }
    public function SaveNewProduct($IProduct, $isNeedNewPurchase, $IPurchase)
    {
        $productBarcode = $IProduct[':productBarcode'];
        $cnt = (int)$this->_checkproductbarcode($productBarcode);
        if ($cnt !== 0) {
            return $this->res(false, "this Barcode $productBarcode found", [], 409);
            exit();
        }
        $isSave = $this->_saveNewProduct($IProduct);
        if (!$isSave) {
            return $this->res(false, "Error on saveing data", [], 500);
            exit();
        }
        if ($isNeedNewPurchase) {
            $id = $this->cn->lastInsertId();
            $IPurchase[":purchaseProduct"] = $id;
            $this->_saveNewPurchase($IPurchase);
        }
        $products  = $this->_getAllProducts();
        return $this->res(true, "Saved", $products, 201);
        exit();
    }

    private function _getProductInfo($productBarcode){
        $this->sql = "SELECT *FROM products where productBarcode = :productBarcode";
        $this->cm = $this->cn->prepare($this->sql);
        $this->cm->bindParam(":productBarcode",$productBarcode);
        $this->cm->execute();
        $rows = $this->cm->fetch(PDO::FETCH_ASSOC);
        $product = $this->IProduct($rows);
        unset($this->sql,$this->cm,$rows);
        return $product;
    }

    public function GetProductInfo($productBarcode){
        $cnt = (int)$this->_checkproductbarcode($productBarcode);
        if($cnt === 0){
            return $this->res(false,"No Data Found",[],404);
            exit;
        }
        $product = $this->_getProductInfo($productBarcode);
        return $this->res(true,"ok",$product,200);
        exit();
    }

    private function _updateProductStatus($id, $status)
    {
        $this->sql = "UPDATE products set productStatus = :productStatus where _id = :_id";
        $updateparams = array(
            ":productStatus" => $status,
            ":_id" => $id
        );
        $this->cm = $this->cn->prepare($this->sql);
        $isupdate = (bool)$this->cm->execute($updateparams);
        unset($this->cm, $this->sql);
        return $isupdate;
    }

    public function updateProductStatus($id, $status)
    {
        $isUpdate = (bool)$this->_updateProductStatus($id, $status);
        if (!$isUpdate) {
            return $this->res(false, "Error On Updating Data", [], 500);
            exit();
        }
        $products  = $this->_getAllProducts();
        return $this->res(false, "Data Has Updated", $products, 200);
        exit();
    }

    private function _updatePrice($updateParams)
    {
        $this->sql = "UPDATE products set productPPrice = :productPPrice,productSPrice = :productSPrice,productMrp = :productMrp,productCgst = :productCgst,productSgst = :productSgst,peBy = :peBy , cpUpdate = now() where _id = :_id";
        $this->cm = $this->cn->prepare($this->sql);
        $isUpdate = $this->cm->execute($updateParams);
        unset($this->sql, $this->cm);
        return $isUpdate;
    }

    public function UpdateProductPrice($updateParams)
    {
        $isUpdate = $this->_updatePrice($updateParams);
        if (!$isUpdate) {
            return $this->res(false, "Error on Update Price", [], 500);
            exit();
        }

        $products  = $this->_getAllProducts();
        return $this->res(false, "Data Has Updated", $products, 200);
        exit();
    }

    private function LowStock(){
        $this->sql = "SELECT pr.*,
        brand.brandName as productBrandName,
        prtype.productTypeName as productTypeNamedisp,
        prunit.unitType as productUnitTypeName,
        IFNULL(pur.pqty,0) as pqty,
        IFNULL(sals.sqty,0) as sqty,
        (IFNULL(pur.pqty,0) - IFNULL(sals.sqty,0)) as balanceqty
         FROM products as pr inner join 
        brands as brand on pr.productBrand = brand._id inner join 
        productTypes as prtype on pr.productType = prtype._id inner join 
        productUnits as prunit on pr.productUnitType = prunit._id left join
        (select purchaseProduct,IFNULL(sum(purchaseQty),0) as pqty from purchases group by purchaseProduct) as pur 
        on pr._id = pur.purchaseProduct left join 
        (select salesProduct,IFNULL(sum(salesQty),0) as sqty from  sales group by salesProduct) as sals
        on pr._id = sals.salesProduct where pr.productMinQty >= (IFNULL(pur.pqty,0) - IFNULL(sals.sqty,0)) and pr.productMinQty <> 0";
        $this->cm = $this->cn->prepare($this->sql);
        $this->cm->execute();
        $products = [];
        while($rows = $this->cm->fetch(PDO::FETCH_ASSOC)){
            $product = $this->IProduct($rows);
            extract($rows);
            $product['pqty'] = (float)$pqty;
            $product['sqty'] = (float)$sqty;
            $product['balqty'] = (float)$balanceqty;
            $product['productBrandName'] = $productBrandName;
            $product['productTypeNamedisp'] = $productTypeNamedisp;
            $product['productUnitTypeName'] = $productUnitTypeName;
            $products[] = $product;
        }
        unset($this->cm,$this->sql,$rows);
        return $products;
    }

    private function totProducts(){
        $this->sql = "SELECT COUNT(*) as cnt from products";
        $this->cm = $this->cn->prepare($this->sql);
        $this->cm->execute();
        $cnt = 0;
        $rows = $this->cm->fetch(PDO::FETCH_ASSOC);
        $cnt = (int)$rows['cnt'];
        unset($this->cm,$this->sql,$rows);
        return $cnt;
    }

    public function DashboardProducts(){
        $totcount = (int)$this->totProducts();
        $lowstock = $this->LowStock();
        $data = array(
            "totProducts" => $totcount,
            "lowStocks" => $lowstock
        );
        return $data;
        exit();
    }

    //for update option

    private function _updateProductInfo($IProduct){
        $this->sql = "UPDATE products 
        set 
        productBrand = :productBrand,
        productType = :productType,
        productName = :productName,
        productSku = :productSku,
        productUnitType = :productUnitType,
        productMinQty = :productMinQty,
        productPPrice = :productPPrice,
        productSPrice = :productSPrice,
        productMrp = :productMrp,
        productCgst = :productCgst,
        productSgst = :productSgst,
        productDiscount = :productDiscount,
        productLocationRack = :productLocationRack,
        eBy = :eBy,
        peBy = :peBy,
        eDate = now(),
        cpUpdate = now(),
        productNametamil = :productNametamil,
        productgst = :productgst 
        where _id = :id";
        $this->cm = $this->cn->prepare($this->sql);
        $isUpdate = $this->cm->execute($IProduct);
        unset($this->sql,$this->cm);
        return $isUpdate;
    }

    public function UpdateProduct($IProduct){
        $isUpdate = $this->_updateProductInfo($IProduct);
        if(!$isUpdate){
            return $this->res(false,"Error on update",[],500);
            exit();
        }
        $products = $this->_getAllProducts();
        return $this->res(true, "ok", $products, 200);
        exit();
        
    }

    private function _productByPatch($id){
        $this->sql = "SELECT `purchaseUniqNo`,purhcaseGstval,`purchaseSPrice`,`purchaseMrp`,`purcahseHaveExpi`,`purchaseManDate`,`purchaseExpdate`, 
                    IFNULL(sum(`purchaseQty`),0) as pqty,IFNULL(sal.salestot,0) as salesqty from purchases as pu  left join 
                    (select salesPurchassRefno,salesProduct,sum(salesQty) as salestot from sales) as sal 
                    on pu.purchaseUniqNo = sal.salesPurchassRefno and pu.purchaseProduct=sal.salesProduct where purchaseProduct=:id 
                    group by `purchaseUniqNo`,purhcaseGstval,`purchaseSPrice`,`purchaseMrp`,`purcahseHaveExpi`,`purchaseManDate`,`purchaseExpdate`";
        $this->cm = $this->cn->prepare($this->sql);
        $this->cm->bindParam(":id",$id);
        $this->cm->execute();
        $products = [];
        while($rows = $this->cm->fetch(PDO::FETCH_ASSOC)){
            extract($rows);
            $product['purchaseUniqNo'] = $purchaseUniqNo;
            $product['purchaseSPrice'] = $purchaseSPrice;
            $product['purchaseMrp'] = $purchaseMrp;
            $product['purcahseHaveExpi'] = $purcahseHaveExpi;
            $product['purchaseManDate'] = $purchaseManDate;
            $product['purchaseExpdate'] = $purchaseExpdate;
            $product['purchaseExpdatedisp'] = date_format(date_create($purchaseExpdate),'M/Y');
            $product['pqty'] = $pqty;
            $product['salesqty'] = $salesqty;
            $product['balance'] = (float)$pqty - (float)$salesqty;
            $product['purchasePrice'] = $purhcaseGstval;
            $products[] = $product;
        }
        unset($this->sql,$this->cm,$rows);
        return $products;
    }
    public function ProductByPatch($id){
        $products = $this->_productByPatch($id);
        return $this->res(true,"ok",$products,200);
        exit();
    }

    //delete
    //check in purcahse
    private function _checkPurchase($id){
        $this->sql = "SELECT count(purchaseProduct) as cnt from purchases where  purchaseProduct = :purchaseProduct";
        $this->cm = $this->cn->prepare($this->sql);
        $this->cm->bindParam("purchaseProduct",$id);
        $this->cm->execute();
        $rows = $this->cm->fetch(PDO::FETCH_ASSOC);
        $cnt = (int)$rows['cnt'];
        unset($this->cm,$this->sql,$rows);
        return $cnt;
    }

    private function _checkSales($id){
        $this->sql = "SELECT COUNT(salesProduct) as cnt from sales where salesProduct = :salesProduct";
        $this->cm = $this->cn->prepare($this->sql);
        $this->cm->bindParam(":salesProduct",$id);
        $this->cm->execute();
        $rows = $this->cm->fetch(PDO::FETCH_ASSOC);
        $cnt = (int)$rows['cnt'];
        unset($this->cm,$this->sql,$rows);
        return $cnt;
    }

    private function _RemoveProduct($id){
        $this->sql = "DELETE FROM products where _id = :_id limit 1";
        $this->cm = $this->cn->prepare($this->sql);
        $this->cm->bindParam(":_id",$id);
        $isDeleted = $this->cm->execute();
        unset($this->cm,$this->sql);
        return $isDeleted;
    }

    public function RemoveProduct($id){
        //check in purhase
        $cnt_purchase = $this->_checkPurchase($id);
        if($cnt_purchase !== 0){
            return $this->res(false,"You Can not Delete This Product This Already in Purchase",[],409);
            exit();
        }
        $cnt_sales = $this->_checkSales($id);
        if($cnt_sales !== 0){
            return $this->res(false,"You Can not Delete This Product This Already In Sales",[],409);
            exit();
        }
        $isDeleted = $this->_RemoveProduct($id);
        if(!$isDeleted){
            return $this->res(false,"Error on Removing Product Informations",[],409);
            exit();
        }
        $products  = $this->_getAllProducts();
        return $this->res(true,"ok",$products,200);
        exit();
        //check in sales
    }

    public function _updateproductPriceInfo($Iparams){
        $this->sql = "UPDATE products set 
        productPPrice = :productPPrice,
        productSPrice = :productSPrice,
        productMrp = :productMrp,
        productCgst = :productCgst,
        productSgst = :productSgst,
        productgst = :productgst where _id = :id";
        $this->cm = $this->cn->prepare($this->sql);
        $isUpdated = $this->cm->execute($Iparams);
        unset($this->cm,$this->sql);
        return $isUpdated;
    }

    

}
