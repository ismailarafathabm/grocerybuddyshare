
<?php

// From URL to get webpage contents.
$url = "http://192.168.1.7:8082/grocerybuddy/barcode.php?barcode=6461&pname=%E0%AE%87%E0%AE%9F%E0%AF%8D%E0%AE%B2%E0%AE%BF%20%E0%AE%AE%E0%AE%BE%E0%AE%B5%E0%AF%81%20250%20%E0%AE%AE%E0%AE%BF%E0%AE%B2%E0%AF%8D%E0%AE%B2%E0%AE%BF&pqty=20&psp=20&pmrp=25&isex=Yes&pdate=2024-10-20&edate=2024-10-22";

// Initialize a CURL session.
$ch = curl_init();

// Return Page contents.
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

//grab URL and pass it to the variable.
curl_setopt($ch, CURLOPT_URL, $url);

$result = curl_exec($ch);




?>
<?php

try {

    $print_output  =$result ;
    $fp = pfsockopen("192.168.1.10", 9100);
    fputs($fp, $print_output);
    fclose($fp);
} catch (Exception $e) {
    print_r($e);
}

?>