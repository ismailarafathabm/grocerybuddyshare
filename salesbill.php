<?php
extract($_GET);
$salesrefno = !isset($salesrefno) || trim($salesrefno) === "" ? "" : trim($salesrefno);
if ($salesrefno === "") {
    echo "Invoice Number Missing";
    die();
}
include_once './db/db.php';
$conn = new DBConnect();
$cn = $conn->connectDB();

include_once './controller/salescontroller.php';
$ssc = new SalesController($cn);
$bill = json_decode($ssc->GetAllSales($salesrefno));
if (!$bill->isSuccess) {
    echo $bill->msg;
    die();
}
$rc = count($bill->data);
if ($rc === 0) {
    header("http/1.0 404 page not found");
    die();
}
$billinfo = $bill->data[0];
//print_r($billinfo);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Receipt</title>
    <style>
        /* @page {
            size: 3in;
        } */

	:{
	  font-size : 12px;
	}
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            width: 2.9in;
        }

        .receipt {
            padding: 10px;
            /* border: 1px solid #000; */
            box-sizing: border-box;
        }

        h1 {
            font-size: 30px;
            text-align: center;
        }

        .items {
            margin: 10px 0;
	    
        }

        .item {
            display: flex;
            justify-content: space-between;
            font-size: 12px;

        }

        .item-name {
            width: 1in;
            margin-top: 2px;
font-size: 12px;
        }

        .item-qty {
            width: 0.15in;
            margin-top: 2px;
            text-align: center;
font-size: 12px;
        }

        .item-mrp {
            width: 0.26in;
            margin-top: 2px;
            text-align: right;
font-size: 12px;
        }

        .item-srp {
            width: 0.26in;
            margin-top: 2px;
            text-align: right;
font-size: 12px;
        }

        .item-totp {
                width: 0.20in;
    margin-top: 2px;
    text-align: right;
    margin-left: 4px;
    margin-right: 11px;
}
        

        .total {
           display: flex;
    justify-content: space-between;
    font-weight: bold;
    font-size: 13px;
    margin-right: 8px;
        }

        hr {
            border: 1px solid #000;
            margin: 5px 0;
        }
        .label {
            width: 2.6in;
            height: 1in;
            border: 1px solid #0000;
            margin: 0in;
            /* Small margin for separation */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            page-break-inside: avoid;
            zoom: 1.03;
            /* Avoid breaking inside labels */
        }

        @media print {
            @page {
                size: 85mm auto;
                /* landscape */
                /* you can also specify margins here: */
                margin: 0;
                
                /* for compatibility with both A4 and Letter */
            }
        }
    </style>
     <script src="./JsBarcode.all.min.js"></script>
</head>

<body>
    <div class="receipt">
        <div style="display: flex;font-size: 37px;font-weight: bold;align-items: center;justify-content: center;">
            <img src="./assets/img/logo-small.png">
            <h1>SPARROW</h1>
        </div>
        <div style="display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;font-size:12px;border-bottom:2px solid #000;padding-top:5px;padding-bottom:5px;">
            <div>SUPER MARKET</div>
            <div>211-Main Bazar,</div>
            <div> Near Darusalam School,</div>
            <div>Kadayanallur - 627751</div>
            <div>Phone - 8300740249</div>
        </div>
        <div style="margin-top:10px" >BILL#: <span><?php echo $billinfo->salesInvoice ?></span></div>
        <div>Date: <span><?php echo $billinfo->salesDates->display  ?></span></div>
        <div style="margin-bottom:10px;">Time: <span><?php echo date_format(date_create($billinfo->cDate), 'h:i:s a') ?></span></div>
        <hr>
        <div class="items">
            <div class="item" style="border-bottom:1px solid #000">
                <span class="item-name">Particulars</span>
                <span class="item-qty">Qty</span>
                <span class="item-mrp">MRP</span>
                <span class="item-srp">SRP</span>
                <span class="item-totp">Total</span>
            </div>
            <?php
            $rowno = 0;
            $totqty = 0;
            $totinvoice = 0;
            $totalsrp = 0;
            $totalmrp = 0;
            foreach ($bill->data as $bill) {
                $rowno += 1;
                $totalsrp += (float)$bill->salesSPrice;
                $totalmrp += (float)$bill->salesMrp;
                $totqty += (float)$bill->salesQty;
                $gstval = (float)$bill->salesCGSTval + (float)$bill->salesSGSTval;
                // echo $gstval;
                $sumofbill = (float)$bill->salesSPrice * (float)$bill->salesQty;
                $totinvoice += $sumofbill;
            ?>
                <div class="item">
                    <span class="item-name"><?php echo $bill->productNametamil ?></span>
                    <span class="item-qty"><?php echo $bill->salesQty ?></span>
                    <span class="item-mrp"><?php echo $bill->salesMrp ?></span>
                    <span class="item-srp"><?php echo $bill->salesSPrice ?></span>
                    <span class="item-totp"><?php echo $sumofbill ?></span>
                </div>
            <?php
            }
            ?>


        </div>
        <hr>
        <div class="total">
            <span>Tot.Items: </span>
            <span><?php echo $rc ?></span>
        </div>
        <div class="total">
            <span>Tot.Qty: </span>
            <span><?php echo $totqty ?></span>
        </div>
        <div class="total" style="margin-top: 5px;border-bottom:1px solid #000">
            <span>Gross Amt: </span>
            <span><span>&#8377;</span><?php echo  number_format($totinvoice, 0) ?></span>
        </div>
<div class="total" style="margin-bottom: 4px;border-bottom:1px solid #000">
            <span>You Have Saved: </span>
            <?php
            $savedamount = ((float)$totalmrp - (float)$totalsrp) + (float)$billinfo->salesOthers;
            ?>
            <span><span>&#8377;</span><?php echo  number_format($savedamount, 0) ?></span>
        </div>
        <div class="total">
            <span>Discount: </span>
            <span><span>&#8377;</span><?php echo  number_format($billinfo->salesOthers, 0) ?></span>
        </div>
        <div class="total" style="font-size: 18px;font-weight:bold;margin-bottom:10px;border-bottom:1px solid #000;">
            <span>Net Amt: </span>
            <?php
            $netamt = (float)$totinvoice - (float)$billinfo->salesOthers
            ?>
            <span><span>&#8377;</span><?php echo  number_format($netamt, 0) ?></span>
        </div>
        
        <div style="border-bottom:2px solid #000;margin-bottom:4px">
            <h3 style="text-decoration: underline;margin-bottom:3px">Payment Details</h3>
            <div style="display: flex; font-size:12px; gap: 3px;width: 100%;flex-direction: row;align-items: center;justify-content:space-between">

                <div>
                    <div style="display: flex;">
                        <div style="width: 112px;">Customer Name </div>

                        <div style="margin-left: 20px;font-weight:bold">: <?php echo $billinfo->salesCustomerName ?></div>
                    </div>
                    <div style="display: flex;">
                        <div style="width: 112px;">Used Points </div>
                        <div style="margin-left: 20px;">: <?php echo $billinfo->cususedpoints  ?></div>
                    </div>
                    <div style="display: flex;">
                        <div style="width: 112px;">Payabel </div>
                        <div style="margin-left: 20px;">:<span>&#8377;</span><?php echo number_format($billinfo->payable, 2) ?></div>
                    </div>
                    <div style="display: flex;">
                        <div style="width: 112px;">Paid </div>
                        <div style="margin-left: 20px;">:<span>&#8377;</span><?php echo number_format($billinfo->salesCustomerPaid, 2) ?></div>
                    </div>
                    <div style="display: flex;">
                        <div style="width: 112px;">Balance </div>
                        <div style="margin-left: 20px;">:<span>&#8377;</span> <?php echo number_format(abs(round($billinfo->salesCustomerBalance)), 0) ?></div>
                    </div>
                    <div style="display: flex;font-size:14px;font-weight:bold;margin-top:5px;margin-bottom:5px;padding:3px">
                        <div>TOTAL LOYALTY POINTS </div>
                        <?php
                        $billpoints = 0;
                        if ($billinfo->salesCustomerPhone !== "") {
                            $billpoints = (float)$totalsrp / 100;
                        }
                        ?>
                        <div style="margin-left: 20px;">: <?php echo number_format($billpoints, 2) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div>
        <div class="label" data-barcode="<?php echo $billinfo->salesInvoice ?>"
                        style="display: flex;align-items: center;justify-content: center;flex-direction: column;">

                        <svg class="barcode"></svg>
                        <div class="barcode-text" style="font-size: 8px; font-weight: bold;font-family: sans-serif;text-align:center"><?php echo $billinfo->salesInvoice ?></div>


                    </div>
        </div>
        <p style="font-size: 12px;text-align:center"> THANK YOU FOR SHOPPING WITH US </br> VISIT AGAIN</p>
    </div>
    <script>
         document.querySelectorAll('.label').forEach(label => {
            const barcodeValue = label.getAttribute('data-barcode');
            JsBarcode(label.querySelector('.barcode'), barcodeValue, {
                format: "CODE128",
                width: 2, // Width of the bars
                height: 35, // Height of the barcode
                displayValue: false // Don't display the value as text on the barcode
            });
        });
        window.print();
    </script>
</body>

</html>