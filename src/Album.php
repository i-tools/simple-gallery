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
     * @throws \ErrorException
     */
    public function __construct(string $path, string $uri, string $name, string $cover = null)
    {
        $this->path = $path;
        $this->uri = $uri;
        $this->name = $name;
        $this->cover = $cover;

        if ( !$this->checkRepositoryExist() )
        {
            throw new \ErrorException('Album directory not found', 500);
        }
    }

    /**
     * @param string $name
     * @return Album
     */
    public function setName(string $name): Album
    {
        $this->name = $name;

        return $this;
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
     * @return Album
     */
    public function setURI(string $uri): Album
    {
        $this->uri = $uri;

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
     * @param string $cover
     * @return Album
     */
    public function setCover(string $cover): Album
    {
        $this->cover = $cover;

        return $this;
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
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @param array $files
     * @return Album
     */
    public function setFiles(array $files): Album
    {
        $this->files = $files;

        return $this;
    }
}

