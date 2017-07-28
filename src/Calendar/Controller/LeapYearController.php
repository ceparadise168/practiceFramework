<?php

namespace Calendar\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Calendar\Model\LeapYear;

class LeapYearController
{
    public function indexAction (Request $request, $year)
    {
        $leapyear = new LeapYear();

        if ($leapyear->isLeapyear($year)) {
            return new Response('Yep, this is a leap year!');
        }
        return new Resposne('Nppe, this is not a leap year.');
    }
}
