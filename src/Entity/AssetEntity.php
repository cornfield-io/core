<?php

declare(strict_types=1);

namespace Cornfield\Core\Entity;

final class AssetEntity
{
    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $integrity;

    /**
     * @var int
     */
    private $version;

    /**
     * AssetEntity constructor.
     *
     * @param string $content
     * @param int    $version
     * @param string $integrity
     */
    public function __construct(string $content, int $version, string $integrity)
    {
        $this->content = $content;
        $this->version = $version;
        $this->integrity = $integrity;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getIntegrity(): string
    {
        return $this->integrity;
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }
}
