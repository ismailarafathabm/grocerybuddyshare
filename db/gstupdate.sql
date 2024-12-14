ALTER TABLE `purchases` ADD `purchaseGst` DOUBLE NOT NULL DEFAULT '0' AFTER `purchaseMrp`, ADD `purchaseCgst` DOUBLE NOT NULL DEFAULT '0' AFTER `purchaseGst`, ADD `purchaseSgst` DOUBLE NOT NULL DEFAULT '0' AFTER `purchaseCgst`, ADD `purhcaseGstval` DOUBLE NOT NULL DEFAULT '0' AFTER `purchaseSgst`;
ALTER TABLE `purchases` ADD `purhcaseGsttotval` DOUBLE NOT NULL DEFAULT '0' AFTER `purhcaseGstval`;

update purchases set `purchaseGst`=(select IFNULL(productCgst+productSgst,0) as gst from products where _id = purchaseProduct);
UPDATE purchases set `purchaseCgst`=(select productCgst from products where _id = purchaseProduct)
UPDATE purchases set `purchaseSgst`=(select productSgst from products where _id = purchaseProduct)
update purchases set `purhcaseGstval`=(`purchasePrice`*`purchaseGst`/100) + purchasePrice
update purchases set `purhcaseGsttotval`=purhcaseGstval * `purchaseQty`

update sales set `salesPPrice`=(select purhcaseGstval from purchases where purchaseProduct = `salesProduct`)