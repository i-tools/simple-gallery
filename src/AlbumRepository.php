<?php

declare(strict_types=1);

namespace App;

use App\Album;

/**
 * Class AlbumRepository
 * @package App
 */
class AlbumRepository
{
    use AlbumTrait;

    /**
     * @var string $uri
     */
    private $uri;

    /**
     * @var string $path
     */
    private $path;

    /**
     * @var array $collections
     */
    private $collections = [];

    /**
     * AlbumRepository constructor.
     * @param string $basePath
     * @param string $baseUri
     * @throws \Exception
     */
    public function __construct(string $basePath, string $baseUri)
    {
        $this->path = $basePath;
        $this->uri = $baseUri;

        if ( !$this->checkRepositoryExist() )
        {
            throw new \ErrorException('Albums directory not found', 500);
        }

        //$this->buildAlbumsCollection();
    }

    /**
     * @return string
     */
    public function getURI(): string
    {
        return $this->uri;
    }

    /**
     * @param string $baseUri
     * @return AlbumRepository
     */
    public function setURI(string $baseUri): AlbumRepository
    {
        $this->uri = $baseUri;

        return $this;
    }

    /**
     * @return array
     * @throws \ErrorException
     */
    protected function getDirAlbums(): array
    {
        $result = [];

        $repositoryDirs = scandir($this->path);

        foreach ($repositoryDirs as $key => $value)
        {
            if ( !in_array($value, [".",".."]) )
            {
                $path = $this->path . DIRECTORY_SEPARATOR . $value;

                $albumCover = $this->getAlbumCoverImage($value);

                if (is_dir($path) && $albumCover)
                {
                    $result[] = ['name' => $value, 'cover' => $albumCover];
                }
            }
        }

        return $result;
    }

    /**
     * @param string $albumName
     * @return string
     * @throws \ErrorException
     */
    protected function getAlbumCoverImage(string $albumName): string
    {
        $path = $this->path . DIRECTORY_SEPARATOR . $albumName;

        if ( !is_dir($path) ) {
            throw new \ErrorException('Album directory not found', 500);
        }

        $albumDir = scandir($path);

        foreach ($albumDir as $key => $value)
        {
            $fileInfo = pathinfo($value);

            if ( in_array($fileInfo['extension'], $this->extensions) && $fileInfo['filename'] == $albumName )
            {
                return $fileInfo['basename'];
            }
        }
    }

    /**
     * @throws \ErrorException
     */
    protected function getAlbumsCollections(): void
    {
        $result = $this->getDirAlbums();

        foreach ($result as $item)
        {
            $this->collections[] = new Album($this->path . '/' . $item['name'], $this->uri, $item['name'], $item['cover']);
        }
    }

    /**
     * @return array
     * @throws \ErrorException
     */
    public function getAlbums(): array
    {
        $this->getAlbumsCollections();

        return $this->collections;
    }

    /**
     * @param string $albumName
     * @return \App\Album
     */
    public function getAlbumByName(string $albumName): Album
    {
        try {
            $album = new Album(
                $this->getPath() . DIRECTORY_SEPARATOR . $albumName,
                $this->uri,
                $albumName,
                $this->getAlbumCoverImage($albumName)
             );
        } catch (\Exception $exception) {
            die($exception->getMessage());
        }

        $files = [];

        $path = $this->path . DIRECTORY_SEPARATOR . $albumName;

        $albumDir = scandir($path);
        foreach ($albumDir as $key => $value)
        {
            $fileInfo = pathinfo($value);

            if ( in_array($fileInfo['extension'], $this->extensions) && $fileInfo['filename'] !== $albumName )
            {
                $files[] = $fileInfo['basename'];
            }
        }

        $album->setFiles($files);

        return $album;
    }
}