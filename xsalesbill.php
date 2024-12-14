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
    <title>SAPRROW SALES BILL - <?php echo $billinfo->salesInvoice ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            
             font-size: 12px;
    		font-family: 'Times New Roman';
        }

        table {
            margin-top: 20px;
            border-collapse: collapse;
    table-layout: fixed;
    width: 95mm;
}
        

        table th {
            font-family: sans-serif;
            font-size: 14px;
            font-weight: normal;
        }
table td
{
  page-break-inside: avoid;
}

        body {
            width: 80mm;
	    padding : 3px;
        }

        @page {
            size: 80mm;
        }
    </style>
    <script src="./JsBarcode.all.min.js"></script>
</head>

<body>
    <table class="no-border">
        <thead>
            <tr>
                <th class="top_bottom_border" colspan="5" style="border-bottom:2px dashed #000;">
                    <div style="display: flex;flex-direction: column;gap: 2px;">
                        <div style="display: flex;font-size: 37px;font-weight: bold;align-items: center;justify-content: center;">
                            <img src="./assets/img/logo-small.png" style="filter: grayscale();">
                            SPARROW
                        </div>
                        <div>SUPER MARKET</div>
                        <div>211-Main Bazar, Near Darusalam School,</div>
                        <div>Kadayanallur - 627751</div>
                        <div>Phone - 8300740249</div>
                        <div style="margin-top:10px;margin-bottom:20px;display:none">TAX INVOICE</div>
                    </div>
                </th>
            </tr>

        </thead>
        <tbody>
            <tr>
                <td colspan="5">
                    <div style="margin-top:20px"></div>
                </td>
            </tr>
            <tr>
                <td colspan="5">
                    <div style="display: flex; gap: 3px;width: 100%;flex-direction: row;align-items: center;justify-content:space-between;padding:3px">

                        <div>
                            <div style="display: flex;">
                                <div style="width:40px">Invoice# </div>
                                <div style="margin-left: 20px;font-weight:bold">: <?php echo $billinfo->salesInvoice ?></div>
                            </div>
                            <div style="display: flex;">
                                <div style="width:40px">Date </div>
                                <div style="margin-left: 20px;">: <?php echo $billinfo->salesDates->display  ?></div>
                            </div>
                            <div style="display: flex;">
                                <div style="width:40px">Time </div>
                                <div style="margin-left: 20px;">: <?php echo date_format(date_create($billinfo->cDate), 'h:i:s a') ?></div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td colspan="5">
                    <div style="margin-bottom:5px; margin-top:20px;border-bottom:2px dashed #000;"></div>
                </td>
            </tr>
            <tr>

                <td class="top_bottom_border" style="font-weight: bold;">Particulars</td>
                <td class="top_bottom_border" style="text-align: center;font-weight: bold;">Qty</td>
                <td class="top_bottom_border" style="text-align: right;font-weight: bold;">MRP</td>
                <td class="top_bottom_border" style="text-align: right;font-weight: bold">SRP</td>
                <td class="top_bottom_border" style="text-align: right;font-weight: bold">Amount</td>
            </tr>
            <tr>
                <td colspan="5">
                    <div style="margin-top:7px;margin-bottom:10px; border-bottom:2px dashed #000;"></div>
                </td>
            </tr>
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
                $sumofbill = ((float)$bill->salesSPrice + (float)$gstval) * (float)$bill->salesQty;
                $totinvoice += $sumofbill;
            ?>
                <tr>

                    <td style=";font-size:10px" ><?php echo $bill->productNametamil ?></td>
                    <td style="text-align: center"><?php echo $bill->salesQty ?></td>
                    <td style="text-align: right;"><?php echo $bill->salesMrp ?></td>
                    <td style="text-align: right;"><?php echo $bill->salesSPrice ?></td>
                    <td style="text-align: right;"><?php echo $sumofbill ?></td>
                </tr>
            <?php
            }
            ?>
            <tr>
                <td colspan="5">
                    <div style="margin-top:7px;margin-bottom: 13px;border-bottom:2px dashed #000;"></div>
                </td>
            </tr>
            <tr>
                <td colspan="5">
                    <div style="display: flex; gap: 3px;width: 100%;flex-direction: row;align-items: center;justify-content:space-between">
                        <div>
                            <div style="display: flex;">
                                <div >Tot Items </div>
                                <div style="text-align: right;margin-left: 20px;font-weight: bold;">: <?php echo $rc ?></div>
                            </div>
                            <div style="display: flex;">
                                <div >Tot Qty </div>
                                <div style="text-align: right;margin-left: 20px;font-weight: bold;">: <?php echo $totqty ?></div>
                            </div>
                        </div>
                        <div>
                            <div style="display: flex;">
                                <div >Gross Amt </div>
                                <div style="text-align: right;margin-left: 20px;font-weight: bold;font-size:15px">: <span>&#8377;</span> <?php echo $totinvoice ?></div>
                            </div>
                            <div style="display: flex;">
                                <div >Bill Discount</div>
                                <div style="text-align: right;margin-left: 20px;font-weight: bold;font-size:15px">: <span>&#8377;</span> <?php echo $billinfo->salesOthers ?></div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td colspan="5">
                    <div style="margin-top:7px;margin-bottom: 13px;border-bottom:2px dashed #000;"></div>
                </td>
            </tr>
            <tr>
                <td colspan="6" style="font-size: 20px;text-align:right">
                    <div style="display: flex; gap: 3px;width: 100%;flex-direction: row;align-items: center;justify-content: flex-end;">
                        <div style="font-size: 15px;font-weight:bold;">Net Amt : </div>
                        <div style="text-align: right;margin-left: 20px;font-weight: bold;font-size : 22px"><span>&#8377;</span> <?php echo (float)$totinvoice - (float)$billinfo->salesOthers ?></div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="5">
                    <div style="margin-top:7px;margin-bottom: 13px;border-bottom:2px dashed #000;"></div>
                </td>
            </tr>
            <tr>
                <td colspan="5" style="font-size: 14px;text-align:right">
                    <?php
                    $savedamount = ((float)$totalmrp - (float)$totalsrp) + (float)$billinfo->salesOthers;
                    ?>
                    Your Have Saved : <span>&#8377;</span> <span style="text-align: right;font-weight:bold;font-size:17px"> <?php echo (float)$savedamount ?></span>
                </td>
            </tr>

            <tr>
                <td colspan="5">
                    <div style="margin-bottom: 13px;"></div>
                </td>
            </tr>
            <tr>
                <td colspan="5">
                    <div style="margin-top:7px;margin-bottom: 13px;border-bottom:2px dashed #000;"></div>
                </td>
            </tr>
            <tr>
                <td colspan="5" style="padding: 10px;">
                    <h3 style="text-decoration: underline;margin-bottom:3px">Payment Details</h3>
                    <div style="display: flex; gap: 3px;width: 100%;flex-direction: row;align-items: center;justify-content:space-between">

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
                                <div style="margin-left: 20px;">: <?php echo $billinfo->payable ?></div>
                            </div>
                            <div style="display: flex;">
                                <div style="width: 112px;">Paid </div>
                                <div style="margin-left: 20px;">: <?php echo $billinfo->salesCustomerPaid ?></div>
                            </div>
                            <div style="display: flex;">
                                <div style="width: 112px;">Balance </div>
                                <div style="margin-left: 20px;">: <?php echo abs(round($billinfo->salesCustomerBalance)) ?></div>
                            </div>
                            <div style="display: flex;">
                                <div>TOTAL LOYALTY POINTS </div>
                                <?php
                                $billpoints = 0;
                                if ($billinfo->salesCustomerPhone !== "") {
                                    $billpoints = (float)$totalsrp / 100;
                                }
                                ?>
                                <div style="margin-left: 20px;">: <?php echo round($billpoints) ?></div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="5">
                    <div style="margin-top:7px;margin-bottom: 13px;border-bottom:2px dashed #000;"></div>
                </td>
            </tr>
            <tr>
                <td colspan="5">
                    <div class="label" data-barcode="<?php echo $billinfo->salesInvoice ?>"
                        style="display: flex;align-items: center;justify-content: center;flex-direction: column;">

                        <svg class="barcode"></svg>
                        <div class="barcode-text" style="font-size: 8px; font-weight: bold;font-family: sans-serif;text-align:center"><?php echo $billinfo->salesInvoice ?></div>


                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="5" style="text-align :center;">
                    <div style="text-align: center;">
                        THANK YOU FOR SHOPPINg WITH US </br> VISIT AGAIN
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
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