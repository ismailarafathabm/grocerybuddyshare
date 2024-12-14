<?php
class mac
{
    public function res($isSuccess, $msg, $data, $statusCode)
    {
        $response = array(
            "isSuccess" => $isSuccess,
            "msg" => $msg,
            "data" => $data,
            "statusCode" => $statusCode
        );
        header("HTTP/1.0 $statusCode $msg");
        return json_encode($response);
        exit();
    }

    public function token($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public function tokenx($length)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function enc($action, $string)
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'nafco2020';
        $secret_iv = '2020nafco';
        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        if ($action == 'enc') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'denc') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }

    public function datemethod($date)
    {
        return array(
            "sorting" => date_format(date_create($date), 'Y-m-d'),
            "display" => date_format(date_create($date), 'd-M-Y'),
            "normal" => date_format(date_create($date), 'd-m-Y'),
            "print" => date_format(date_create($date), 'd-m-y'),
        );
    }

}
