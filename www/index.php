<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/www/templates/');
$twig = new \Twig\Environment($loader, [
    'cache' => dirname(__DIR__) . '/cache',
]);

echo $twig->render('albums.html.twig', []);

