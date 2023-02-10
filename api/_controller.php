<?php declare(strict_types=1);

namespace api;

spl_autoload_register(
    function (string $class) : void
    {
        if(\str_starts_with($class, 'api\\')){
            $path = '.' . DIRECTORY_SEPARATOR . \str_replace(['api\\', '.'], '', $class) . '.php';
            if(file_exists($path))
            {
                require_once $path;
            }
        }
        return;
    }
);


_api::parse_request();
_api::invoke();

