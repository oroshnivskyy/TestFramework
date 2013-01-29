<?php
namespace Calendar\Controller;
use Symfony\Component\HttpFoundation\Response;
use Calendar\Model\LeapYear;

class LeapYearController{
    public function indexAction( $year ){
        $leapYear = new LeapYear();
        if ( $leapYear->isLeapYear( $year ) ){
            $response = new Response( 'Yep, this is a leap year!' . time() );
        }
        else{
            $response = new Response( 'Nope, this is not a leap year.' . time() );
        }

        $response->setPublic();
        $response->setEtag( 'etagtag' );
        $response->setLastModified( new \DateTime() );
        $response->setMaxAge( 10 );
        $response->setSharedMaxAge( 10 );
        return $response;
    }
    private static function is_leap_year( $year = null ){
        if ( !isset( $year ) ){
            $year = date( 'Y' );
        }

        return 0 == $year % 400 || ( 0 == $year % 4 && 0 != $year % 100 );
    }
}
