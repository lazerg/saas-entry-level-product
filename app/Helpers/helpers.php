<?php

use Spatie\TemporaryDirectory\TemporaryDirectory;

/**
 * @param string $extension
 * @return string
 * @throws \Spatie\TemporaryDirectory\Exceptions\PathAlreadyExists
 */
function temp_path(string $extension = 'txt'): string
{
    $filename = Str::random(32) . '.' . $extension;

    return (new TemporaryDirectory())
        ->create()
        ->path($filename);
}
