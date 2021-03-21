curlClass v0.0.1
================

Basic PHP class for easy HTTP requests.

Supports **GET**, **POST**, **PUT**, **DELETE** methods
--------

STARTER
--------
    require_once('./curlClass/curlClass.php');
    $curl = new cURL();

GET METHOD
--------

```php
$cookie = uniqid("cookie_"); // Generate unique id for cookie file.

$proxy = [
    'METHOD' => 'LUMINATI',
    'USERNAME' => 'your_username',
    'PASSWORD' => 'your_password',
    'SESSION' => mt_rand(),
]; // Proxy configuration

$curl->get("http://example.com/", NULL, $cookie)->body; // Send GET request (response will be saved in in $response variable.)
 // To use proxy add variable after $cookie

$curl->deleteCookie(); // Delete generated cookiefile. If used cookie param

$curl->debug(); // For debugging reuest/responses

```

POST METHOD
--------

```php
$cookie = uniqid("cookie_"); // Generate unique id for cookie file.

$proxy = [
    'METHOD' => 'LUMINATI',
    'USERNAME' => 'your_username',
    'PASSWORD' => 'your_password',
    'SESSION' => mt_rand(),
]; // Proxy configuration

$response = $curl->post("http://example.com/",  
    'name=Unique', // POSTFIELDS
    [
        'Host: example.com',
        'Origin: http://example.com',
        'Referer: http://example.com/'
    ], //HEADERS
    $cookie
)->body; // To use proxy add proxy variable after $cookie
 // Send POST request (response will be saved in in $response variable.)

$curl->deleteCookie(); // Delete generated cookiefile. If used cookie param

$curl->debug(); // For debugging reuest/responses
```

PROXY USAGE
--------

```php
# Local Proxy Configuration
$proxy = [
    'METHOD' => 'TUNNEL',
    'SERVER' => $curl->random('./proxy.txt')
];

# Apify Proxy Configuration
$proxy = [
    'METHOD' => 'APIFY',
    'PASSWORD' => 'your_pass',
];

# IPvanish Proxy Configuration
$proxy = [
    'METHOD' => 'IPVANISH',
    'SERVER' => 'akl-c12.ipvanish.com',
    'AUTH' => 'your_user:your_pass',
];

# Webshare Proxy Configuration
$proxy = [
    'METHOD' => 'WEBSHARE',
    'USERNAME' => 'your_user',
    'PASSWORD' => 'your_pass',
];

# Luminati Proxy Configuration
$proxy = [
    'METHOD' => 'LUMINATI',
    'USERNAME' => 'your_user',
    'PASSWORD' => 'your_pass',
    'SESSION' => mt_rand(),
];

# Proxyland Proxy Configuration
$proxy = [
    'METHOD' => 'PROXYLAND',
    'USERNAME' => 'your_user',
    'PASSWORD' => 'your_pass',
];

# HypeProxies Proxy Configuration
$proxy = [
    'METHOD' => 'HYPEPROXY',
    'USERNAME' => 'your_user',
    'PASSWORD' => 'your_pass',
];
```
USAGE & INSTALLATION
------------

### Direct
Install source code: https://github.com/username-uNique/curlClass/archive/refs/heads/main.zip

Repository: https://github.com/username-uNique/curlClass
