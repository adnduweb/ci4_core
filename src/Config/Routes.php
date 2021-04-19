<?php


$routes->group('api/v1', ['namespace' => '\Adnduweb\Ci4Core\Controllers\API\v1', 'filter' => 'cors'], function($routes)
{
    //$routes->resource('tests');
    //$routes->options('tests', 'Tests::index');
    $routes->match(['get', 'options'], 'tests', 'Tests::index');
});