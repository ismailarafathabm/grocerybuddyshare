<?php
extract($_GET);
$barcode = !isset($barcode) || trim($barcode) === "" ? "" : trim($barcode);
if ($barcode === "") {
    echo "barcode missing";
    die();
}

$pname = !isset($pname) || trim($pname) === "" ? "" : trim($pname);
if ($pname === "") {
    echo "Product Name missing";
    die();
}

$pqty = !isset($pqty) || trim($pqty) === "" ? "" : trim($pqty);
if ($pqty === "") {
    echo "Product Qty missing";
    die();
}
if (!is_numeric($pqty)) {
    echo "Product Qty Not valid format";
    die();
}

$psp = !isset($psp) || trim($psp) === "" ? "" : trim($psp);
if ($psp === "") {
    echo "Sparrow Price missing";
    die();
}
if (!is_numeric($psp)) {
    echo "Sparrow Price Not valid format";
    die();
}

$pmrp = !isset($pmrp) || trim($pmrp) === "" ? "" : trim($pmrp);
if ($pmrp === "") {
    echo "MRP Price missing";
    die();
}
if (!is_numeric($pmrp)) {
    echo "MRP Price Not valid format";
    die();
}
$pkdate = $isex === 'Yes' ? date_format(date_create($pdate), 'M/y') : '-';
$exdate = $isex === 'Yes' ? date_format(date_create($edate), 'M/y') : '-';


?>
<!DOCTYPE html>
<html>

<head>
    <title>Sparrow Super Market</title>
    <style>
        * {
            padding: 0;
            margin: 0;
        }

        .barcodearea {
            width: 2in;
            height: 1in;
            border: 1px solid #000;
            margin: 0in;
            /* Small margin for separation */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            page-break-inside: avoid;
            position: relative;




        }

        .barcodetitle {
            font-size: 10px;
            font-weight: bold;
            font-family: sans-serif;
        }

        .barcode_txt {
            font-size: 10px;
            font-family: sans-serif;
        }

        .barcodeitem {
            font-size: 10px;
            font-weight: bold;
            font-family: sans-serif;
        }

        .barcodelable {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .exp {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 8px;
            font-family: sans-serif;
            width: 1.5in;
        }

        .barcodepaper {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            width: 4.2in;
            margin-bottom: 2px;
        }

        @page {
            size: 4.2in 1in;
            margin: 0;
        }
    </style>
</head>


<body>
    <div class="barcodepaper">
        <?php
        $top = 0;
        for ($i = 1; $i <= (int)$pqty; $i++) {
            if ($i % 2 == 0) {
            } else {
                $top += 0.1;
            }

        ?>
            <div class="barcodearea">
                <div style="
    position: absolute;
    top: 5px;
    display:flex;
    flex-direction : column;
    align-items:center;
    margin-top:<?php echo (string)$top ?>in;
">
                    <div class="barcodetitle">
                        Sparrow Super Market
                    </div>
                    <div class="barcodelable">
                        <img id="barcode_id" width="140px" height="50px" />
                        <div class="barcode_txt">
                            <?php echo $barcode ?>
                        </div>
                    </div>
                    <div class="barcodeitem">
                        <?php echo $pname ?>
                    </div>
                    <div class="exp">

                        <div class="pdate">
                            PAC : <?php
                                    echo $pkdate
                                    ?>

                        </div>
                        <div class="pdate">
                            MRP : <span>&#8377;</span> <?php echo $pmrp ?>
                        </div>

                    </div>
                    <div class="exp">

                        <div class="pdate">
                            EXP : <?php echo $exdate ?>
                        </div>
                        <div class="pdate" style="font-size: 10px;font-weight:bold">
                            SRP : <span>&#8377;</span> <?php echo $psp ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <!-- <div id="BarCodeArea">
        <div class="barcode_content" style="width: 68mm; height: 25mm;">
            <p>Title of Barcode You want to Place...</p>
            <img id="barcode_id" />
        </div>
    </div> -->

    <script src="./assets/js/JsBarcode.all.min.js"></script>
    <script>
        function print_barcode(BarCodePrintDiv) {
            var body = document.body.innerHTML;
            var data = document.getElementById(BarCodePrintDiv).innerHTML;
            document.body.innerHTML = data;
            window.print();
            document.body.innerHTML = body;
        }
        GenerateBarCode("<?php echo $barcode ?>");

        function GenerateBarCode(barcodeValue) {
            JsBarcode("#barcode_id", barcodeValue, {
                format: "CODE39",
                width: 1,
                height: 40,
                font: "monospace",
                displayValue: false,
                lineColor: "#000000"
            });
        }
        window.print();
    </script>
</body>

</html>