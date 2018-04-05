<?php

require_once "vendor/autoload.php";

try {

    \VS\Response\ResponseConstants::setViewPath(__DIR__ . '/app/views/');

    /**
     * @var \VS\Router\RouterInterface $router
     */
    $router     = \VS\Router\Router::getInstance(
        \VS\Url\Url::getInstance()->current(), // current url string
        \VS\Request\Request::getInstance()->method() // current request method
    );

    /**
     * @var \VS\Router\RouteItemInterface $routeItem
     */
    $routeItem = $router
        ->get('/', "TicTacToeController")
        ->get('/board', 'TicTacToeController@getBoard')
        ->post('/move', 'TicTacToeController@move')
        ->getRouteItem();

    $controllerNamespace = "App\\Controller\\%s";

    $response = \VS\General\DIFactory::injectMethod(sprintf(
        $controllerNamespace,
        $routeItem->getController()
    ), $routeItem->getMethodName(), ... $routeItem->getParams());

    if ($response instanceof \VS\Response\Drivers\DriverInterface) {
        print $response;
    }
    exit;

}catch (Throwable $exception) {
    exit($exception->getMessage());
}