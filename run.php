#!/usr/bin/env php
<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use PathFinder\PathFinder;
use PathFinder\GridSearch;
use PathFinder\GridRules;

function showUsage(): void
{
    $message = <<<TXT


TXT;

    fwrite(STDERR, $message);
}

function decodeGrid(string $gridJson): array
{
    $grid = json_decode($gridJson, true);

    if (!is_array($grid)) {
        throw new InvalidArgumentException('Grid must be valid JSON representing a 2D array.');
    }

    return $grid;
}

function parsePoint(string $label, string $input): array
{
    $parts = explode(',', $input);

    if (count($parts) !== 2) {
        throw new InvalidArgumentException(
            sprintf('%s position must be in the format "row,col" (e.g. "0,0"). Given: "%s"', ucfirst($label), $input)
        );
    }

    return [
        (int) $parts[0],
        (int) $parts[1],
    ];
}

if ($argc !== 4) {
    showUsage();
    exit(1);
}

[$scriptName, $gridJson, $startInput, $endInput] = $argv;

try {
    $grid  = decodeGrid($gridJson);
    $start = parsePoint('start', $startInput);
    $end   = parsePoint('end', $endInput);

    $pathFinder = new PathFinder(new GridSearch(new GridRules()), new GridRules());
    $distance = $pathFinder->pathFind($grid, $start, $end);

    echo "Shortest distance: {$distance}" . PHP_EOL;
    exit(0);
} catch (Throwable $e) {
    fwrite(STDERR, 'ERROR: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}

