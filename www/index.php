<?php

declare(strict_types=1);

error_reporting(E_ALL & ~E_NOTICE);

if (php_sapi_name() === 'cli-server' && preg_match('/\.(?:png|jpg|jpeg|gif|js|css)$/', $_SERVER["REQUEST_URI"])) {
    return false;    // сервер возвращает файлы напрямую.
}

require dirname(__DIR__) . '/vendor/autoload.php';

use App\Album;
use App\AlbumRepository;

$loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/www/templates');
$twig = new \Twig\Environment($loader, [
    'cache' => dirname(__DIR__) . '/cache',
    'debug' => true
]);


$twig->addExtension(new \Twig\Extension\DebugExtension());

list($_path_uri, $_get_vars) = preg_split("/\?/", $_SERVER["REQUEST_URI"], 2);
$uriArray=preg_split("/\//", $_path_uri);

switch ($uriArray[1])
{
    case 'view':
    {
        $albumName = $uriArray[2];

        try {
            $album = new Album(dirname(__DIR__) . '/www/media' . $albumName, '/media', $albumName);
        } catch (Exception $exception) {
            die($exception->getMessage());
        }

        echo $twig->render('album-view.html.twig', [
            'album' => $album,
            'files' => $album->getAlbumFiles()
        ]);
        break;
    }
    default:
    {
        try {
            $albums = new AlbumRepository(dirname(__DIR__) . '/www/media', '/media');
        } catch (Exception $exception) {
            die($exception->getMessage());
        }

        echo $twig->render('albums.html.twig', [
            'albums' => $albums->getAlbums()
        ]);
    }
}

