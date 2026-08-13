<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Console\Concerns;

trait WritesEnvFile
{
    private function writeEnv(array $values): ?string
    {
        $path = base_path('.env');
        $backup = null;

        if (is_file($path)) {
            $backup = base_path('.env.backup');

            copy($path, $backup);
        } else {
            $example = base_path('.env.example');

            file_put_contents($path, is_file($example) ? file_get_contents($example) : '');
        }

        $contents = file_get_contents($path);

        foreach ($values as $key => $value) {
            $line = $key.'='.$this->encodeEnvValue((string) $value);
            $pattern = '/^'.preg_quote($key, '/').'=.*/m';

            $contents = preg_match($pattern, $contents)
                ? preg_replace_callback($pattern, fn (): string => $line, $contents, 1)
                : rtrim($contents, "\r\n").PHP_EOL.$line.PHP_EOL;
        }

        file_put_contents($path, $contents);

        return $backup === null ? null : basename($backup);
    }

    private function encodeEnvValue(string $value): string
    {
        return preg_match('/[\s"\'#]/', $value) === 1
            ? '"'.addcslashes($value, '"\\').'"'
            : $value;
    }
}
