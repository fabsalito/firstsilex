<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$blogPosts = array(
    1 => array(
        'date'      => '2011-03-29',
        'author'    => 'igorw',
        'title'     => 'Using Silex',
        'body'      => '...',
    ),
    2 => array(
        'date'      => '2013-02-01',
        'author'    => 'fabsal',
        'title'     => 'Using Symfony',
        'body'      => '...',
    ),
);

$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html', array());
})
->bind('homepage')
;

$app->get('/blog/show/{id}', function(Silex\Application $app, $id) use($blogPosts) {
    $output = '';

    if (!isset($blogPosts[$id])){
        $app->abort(404, "El post $id no existe!!!");
    }

    $post = $blogPosts[$id];

    //foreach ($blogPosts as $post) {
        $output .= $post['title'];
        $output .= '<br />';
    //}

    return $output;
});

$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    $page = 404 == $code ? '404.html' : '500.html';

    return new Response($app['twig']->render($page, array('code' => $code)), $code);
});
