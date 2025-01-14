<?php

declare(strict_types=1);

namespace PHPSu\Config;

use Exception;

use function array_merge;

/**
 * @api
 */
final class Database implements DockerTraitSupportInterface
{
    use AddDockerTrait;

    private string $name;
    private DatabaseConnectionDetails $connectionDetails;
    /** @var string[] */
    private array $excludes = [];
    private bool $noDefiner = true;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Database
    {
        $this->name = $name;
        return $this;
    }

    /**
     * todo: remove
     * @deprecated will be removed in 3.0
     */
    public function getUrl(): string
    {
        return $this->connectionDetails->__toString();
    }

    public function setUrl(string $url): Database
    {
        $this->connectionDetails = DatabaseConnectionDetails::fromUrlString($url);
        return $this;
    }

    public function getConnectionDetails(): DatabaseConnectionDetails
    {
        if ($this->isDockerEnabled() && $this->getContainer() === '') {
            $this->setContainer($this->connectionDetails->getHost());
            $this->connectionDetails->setHost('127.0.0.1');
        }
        if ($this->isDockerEnabled() && $this->connectionDetails->getPort() !== 3306) {
            $this->connectionDetails->setPort(3306);
        }
        return $this->connectionDetails;
    }

    public function setConnectionDetails(DatabaseConnectionDetails $connectionDetails): Database
    {
        $this->connectionDetails = $connectionDetails;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getExcludes(): array
    {
        return $this->excludes;
    }

    /**
     * @param string[] $excludes
     * @return Database
     * @return Database
     */
    public function addExcludes(array $excludes): Database
    {
        $this->excludes = array_merge($this->excludes, $excludes);
        return $this;
    }

    public function addExclude(string $exclude): Database
    {
        $this->excludes[] = $exclude;
        return $this;
    }

    public function shouldDefinerBeRemoved(): bool
    {
        return $this->noDefiner;
    }

    public function setRemoveDefinerFromDump(bool $removeIt): Database
    {
        $this->noDefiner = $removeIt;
        return $this;
    }
}
