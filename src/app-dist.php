<?php

use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Sergiors\Silex\Provider\DoctrineCacheServiceProvider;

$app = new Application();
$app->register(new ServiceControllerServiceProvider());
$app->register(new AssetServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new HttpFragmentServiceProvider());
$app['twig'] = $app->extend('twig', function ($twig, $app) {
    // add custom globals, filters, tags, ...
    return $twig;
});

$app->register(new SilexGuzzle\GuzzleServiceProvider(), array(
    'guzzle.base_uri' => 'https://api.flickr.com',
    'guzzle.timeout' => 3.14,
    'guzzle.request_options' => []
));

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'path' => __DIR__ . '/app.db',
        'host' => '',
        'dbname' => '',
        'user' => '',
        'password' => '',
        'charset' => 'utf8'
    ),
));

$app->register(new DoctrineCacheServiceProvider(), [
    'cache.options' => [
        'driver' => 'filesystem',
        'namespace' => 'myapp',
        'cache_dir' => __DIR__ . '/../var/cache/doctrine'
    ]
]);

return $app;
