<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;

$request = Request::createFromGlobals();
$routes = include __DIR__.'/../src/app.php';

/**
 * get request context from request
 */
$context = new Routing\RequestContext();
$context->fromRequest($request);

/**
 * filter route from routes in app.php whitch mathchs the request context
 */
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

/**
 * Based on the information stored in the RouteCollection instance whitch in the app.php,
 * a UrlMatcher instance can match URL paths.
 */ 
try {
    extract($matcher->match($request->getPathInfo()), EXTR_SKIP);
    ob_start();
    include sprintf(__DIR__.'/../src/pages/%s.php', $_route);

    $response = new Response(ob_get_clean());
} catch (Routing\Exception\ResourceNotFoundException $e) {
    $response = new Response('Not Found', 404);
} catch (Exception $e) {
    $response = new Response('An error occurred', 500);
}

$response->send();
