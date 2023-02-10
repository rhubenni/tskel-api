<?php declare(strict_types=1);

namespace api;

class _api {
    static public array $REQUEST_SETTINGS;
    
    static public function parse_request() : void
    {
        $REQUEST_SETTINGS = [
            "REQUEST_PATH" => \strtolower(
                \parse_url(
                    \filter_input(
                        INPUT_SERVER,
                        'REQUEST_URI',
                        FILTER_SANITIZE_URL
                    ),
                    PHP_URL_PATH
                )
            ),
            "REQUEST_METHOD" => \strtolower(
                \filter_input(
                    INPUT_SERVER,
                    'REQUEST_METHOD',
                    FILTER_SANITIZE_FULL_SPECIAL_CHARS
                )
            ),
            "API_BASEPATH" => \basename(__DIR__),
        ];
        $REQUEST_SETTINGS['REQUEST_DIR'] = '.' . DIRECTORY_SEPARATOR . \str_replace(
            ['/' . $REQUEST_SETTINGS['API_BASEPATH'] . '/', '.'],
            '',
            $REQUEST_SETTINGS['REQUEST_PATH']
        );
        $REQUEST_SETTINGS['REQUEST_FILE'] = \str_replace(
            DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR,
            DIRECTORY_SEPARATOR,
            $REQUEST_SETTINGS['REQUEST_DIR'] . DIRECTORY_SEPARATOR . $REQUEST_SETTINGS['REQUEST_METHOD'] . '.php'
        );
        self::$REQUEST_SETTINGS = $REQUEST_SETTINGS;
    }
    
    static public function json_output(
        array $data,
        int $httpCode = 200,
        bool $cacheable = false,
        int $flags = JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION
    ) : void
    {
        _statusCodes::statusCode($httpCode);
        if(!$cacheable) {
            self::noCache();
        }
        _contentTypes::contentType('json');
        echo json_encode($data, $flags);
    }
    
    static public function noCache() : void
    {
        header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
        header('Expires: Fri, 13 Dec 2013 06:00:00 GMT');
        header('Cache-Control: no-cache');
        header('Pragma: no-cache');
    }
    
    static public function invoke() : void
    {
        $requestPath = \strtolower(self::$REQUEST_SETTINGS['REQUEST_PATH']);
        $dir = '.' . DIRECTORY_SEPARATOR . \str_replace(['/api/', '.'], '', $requestPath);
        $file = \str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $dir . DIRECTORY_SEPARATOR . self::$REQUEST_SETTINGS['REQUEST_METHOD'] . '.php');
        
        if (\file_exists($file)) {
            require_once $file;
        } else {
            if(\file_exists($dir)) {
                $error = "REQUEST_METHOD_NOT_ALLOWED";
                $message = "The endpoint doesn't seem to support this request method";
                $statusCode = 405;
            } else {
                $error = "API_ENDPOINT_NOT_FOUND";
                $message = "Unknown endpoint";
                $statusCode = 404;
            }
            self::json_output([
                "error" => $error,
                "message" => $message,
                "requested" => self::$REQUEST_SETTINGS['REQUEST_PATH'],
                "method" => self::$REQUEST_SETTINGS['REQUEST_METHOD']
            ], $statusCode);
        }
    }
}
