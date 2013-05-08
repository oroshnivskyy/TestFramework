<?php
use Symfony\Component\DependencyInjection;
use Symfony\Component\DependencyInjection\Reference;

function getContainer($debug)
{
    $routes = include BASE_PATH . '/app/app.php';
    $di = new Phalcon\DI();
    $di->set('context', 'Symfony\Component\Routing\RequestContext');
    if ($debug) {
        $dumper = new Symfony\Component\Routing\Matcher\Dumper\PhpMatcherDumper($routes);
        file_put_contents(BASE_PATH . '/cache/ProjectUrlMatcher.php', $dumper->dump());
        $di->set(
            'matcher',
            array(
                'className' => 'Symfony\Component\Routing\Matcher\UrlMatcher',
                'arguments' => array(
                    array('type' => 'parameter', 'value' => $routes),
                    array('type' => 'service', 'name' => 'context')
                )
            )
        );
    } else {
        if (!file_exists(BASE_PATH . '/cache/ProjectUrlMatcher.php')) {
            $dumper = new Symfony\Component\Routing\Matcher\Dumper\PhpMatcherDumper($routes);
            file_put_contents(BASE_PATH . '/cache/ProjectUrlMatcher.php', $dumper->dump());
        }
        include(BASE_PATH . '/cache/ProjectUrlMatcher.php');
        $di->set(
            'matcher',
            array(
                'className' => 'ProjectUrlMatcher',
                'arguments' => array(
                    array('type' => 'service', 'name' => 'context')
                )
            )
        );
    }
    $di->set('resolver', 'Symfony\Component\HttpKernel\Controller\ControllerResolver');
    $di->set(
        'listener.router',
        array(
            'className' => 'Symfony\Component\HttpKernel\EventListener\RouterListener',
            'arguments' => array(
                array('type' => 'service', 'name' => 'matcher')
            )
        )
    );
    $di->set(
        'listener.response',
        array(
            'className' => 'Symfony\Component\HttpKernel\EventListener\ResponseListener',
            'arguments' => array(
                array('type' => 'parameter', 'value' => 'UTF-8')
            )
        )
    );

    $di->set(
        'listener.exception',
        array(
            'className' => 'Symfony\Component\HttpKernel\EventListener\ExceptionListener',
            'arguments' => array(
                array('type' => 'parameter', 'value' => 'Simple\\ErrorController::exceptionAction')
            )
        )
    );
    $di->set(
        'dispatcher',
        array(
            'className' => 'Symfony\Component\EventDispatcher\EventDispatcher',
            'calls' => array(
                array(
                    'method' => 'addSubscriber',
                    'arguments' => array(
                        array('type' => 'service', 'name' => 'listener.router'),
                    )
                ),
                array(
                    'method' => 'addSubscriber',
                    'arguments' => array(
                        array('type' => 'service', 'name' => 'listener.response'),
                    )
                ),
                array(
                    'method' => 'addSubscriber',
                    'arguments' => array(
                        array('type' => 'service', 'name' => 'listener.exception'),
                    )
                )
            )
        )
    );
    $di->set(
        'framework',
        array(
            'className' => 'Simple\Framework',
            'arguments' => array(
                array('type' => 'service', 'name' => 'dispatcher'),
                array('type' => 'service', 'name' => 'resolver'),
            )
        )
    );
    return $di;
}