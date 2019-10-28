<?php

declare(strict_types=1);

namespace App;

class Album
{
    use AlbumTrait;

    /**
     * @var string $uri
     */
    private $uri;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $cover;
     */
    private $cover;

    /**
     * @var array $files
     */
    private $files = [];

    /**
     * Album constructor.
     * @param string $path
     * @param string $uri
     * @param string $name
     * @param string|null $cover
     */
    public function __construct(string $path, string $uri, string $name, string $cover = null)
    {
        $this->path = $path;
        $this->uri = $uri;
        $this->name = $name;
        $this->cover = $cover;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $uri
     */
    public function setURI(string $uri)
    {
        $this->uri = $uri;
    }

    /**
     * @return string
     */
    public function getURI(): string
    {
        return $this->uri;
    }

    /**
     * @param string $cover
     */
    public function setCover(string $cover)
    {
        $this->cover = $cover;
    }

    /**
     * @return string|null
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * @return array
     */
    public function getAlbumFiles(): array
    {
        $result = [];

        $path = $this->path . DIRECTORY_SEPARATOR;

        $albumDir = scandir($path);

        foreach ($albumDir as $key => $value)
        {
            $fileInfo = pathinfo($value);

            if ( in_array($fileInfo['extension'], $this->extensions) && $fileInfo['filename'] !== $this->name )
            {
                $result[] = $fileInfo['basename'];
            }
        }

        return $result;
    }
}

