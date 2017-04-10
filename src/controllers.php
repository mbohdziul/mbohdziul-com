<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app->get('/', function () use ($app) {
    checkPhotosCache($app);
    return $app['twig']->render('index.html.twig', array(
        'photoSets' => $app['cache']->fetch('photoSets'),
        'photos' => $app['cache']->fetch('photos')
    ));
})->bind('homepage');

$app->get('/cv', function () use ($app) {
    return $app['twig']->render('cv.html.twig', array());
})->bind('cv');

$app->get('/about', function () use ($app) {
    return $app['twig']->render('about.html.twig', array());
})->bind('about');

$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/' . $code . '.html.twig',
        'errors/' . substr($code, 0, 2) . 'x.html.twig',
        'errors/' . substr($code, 0, 1) . 'xx.html.twig',
        'errors/default.html.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});

//----------------------------------------------------------------------------------------------------------------------
function checkPhotosCache($app)
{
    if (!$app['cache']->contains('photoSets')) {
        $app['cache']->save('photoSets', $app['db']->fetchAll('SELECT * FROM flickr__photo_set'));
        dump('DB :: loading sets');
    }

    if (!$app['cache']->contains('photos')) {
        $app['cache']->save('photos', $app['db']->fetchAll('SELECT * FROM flickr__photo'));
        dump('DB :: loadingphotos');
    }
}