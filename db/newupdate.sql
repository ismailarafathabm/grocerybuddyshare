ALTER TABLE `products` ADD `productNametamil` VARCHAR(255) NOT NULL AFTER `cpUpdate`, ADD `productgst` DOUBLE NOT NULL AFTER `productNametamil`;
ALTER TABLE `purchases` ADD `purchaseType` VARCHAR(255) NOT NULL AFTER `status`, ADD `purchasePaid` DOUBLE NOT NULL AFTER `purchaseType`, ADD `purchasePaidRefno` VARCHAR(255) NOT NULL AFTER `purchasePaid`;

--20th sep
ALTER TABLE `purchases` ADD `purcahseHaveExpi` INT NOT NULL AFTER `purchasePaidRefno`, ADD `purchaseManDate` DATE NOT NULL AFTER `purcahseHaveExpi`, ADD `purchaseExpdate` TEXT NOT NULL AFTER `purchaseManDate`;
ALTER TABLE `purchases` ADD `purchaseDiscounttype` INT NOT NULL AFTER `purchaseExpdate`, ADD `purchaseDiscountval` DOUBLE NOT NULL AFTER `purchaseDiscounttype`;
ALTER TABLE `purchases` CHANGE `purcahseHaveExpi` `purcahseHaveExpi` VARCHAR(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT 'No';

CREATE TABLE `purchaseDue` (`_id` INT NOT NULL AUTO_INCREMENT , `purchaseDueSupplier` INT NOT NULL , `purchaseDueRefno` VARCHAR(255) NOT NULL , `purchaseDueAmount` DOUBLE NOT NULL , `purchaseDueDate` DATE NOT NULL , `purchaseDueDueDate` DATE NOT NULL , `cBy` VARCHAR(255) NOT NULL , `eBy` VARCHAR(255) NOT NULL , `cDate` DATETIME NOT NULL , `eDate` DATETIME NOT NULL , `status` INT NOT NULL , PRIMARY KEY (`_id`)) ENGINE = InnoDB;

ALTER TABLE `purchases` ADD `purchaseSPrice` DOUBLE NOT NULL AFTER `purchaseDiscountval`, ADD `purchaseMrP` DOUBLE NOT NULL AFTER `purchaseSPrice`;

--new update 22 oct

ALTER TABLE `sales` ADD `salesMrp` DOUBLE NOT NULL AFTER `salesnetprice`, ADD `salesPurchassRefno` VARCHAR(244) NOT NULL AFTER `salesMrp`, ADD `salesHaveExp` VARCHAR(255) NOT NULL AFTER `salesPurchassRefno`, ADD `salesPackedDate` DATE NOT NULL AFTER `salesHaveExp`, ADD `salesExpiryDate` DATE NOT NULL AFTER `salesPackedDate`;
--new update 23 oc
create table customers(_id int primary key AUTO_INCREMENT,customerName varchar(255),customerPhone varchar(255),customerAddress varchar(255))
create table customerSalesPoints(
    _id int primary key AUTO_INCREMENT,
    customerPhone varchar(255) not null,
    salesInvoice varchar(255) not null,
    salesInvoiceTotal double not null,
    salesInvoicePointGet double not null,
    cBy varchar(255) not null,
    eBy varchar(255) not null,
    cDate DateTIme not null DEFAULT CURRENT_TIMESTAMP,
    eDate Datetime not null DEFAULT CURRENT_TIMESTAMP
);
create table customerSalesPointUses(
    _id int not null primary key AUTO_INCREMENT,
    useDate Date not null DEFAULT CURRENT_DATE,
    customerPhone varchar(255) not null,
    usedPoints double not null,
    usePointSalesInvoiceNo varchar(255) not null,
    cBy varchar(255) not null,
    eBy varchar(255) not null,
    cDate datetime not null DEFAULT CURRENT_TIMESTAMP,
    eDate datetime not null DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE `sales` ADD `payable` DOUBLE NOT NULL AFTER `cususedpoints`;


--27 oct

update purchases set  purchasePaidRefno = '0' 
ALTER TABLE `purchases` CHANGE `purchasePaidRefno` `purchasePaidRefno` DOUBLE NOT NULL DEFAULT '0';
update purchases set purchasePaidRefno = round((select (productPPrice*productgst/100) from products where _id=purchases.purchaseProduct),2) + purchaseSubtot 