<?php
use Kernel\URL\Router as r;
use Controller as c;
use API as a;


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