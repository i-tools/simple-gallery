<?php

declare(strict_types=1);

namespace App;

use App\Traits\AlbumTrait;

/**
 * Class AlbumRepository
 * @package App
 */
class AlbumRepository
{
    use AlbumTrait;

    const IMAGE_EXTENSIONS = '{jpg,JPG,jpeg,JPEG,png,PNG}';

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
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return AlbumRepository
     * @throws \ErrorException
     */
    public function setPath(string $path): AlbumRepository
    {
        $this->path = $path;

        if (!$this->checkRepositoryExist()) {
            throw new \ErrorException('Albums directory not found', 500);
        }

        return $this;
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
     * @throws \ErrorException
     */
    public function setURI(string $baseUri): AlbumRepository
    {
        if (empty($baseUri)) {
            throw new \ErrorException('Base URI can\'t be empty', 500);
        }
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

        $repositoryDirs = glob($this->getPath() . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR);

        foreach ($repositoryDirs as $path) {
            $albumName = basename($path);
            $albumCover = $this->getAlbumCoverImage($albumName);
            $result[] = ['name' => $albumName, 'cover' => $albumCover];
        }

        return $result;
    }

    /**
     * @param string $albumName
     * @return string
     * @throws \ErrorException
     */
    protected function getAlbumCoverImage(string $albumName)
    {
        $path = $this->path . DIRECTORY_SEPARATOR . $albumName;

        if (!is_dir($path)) {
            throw new \ErrorException('Album directory not found', 500);
        }

        $fileName = glob($path . DIRECTORY_SEPARATOR . $albumName . '.' . self::IMAGE_EXTENSIONS, GLOB_BRACE)[0];

        return basename($fileName);
    }

    /**
     * @throws \ErrorException
     */
    protected function getAlbumsCollections(): void
    {
        $result = $this->getDirAlbums();

        foreach ($result as $item) {
            $this->collections[] = new Album($this->path . DIRECTORY_SEPARATOR . $item['name'], $this->uri, $item['name'], $item['cover']);
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
     * @return array
     */
    private function getAlbumFiles(string $albumName): array
    {
        $files = [];

        $path = $this->path . DIRECTORY_SEPARATOR . $albumName;

        $albumFiles = glob($path . DIRECTORY_SEPARATOR  . '*.' . self::IMAGE_EXTENSIONS, GLOB_BRACE);

        foreach ($albumFiles as $file) {
            $fileInfo = pathinfo($file);

            if ($fileInfo['filename'] !== $albumName) {
                $files[] = basename($file);
            }
        }

        return $files;
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

        $album->setFiles($this->getAlbumFiles($albumName));

        return $album;
    }
}
