<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;
use Symfony\Component\HttpKernel;

function render_template(Request $request)
{
    extract($request->attributes->all(), EXTR_SKIP);
    ob_start();
    include sprintf(__DIR__.'/../src/pages/%s.php', $_route);

    return new Response(ob_get_clean());
}

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

$controllerResolver = new HttpKernel\Controller\ControllerResolver();
$argumentResolver = new HttpKernel\Controller\ArgumentResolver();

/**
 * Based on the information stored in the RouteCollection instance whitch in the app.php,
 * a UrlMatcher instance can match URL paths.
 */ 
/*
try {
    $request->attributes->add($matcher->match($request->getPathInfo()));

    $controller = $controllerResolver->getController($request);
    $arguments = $argumentResolver->getArguments($request, $controller);

    $response = call_user_func_array($controller, $arguments);
   // $response = call_user_func($request->attributes->get('_controller'), $request);
} catch (Routing\Exception\ResourceNotFoundException $e) {
    $response = new Response('Not Found', 404);
} catch (Exception $e) {
    $response = new Response('An error occurred', 500);
}
*/

$framework = new Simplex\Framework($matcher, $controllerResolver, $argumentResolver);
//var_dump($framework->handle($request));
$resposne = $framework->handle($request);
//var_dump($response);
//$response->send();
$a = $framework->handle($request);
//var_dump($a);
$a->send();
//$framework->handle($request)->send();
