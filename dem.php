<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Barcode</title>
    <script src="./JsBarcode.all.min.js"></script>
    <style>
        @media print {
            body {
                width: 2in; /* Set width for 2-inch thermal paper */
                margin: 0; /* Remove default margin */
            }
            #barcode {
                width: 100%; /* Full width */
                text-align: center; /* Center the barcode */
            }
        }
        #barcode {
            text-align: center; /* Center the barcode */
        }
    </style>
</head>
<body>
    <div id="barcode"></div>
    <button onclick="printBarcode()">Print Barcode</button>

    <script>
        // Generate the barcode
        JsBarcode("#barcode", "123456789012", {
            format: "CODE128",
            width: 2,
            height: 100,
            displayValue: true
        });

        // Function to print the barcode
        function printBarcode() {
            window.print();
        }
    </script>
</body>
</html>
