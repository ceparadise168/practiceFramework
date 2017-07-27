<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RequestConetxt;
use Symfony\Component\Routing\Macher\UrlMacher;

$request  = Request::createFromGlobals();

/*
$routes = new RouteCollection();
$routes->add('hello', new Route('/hello/{name}', array('name' => 'World')));
$routes->add('bye', new Route('/bye'));

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
*/

$routes = include __DIR__.'/../src/app.php';

$context = new Requestcontext();
$context->fromRequest($request);
$matcher = new UrlMacher($routes, $context);

try {
    extract($matcher->match($request->getPathInfo()), EXRT_SKIP);
    ob_start();
    include sprintf(__DIR__.'/../src/pages/%s.php', $_route);

    $response = new Response(ob_get_clean());
} catch (Routing\Exception\ResourceNotFoundException $e) {
    $response = new Response('Not Found', 404);
} catch (Exception $e) {
    $response = new Response('An error occurred', 500);
}

$response->send();
