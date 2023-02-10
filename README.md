# TSKEL-API
### _A Basic REST API Skeleton for PHP_

#### Version


_**TSKEL-API**_ is a standard basic REST API architecture for PHP projects that don't have the possibility (or the need) to have a robust framework along with it.

It was designed to run on apache, with PHP, but it is also compatible with nginx (you just need to configure the nginx rewrite rules in the same way as expressed in `.htaccess` in apache).

What this skeleton can currently do:

- allows handling all requests in the installation path and redirecting to php files within the file system that correspond to the respective uri request, importing, in that folder, the file with the name of the HTTP verb used (get.php, post.php , etc)
- provide error messages if the API path is not found (http/404) or if the file with the method does not exist (http/405)
- provides a generic function to output any array as a json object, with the respective status code

What this skeleton currently cannot do:

- this skeleton doesn't handle authentication or tokens (you need to do that unless your API is absolutely public)
- they don't sanitize or prepare input data in any way

## Where to use

- In projects that need basic REST API and deployment speed
- In projects that don't need auth control (but you can use it if you write your own code)

## Examples

If this project is in /api/ or your server (such as https://localhost/api/) you will get the following behavior:

> `GET https://localhost/api/endpoint1/action1` will be handled by file `./api/endpoint1/action1/get.php`

> `POST https://localhost/api/endpoint1/action1` will be handled by file `./api/endpoint1/action1/post.php`

If the php file with the method name does not exist but the folder does, it will throw an http/405 Method Not Allowed error.
If the folder with the same path as the request does not exist, http/404 Not Found will be generated.

## Output data
All the files can access the static function `api\_api::json_output()`, who have the `$REQUEST_SETTINGS` variable, with handler informations and also can use the `json_output` function, who will send the api response to users, but you are free to write your own response function.

```php

    static public function json_output(
        array $data,
        int $httpCode = 200,
        bool $cacheable = false,
        int $flags = JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION
    ) : void

```

`$data` is the array who will be sent as response in json
`$httpCode` is the http status code of the response
`$cacheable` defines if response is cacheable by client, or not
`$flags` allow you to define your needed flags to hph function json_encode()
