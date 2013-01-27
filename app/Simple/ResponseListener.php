<?php
namespace Simple;

class ResponseListener {
    public function onResponse( ResponseEvent $event )
    {
        $event->getResponse()->headers->set( 'Framework', "Simple" );
    }
}
