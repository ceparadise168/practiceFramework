<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;
use Symfony\Component\HttpKernel;
use Symfony\Component\EventDispatcher\EventDispatcher;

/*
$dispatcher = new EventDispatcher();
$dispatcher->addListener('response', function (Simplex\ResponseEvent $event) {
    $response = $event->getResponse();

    if (
        $response->isRedirection()
        || ($response->headers->has('Content-Type') && false === strpos($response->headers->get('Content-Type'), 'html'))
        || 'html' !== $event->getRequest()->getRequestFormat()
        ) {
            return;
        }
        $response->setContent($response->getContent().'GA CODE');
});
*/

$dispatcher = new EventDispatcher();
/*
$dispatcher->addListener('response', array(new Simplex\ContentLengthListener(), 'onResponse'), -255);
$dispatcher->addListener('response', array(new Simplex\GoogleListener(), 'onResponse'));
*/
$dispatcher->addSubscriber(new Simplex\ContentLengthListener());
$dispatcher->addSubscriber(new Simplex\GoogleListener());


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

$framework = new Simplex\Framework($matcher, $controllerResolver, $argumentResolver, $dispatcher);
$response = $framework->handle($request);
$response->send();
