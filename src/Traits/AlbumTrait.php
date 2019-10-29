<?php

declare(strict_types=1);

namespace App\Traits;

/**
 * Trait AlbumTrait
 * @package App\Traits
 */
trait AlbumTrait
{
    /**
     * @var string $path
     */
    private $path;

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
