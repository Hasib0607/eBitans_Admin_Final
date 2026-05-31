<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://bulksmsbd.net/api/smsapi");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
} else {
    echo 'Success: Connected to bulksmsbd.net';
}
curl_close($ch);
?>
