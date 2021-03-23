<?php
require_once('./curlClass/curlClass.php');

$curl = new cURL();

$cookie = uniqid("cookie_"); // Generate unique id for cookie file.

$proxy = [
    'METHOD' => 'LUMINATI',
    'USERNAME' => 'your_username',
    'PASSWORD' => 'your_password',
    'SESSION' => mt_rand(),
]; // Proxy configuration

$response = $curl->get("http://example.com/", NULL, $cookie)->body; // Send GET request (response will be saved in in $response variable.)
 // To use proxy add $proxy after $cookie

$curl->deleteCookie(); // Delete generated cookiefile. If used cookie param

$curl->debug(); // For debugging reuest/responses
