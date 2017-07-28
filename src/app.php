<?php

use Symfony\Component\Routing;
use Symfony\Component\HttpFoundation\Response;

function is_leap_year($year = null) {

    if (null === $year) {
        $year = date('Y');
    }
    return 0 ===$year % 400 || (0 === $year % 4 && 0 !==$year % 100);
}

class LeapYearController
{
    public function indexAction($year)
    {
        if (is_leap_year($year)) {
            return new Response('Yep, this is a leap year!');
    }
        return new Response('Nope, this is not a leap year.');
    }
}

$routes = new Routing\RouteCollection();
$routes->add(
    'hello',
     new Routing\Route('/hello/{name}',
     array(
        'name' => 'World',
        '_controller' => function ($request) {
            $request->attributes->set('foo', 'bar');

            $response = render_template($request);
            $response->headers->set('Content-Type', 'text/plain');

            return $response;
        }
)));
$routes->add(
    'bye',
    new Routing\Route('bye'),
    array(
        '_controller' => function ($request) {
            $request->attributes->set('foo', 'bar');
            $request = render_template($request);
            $response->headers->set('Content-Type', 'text/plain');

            return $response;
        }
));
$routes->add(
    'leap_year',
    new Routing\Route('/is_leap_year/{year}',
    array(
        'year' => null,
        //'_controller' => array (new LeapYearController(), 'indexAction'),
        '_controller' => 'Calendar\Controller\LeapYearController::indexAction',
)));

return $routes;
