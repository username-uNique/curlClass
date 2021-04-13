<?php
 #Author @username_uNique
class cURL
{
    private static $ch;
    private static $response;
    private static $autoHeaders;
    private static int $error_in_code;
    private static array $response_infos;
    private static string $error_in_string;
    private static string $cookie_system = '';
    private static array $set_curl_option = [
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_CONNECTTIMEOUT => 30,
        CURLOPT_HEADER         => true,
        CURLOPT_AUTOREFERER    => true,
        CURLINFO_HEADER_OUT    => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
    ];

    /**
     * prepare curl request
     *
     * @access private
     * @param string $url
     *
     * @return void
     */
    private static function setUrl(string $url): void
    {
        self::$ch = curl_init($url);
        self::curlOption(self::$set_curl_option);
    }

    /**
     * allow custom curlOption
     *
     * @access public
     * @param array $option
     *
     * @return void
     */
    public static function curlOption(array $option): void
    {
        curl_setopt_array(self::$ch, $option);
    }

    /**
     * set headerOption
     *
     * @access private
     * @param header
     *
     * @return void
     */
    private static function setHeaders(array $header): void
    {
        self::curlOption([CURLOPT_HTTPHEADER => $header]);
    }

    /**
     *  use localproxy
     *
     * @access private
     * @param array $sexyproxy
     *
     * @return void
     */
    private static function localProxy(array $sexyproxy): void
    {
        self::curlOption([
            CURLOPT_PROXY => $sexyproxy['SERVER'],
            CURLOPT_HTTPPROXYTUNNEL => true,
        ]);
    }

    /**
     * use luminati proxy configuration
     *
     * @access private
     * @param array $sexyproxy
     *
     * @return void
     */
    private static function luminati(array $sexyproxy): void
    {
        self::curlOption([
            CURLOPT_PROXY => 'http://zproxy.lum-superproxy.io:22225',
            CURLOPT_PROXYUSERPWD => "{$sexyproxy['USERNAME']}-session-{$sexyproxy['SESSION']}:{$sexyproxy['PASSWORD']}",
        ]);
    }

    /**
     * use apify proxy configuration
     *
     * @access private
     * @param array $sexyproxy
     *
     * @return void
     */
    private static function apify(array $sexyproxy): void
    {
        self::curlOption([
            CURLOPT_PROXY => 'http://proxy.apify.com:8000',
            CURLOPT_PROXYUSERPWD => "auto:{$sexyproxy['PASSWORD']}",
        ]);
    }

    /**
     * use ipvanish proxy configuration
     *
     * @access private
     * @param array $sexyproxy
     *
     * @return void
     */
    private static function ipvanish(array $sexyproxy): void
    {
        self::curlOption([
            CURLOPT_PROXY => "{$sexyproxy['SERVER']}:1080",
            CURLOPT_PROXYUSERPWD => $sexyproxy['AUTH'],
        ]);
    }

    /**
     * use webshare proxy configuration
     *
     * @access private
     * @param array $sexyproxy
     *
     * @return void
     */
    private static function webshare(array $sexyproxy): void
    {
        self::curlOption([
            CURLOPT_PROXY => 'p.webshare.io:80',
            CURLOPT_PROXYUSERPWD => "{$sexyproxy['USERNAME']}:{$sexyproxy['PASSWORD']}",
        ]);
    }

    /**
     * use hypeproxies proxy configuration
     *
     * @access private
     * @param array $sexyproxy
     *
     * @return void
     */
    private static function hypeproxies(array $sexyproxy): void
    {
        self::curlOption([
            CURLOPT_PROXY => 'usa.resi.hypeproxies.io:5844',
            CURLOPT_PROXYUSERPWD => "{$sexyproxy['USERNAME']}:{$sexyproxy['PASSWORD']}",
        ]);
    }

    /**
     * use proxyland proxy configuration
     *
     * @access private
     * @param array $sexyproxy
     *
     * @return void
     */
    private static function proxyland(array $sexyproxy): void
    {
        self::curlOption([
            CURLOPT_PROXY => 'server.proxyland.io:9090',
            CURLOPT_PROXYUSERPWD => "{$sexyproxy['USERNAME']}:{$sexyproxy['PASSWORD']}",
        ]);
    }

    /**
     * use proxy system
     *
     * @access private
     * @param array $sexyproxy
     *
     * @return void
     */
    private static function ProxySystem($sexyproxy): void
    {
        switch (strtoupper($sexyproxy['METHOD'])) {
            case 'LOCAL':
                self::localProxy($sexyproxy);
                break;
            case 'APIFY':
                self::apify($sexyproxy);
                break;
            case 'LUMINATI':
                self::luminati($sexyproxy);
                break;
            case 'IPVANISH':
                self::ipvanish($sexyproxy);
                break;
            case 'WEBSHARE':
                self::webshare($sexyproxy);
                break;
            case 'PROXYLAND':
                self::proxyland($sexyproxy);
                break;
            case 'HYPEPROXY':
                self::hypeproxies($sexyproxy);
                break;
        }
    }

    /**
     * smart automated cookie system
     *
     * @access private
     * @param string $file
     *
     * @return void
     */
    private static function smartCookie(string $file): void
    {
        self::$cookie_system = sprintf("%s/%s.txt", sys_get_temp_dir(), $file);
        self::curlOption([
            CURLOPT_COOKIEJAR => self::$cookie_system,
            CURLOPT_COOKIEFILE => self::$cookie_system,
        ]);
    }

    /**
     * delete saved cookiefile
     *
     * @access public
     *
     * @return void
     */
    public static function deleteCookie(): void
    {
        unlink(self::$cookie_system);
    }

    /**
     * validate parameters for curl structure
     *
     * @access private
     * @param array $headers
     * @param string $cookie
     * @param array $server
     *
     * @return void
     */
    private static function validateParam(
        array $headers = null,
        string $cookie = null,
        array $server = null
    ): void {
        if (!empty($headers) && is_array($headers)) {
            self::setHeaders($headers);
        }

        if (!empty($cookie)) {
            self::smartCookie($cookie);
        }

        if (!empty($server) && is_array($server)) {
            self::ProxySystem($server);
        }
    }

    /**
     * set configuration for GET request with header, cookie, proxy
     *
     * @access public
     * @param string $url
     * @param array $headers
     * @param string $cookie
     * @param array %server
     *
     * @return object
     */
    public static function get(
        string $url,
        array $headers = null,
        string $cookie = null,
        array $server = null
    ): object {
        self::setUrl($url);
        self::curlOption([CURLOPT_USERAGENT => self::userAgent()]);
        self::validateParam($headers, $cookie, $server);
        return self::run();
    }

    /**
     * set configuration for POST request with data, header, cookie, proxy
     *
     * @access public
     * @param string $url
     * @param string|array $data
     * @param array $headers
     * @param string $cookie
     * @param array $server
     *
     * @return object
     */
    public static function post(
        string $url,
        $data = null,
        array $headers = null,
        string $cookie = null,
        array $server = null
    ): object {
        self::setUrl($url);
        self::curlOption([
            CURLOPT_USERAGENT => self::userAgent(),
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => self::AutoPostfield($data),
        ]);
        self::validateParam($headers, $cookie, $server);
        return self::run();
    }

    /**
     * set configuration for PUT request with data, header, cookie, proxy
     *
     * @access public
     * @param string $url
     * @param string|array $data
     * @param array $headers
     * @param string $cookie
     * @param array $server
     *
     * @return object
     */
    public static function put(
        string $url,
        $data = null,
        array $headers = null,
        string $cookie = null,
        array $server = null
    ): object {
        self::setUrl($url);
        self::curlOption([
            CURLOPT_USERAGENT => self::userAgent(),
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => self::AutoPostfield($data),
        ]);
        self::validateParam($headers, $cookie, $server);
        return self::run();
    }

    /**
     * set configuration for DELETE request with data, header, cookie, proxy
     *
     * @access public
     * @param string $url
     * @param string|array $data
     * @param array $headers
     * @param string $cookie
     * @param array $server
     *
     * @return object
     */
    public static function delete(
        string $url,
        $data = null,
        array $headers = null,
        string $cookie = null,
        array $server = null
    ): object {
        self::setUrl($url);
        self::curlOption([
            CURLOPT_USERAGENT => self::userAgent(),
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_POSTFIELDS => self::AutoPostfield($data),
        ]);
        self::validateParam($headers, $cookie, $server);
        return self::run();
    }

    /**
     * set curl request and headerfunction
     *
     * @access public
     *
     * @return object
     */
    public static function run(): object
    {
        self::createStdClass();
        self::curlOption([
            CURLOPT_HEADERFUNCTION => setAutoHeaders(self::$autoHeaders),
        ]);
        self::$response = curl_exec(self::$ch);
        self::$response_infos = curl_getinfo(self::$ch);
        if (self::$response === false) {
            self::$error_in_code = curl_errno(self::$ch);
            self::$error_in_string = curl_error(self::$ch);
            curl_close(self::$ch);
            return (object) [
                'success' => false,
                'code' => self::$response_infos['http_code'],
                'headers' => [
                    'request_headers' => self::parseAutoHeaders(
                        self::$response_infos['request_header']
                    ),
                    'response_headers' => self::parseAutoHeaders(
                        self::$autoHeaders->respHeaders
                    ),
                ],
                'errno' => self::$error_in_code,
                'error' => self::$error_in_string,
                'body' => 'Error, ' . self::$error_in_string,
            ];
        } else {
            curl_close(self::$ch);
            return (object) [
                'success' => true,
                'code' => self::$response_infos['http_code'],
                'headers' => [
                    'request_headers' => self::parseAutoHeaders(
                        self::$response_infos['request_header']
                    ),
                    'response_headers' => self::parseAutoHeaders(
                        self::$autoHeaders->respHeaders
                    ),
                ],
                'body' => self::$response,
            ];
        }
    }

    /**
     * echo proceeded request responses (debug)
     *
     * @access public
     * @param bool $pretty
     *
     * @return string
     */
    public static function debug(bool $pretty = false): string
    {
        if ($pretty) {
            setHeaders('Content-Type: application/json');
            echo json_encode([
                'curlx_debug' => [
                    'information' => [
                        'request_headers' => self::$response_infos,
                        'response_headers' => self::parseAutoHeaders(
                            self::$autoHeaders->respHeaders
                        ),
                    ],
                    'errors' => [
                        'errnum' => self::$error_in_code ?? '',
                        'errstr' => self::$error_in_string ?? '',
                    ],
                    'response' => self::$response,
                ],
            ]);
        } else {
            echo sprintf(
                "=============================================<br/>\n
                <h2>REQUEST DEBUG</h2>\n
                =============================================<br/>\n
                <h3>RESPONSE</h3>\n
                <code>%s</code><br/>\n\n",
                nl2br(htmlentities(self::$response))
            );
            echo sprintf(
                "=============================================<br/>\n
                <h3>Information</h3>\n
                =============================================<br/><pre>%s</pre>",
                print_r(self::$response_infos, true)
            );
            echo sprintf("=============================================<br/>");
            if (isset(self::$error_in_string)) {
                echo sprintf(
                    "=============================================<br/>\n
                    <h3>Errors</h3>\n
                    <strong>Code: </strong>%d<br/>\n
                    <strong>Message: </strong>%d<br/>\n",
                    self::$error_in_code,
                    self::$error_in_string
                );
            }
        }
    }

    /**
     * get random string from selected file
     *
     * @access public
     * @param string $file
     *
     * @return string
     */
    public static function random(string $file): string
    {
        $_ = file($file);
        return $_[rand(0, count($_) - 1)];
    }

    /**
     * create temp directory to store received headers
     *
     * @access private
     *
     * @return void
     *
     */
    private static function createStdClass(): void
    {
        $headup = new \stdClass();
        $headup->respHeaders = '';
        self::$autoHeaders = $headup;
    }

    /**
     * validate postfield type
     *
     * @access private
     * @param string|array|object|null $data
     *
     * @return string
     */
    private static function AutoPostfield($data): string
    {
        if (empty($data)) {
            return false;
        } elseif (is_array($data) || is_object($data)) {
            return json_encode($data);
        } else {
            return $data;
        }
    }

    /**
     * remove spaces
     *
     * @access public
     * @param string $str
     *
     * @return string
     */
    public static function clean(string $str): string
    {
        return preg_replace('/\s+/', '', $str);
    }

    /**
     * Parse Headers
     *
     * @access private
     * @param string $raw
     *
     * @return array
     */
    private static function supportParse(string $raw): array
    {
        $raw = preg_split('/\r\n/', $raw, null, PREG_SPLIT_NO_EMPTY);
        $http_headers = [];
        for ($i = 1; $i < count($raw); $i++) {
            if (strpos($raw[$i], ':') !== false) {
                list($key, $value) = explode(':', $raw[$i], 2);
                $key = trim($key);
                $value = trim($value);
                isset($http_headers[$key])
                    ? ($http_headers[$key] .= ',' . $value)
                    : ($http_headers[$key] = $value);
            }
        }

        return [($raw['0'] ??= $raw['0']), $http_headers];
    }

    /**
     * extract headers
     *
     * @access private
     * @param string $raw
     *
     * @return array
     */
    private static function parseAutoHeaders(string $raw): array
    {
        $request_headers = [];

        list($scheme, $headers) = self::supportParse($raw);
        $request_headers['scheme'] = $scheme;
        foreach ($headers as $key => $value) {
            $request_headers[$key] = $value;
        }

        return $request_headers;
    }

    /**
     * Can split a string by two specify strings
     *
     * @access public
     * @param string $str
     * @param string $start
     * @param string $end
     * @param bool $decode
     *
     * @return string
     */
    public static function getBetwn(
        string $str,
        string $start,
        string $end,
        bool $decode = false
    ): string {
        return $decode
            ? base64_decode(explode($end, explode($start, $str)[1])[0])
            : explode($end, explode($start, $str)[1])[0];
    }

    /**
     * get valid chrome version
     *
     * @access private
     * @param string|array|object|null $version
     *
     * @return string
     */
    private static function chromeVal($version): string
    {
        return random_int($version['min'], $version['max']) .
            '.0.' .
            random_int(1000, 4000) .
            '.' .
            random_int(100, 400);
    }

    /**
     * return a random user agent
     *
     * @access private
     *
     * @return string
     */
    private static function userAgent(): string
    {
        $userAgent =
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/' .
            (random_int(1, 100) > 50
                ? random_int(533, 537)
                : random_int(600, 603)) .
            '.' .
            random_int(1, 50) .
            ' (KHTML, like Gecko) Chrome/' .
            self::chromeVal(['min' => 47, 'max' => 55]) .
            ' Safari/' .
            (random_int(1, 100) > 50
                ? random_int(533, 537)
                : random_int(600, 603));
        return $userAgent;
    }
}

function setAutoHeaders($autoHeaders)
{
    return function ($_, $header) use ($autoHeaders) {
        $autoHeaders->respHeaders .= $header;
        return strlen($header);
    };
}
