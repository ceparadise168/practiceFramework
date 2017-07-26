<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request  = Request::createFromGlobals();

$map = array(
        '/hello' => 'hello',
        '/bye' => 'bye',
        );

$path = $request->getPathInfo();
if (isset($map[$path])) {
    ob_start();
    extract($request->query->all(), EXTT_SKIP);
    include sprintf(__DIR__.'/../src/pages/%s.php', $map[$path]);
    $response = new Response(ob_get_clean());
} else {
    $response->setStatusCode(404);
}

$response->send();
