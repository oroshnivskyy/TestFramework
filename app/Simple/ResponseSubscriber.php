<?php
namespace Simple;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
class ResponseSubscriber implements EventSubscriberInterface {

    public function onResponse( ResponseEvent $event )
    {
        $event->getResponse()->headers->set( 'Framework', "Simple" );
    }

    public static function getSubscribedEvents()
    {
        return array('response' => array( 'onResponse', -255 ) );
    }
}
