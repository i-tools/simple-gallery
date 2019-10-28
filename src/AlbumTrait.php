<?php

declare(strict_types=1);

namespace App;

/**
 * Trait AlbumTrait
 * @package App
 */
trait AlbumTrait
{
    /**
     * @var string $path
     */
    private $path;

    /**
     * @var array $extensions
     */
    private $extensions = ['jpg', 'JPG', 'jpeg', 'JPEG', 'png', 'PNG', 'webp'];

    /**
     * @return bool
     */
    protected function checkRepositoryExist(): bool
    {
        return is_dir($this->path);
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
     */
    public function setPath(string $path)
    {
        $this->path = $path;
    }
}