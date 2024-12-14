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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barcode Labels</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            margin: 0;
            padding: 0;

            display: flex;
            flex-wrap: wrap;
            width: 4.2in;
            /* Width of the letter paper */
            height: 1.5in;
            /* Height of the letter paper */
        }

        .label {
            width: 2in;
            height: 1.3in;
            border: 1px solid #000;
            margin: 0in;
            /* Small margin for separation */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            page-break-inside: avoid;
            /* Avoid breaking inside labels */
        }

        @page {
            size: 4.2in 1.5in;
            margin: 0.1in;
        }
    </style>
</head>

<body>
    <?php
    for ($i = 1; $i <= (int)$pqty; $i++) {
    ?>
        <div class="label" data-barcode="<?php echo $barcode ?>">
            <div class="barcodetitle" style="font-size: 11px; font-weight: bold;font-family: sans-serif;">
                Sparrow Super Market
            </div>
            <svg class="barcode"></svg>
            <div class="barcode-text" style="font-size: 10px; font-weight: bold;font-family: sans-serif;"><?php echo $barcode ?></div>
            <div class="barcodeitem" style="font-size: 10px; font-weight: bold;font-family: sans-serif;">
                <?php echo $pname ?>
            </div>
            <div style="font-size: 10px;display:flex;width: 1.8in;align-items: center;justify-content: space-around;font-family: sans-serif;">
                <div class="exp">
                    <div class="pdate">PAC : <?php echo $pkdate ?></div>
                    <div class="pdate">MRP : <span>&#8377;</span> <?php echo $pmrp ?></div>
                </div>
                <div class="exp">
                    <div class="pdate">EXP : <?php echo $exdate ?></div>
                    <div class="pdate" style="font-size: 10px;font-weight:bold">SRP : <span>&#8377;</span> <?php echo $psp ?></div>
                </div>
            </div>
        </div>

    <?php } ?>
    <!-- Add more labels as needed -->

    <script>
        document.querySelectorAll('.label').forEach(label => {
            const barcodeValue = label.getAttribute('data-barcode');
            JsBarcode(label.querySelector('.barcode'), barcodeValue, {
                format: "CODE128",
                width: 2, // Width of the bars
                height: 30, // Height of the barcode
                displayValue: false // Don't display the value as text on the barcode
            });
        });
    </script>
</body>

</html>