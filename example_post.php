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

$response = $curl->post("http://example.com/",  
    'name=Unique', 
    [
        'Host: example.com',
        'Origin: http://example.com',
        'Referer: http://example.com/'
    ], 
    $cookie
)->body; // To use proxy add $proxy after $cookie
 // Send POST request (response will be saved in in $response variable.)

$curl->deleteCookie(); // Delete generated cookiefile. If used cookie param

$curl->debug(); // For debugging reuest/responses
