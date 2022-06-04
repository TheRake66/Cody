<?php
use Kernel\Url\Router as r;
use Controller as c;
use Api as a;


/*
r::notfound('/');
r::default('/');
r::add([ '/' => c\::class ]);
*/

/*
r::add([
    '/api' => [ 
        a\::class, [
            r::METHOD_GET,
            r::METHOD_POST
    ]],
    '/api/{id}' => [ 
        a\::class, [
            r::METHOD_GET,
            r::METHOD_PUT,
            r::METHOD_DELETE,
            r::METHOD_PATCH
    ]]
]);
*/


?>