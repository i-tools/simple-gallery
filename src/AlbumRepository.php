<?php

declare(strict_types=1);

namespace App;

use App\Album;

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

        $this->buildAlbumsCollection();
    }

    /**
     * @return array
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
     * @return mixed
     */
    protected function getAlbumCoverImage(string $albumName)
    {
        $path = $this->path . DIRECTORY_SEPARATOR . $albumName;

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

    protected function buildAlbumsCollection(): void
    {
        $result = $this->getDirAlbums();

        foreach ($result as $item)
        {
            $this->collections[] = new Album($this->path . '/' . $item['name'], $this->uri, $item['name'], $item['cover']);
        }
    }

    /**
     * @return array
     */
    public function getAlbums(): array
    {
        return $this->collections;
    }
}