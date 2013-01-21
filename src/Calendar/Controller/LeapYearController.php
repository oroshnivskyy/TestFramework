<?php
namespace Calendar\Controller;
use Symfony\Component\HttpFoundation\Response;
use Calendar\Model\LeapYear;

class LeapYearController{
    public function indexAction( $year ){
        $leapYear = new LeapYear();
        if ( $leapYear->isLeapYear( $year ) ){
            return new Response( 'Yep, this is a leap year!' );
        }

        return new Response( 'Nope, this is not a leap year.' );
    }
    private static function is_leap_year( $year = null ){
        if ( !isset( $year ) ){
            $year = date( 'Y' );
        }

        return 0 == $year % 400 || ( 0 == $year % 4 && 0 != $year % 100 );
    }
}
