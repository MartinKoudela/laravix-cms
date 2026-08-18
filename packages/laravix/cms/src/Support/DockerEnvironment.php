<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Support;

use InvalidArgumentException;

class DockerEnvironment
{
    public const DATABASES = ['mysql', 'pgsql', 'sqlite'];

    private const COMPANIONS = ['worker', 'scheduler'];

    private const WITHOUT_VOLUME = ['mailpit', 'worker', 'scheduler'];

    private const WITHOUT_HEALTHCHECK = ['mailpit', 'worker', 'scheduler'];

    public function __construct(
        public readonly string $database = 'mysql',
        public readonly bool $meilisearch = false,
        public readonly bool $mailpit = false,
        public readonly bool $redis = false,
        public readonly bool $worker = false,
        public readonly bool $scheduler = false,
        public readonly int $appPort = 80,
        public readonly int $vitePort = 5173,
        public readonly array $forwardPortOverrides = [],
    ) {
        if (! in_array($this->database, self::DATABASES, true)) {
            throw new InvalidArgumentException("Unsupported database service [{$this->database}].");
        }
    }

    public function defaultForwardPorts(): array
    {
        $ports = match ($this->database) {
            'mysql' => ['FORWARD_DB_PORT' => 3306],
            'pgsql' => ['FORWARD_DB_PORT' => 5432],
            'sqlite' => [],
        };

        if ($this->meilisearch) {
            $ports['FORWARD_MEILISEARCH_PORT'] = 7700;
        }

        if ($this->mailpit) {
            $ports['FORWARD_MAILPIT_PORT'] = 1025;
            $ports['FORWARD_MAILPIT_DASHBOARD_PORT'] = 8025;
        }

        if ($this->redis) {
            $ports['FORWARD_REDIS_PORT'] = 6379;
        }

        return $ports;
    }

    public function forwardPorts(): array
    {
        return array_merge($this->defaultForwardPorts(), $this->forwardPortOverrides);
    }

    public function withResolvedPorts(callable $isAvailable): self
    {
        $claimed = [];

        $resolve = function (int $preferred) use ($isAvailable, &$claimed): int {
            $port = $preferred;

            while ($port < 65535 && (in_array($port, $claimed, true) || ! $isAvailable($port))) {
                $port++;
            }

            $claimed[] = $port;

            return $port;
        };

        $appPort = $resolve($this->appPort);
        $vitePort = $resolve($this->vitePort);
        $overrides = [];

        foreach ($this->defaultForwardPorts() as $key => $port) {
            $resolved = $resolve($port);

            if ($resolved !== $port) {
                $overrides[$key] = $resolved;
            }
        }

        return new self(
            database: $this->database,
            meilisearch: $this->meilisearch,
            mailpit: $this->mailpit,
            redis: $this->redis,
            worker: $this->worker,
            scheduler: $this->scheduler,
            appPort: $appPort,
            vitePort: $vitePort,
            forwardPortOverrides: array_merge($this->forwardPortOverrides, $overrides),
        );
    }

    public function containers(): array
    {
        return array_values(array_filter([
            $this->database === 'sqlite' ? null : $this->database,
            $this->meilisearch ? 'meilisearch' : null,
            $this->mailpit ? 'mailpit' : null,
            $this->redis ? 'redis' : null,
            $this->worker ? 'worker' : null,
            $this->scheduler ? 'scheduler' : null,
        ]));
    }

    public function dependencies(): array
    {
        return array_values(array_diff($this->containers(), self::COMPANIONS));
    }

    public function volumes(): array
    {
        return array_map(
            fn (string $container): string => 'laravix-'.$container,
            array_values(array_diff($this->containers(), self::WITHOUT_VOLUME))
        );
    }

    public function healthchecks(): array
    {
        return array_values(array_diff($this->containers(), self::WITHOUT_HEALTHCHECK));
    }

    public function usesDatabaseContainer(): bool
    {
        return $this->database !== 'sqlite';
    }

    public function environmentValues(): array
    {
        $values = [
            'APP_PORT' => $this->appPort,
            'APP_URL' => 'http://localhost'.($this->appPort === 80 ? '' : ':'.$this->appPort),
            'VITE_PORT' => $this->vitePort,
        ];

        $values += match ($this->database) {
            'mysql' => [
                'DB_CONNECTION' => 'mysql',
                'DB_HOST' => 'mysql',
                'DB_PORT' => 3306,
                'DB_DATABASE' => 'laravix',
                'DB_USERNAME' => 'sail',
                'DB_PASSWORD' => 'password',
            ],
            'pgsql' => [
                'DB_CONNECTION' => 'pgsql',
                'DB_HOST' => 'pgsql',
                'DB_PORT' => 5432,
                'DB_DATABASE' => 'laravix',
                'DB_USERNAME' => 'sail',
                'DB_PASSWORD' => 'password',
            ],
            'sqlite' => [
                'DB_CONNECTION' => 'sqlite',
            ],
        };

        if ($this->meilisearch) {
            $values += [
                'SCOUT_DRIVER' => 'meilisearch',
                'MEILISEARCH_HOST' => 'http://meilisearch:7700',
            ];
        }

        if ($this->mailpit) {
            $values += [
                'MAIL_MAILER' => 'smtp',
                'MAIL_HOST' => 'mailpit',
                'MAIL_PORT' => 1025,
            ];
        }

        if ($this->redis) {
            $values += [
                'REDIS_HOST' => 'redis',
                'REDIS_PORT' => 6379,
                'CACHE_STORE' => 'redis',
                'SESSION_DRIVER' => 'redis',
            ];
        }

        return $values + $this->forwardPorts();
    }
}
