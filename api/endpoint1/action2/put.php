<?php declare(strict_types=1);

namespace api\endpoint1\action2;
use api\_api AS API;

$message = sprintf(
    'Test of %s on %s OK! :)',
    API::$REQUEST_SETTINGS['REQUEST_METHOD'],
    API::$REQUEST_SETTINGS['REQUEST_PATH']
);

API::json_output([
    "ok" => $message
]);
