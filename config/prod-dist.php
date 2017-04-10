<?php

// configure your app for the production environment

$app['twig.path'] = array(__DIR__ . '/../templates');
$app['twig.options'] = array('cache' => __DIR__ . '/../var/cache/twig');

$app['flickr.api_key'] = '';
$app['flickr.user_id'] = '';




