<?php

namespace PathFinder;

class GridRules
{
    public function isInBounds(int $row, int $column, int $rows, int $columns): bool
    {
        if($row < 0 || $row >= $rows || $column < 0 || $column >= $columns) {
        return false;
        }

        return true;
    }

    public function isStartDestinationValid(bool $startPosition): bool
    {
        if($startPosition === false) {
        return false;
        }

        return true;
    }

    public function isGoalDestinationValid(bool $goalPosition): bool
    {
        if($goalPosition === false) {
        return false;
        }

        return true;
    }

    public function isStartEndPositionsDifferent(array $startPosition, array $goalPosition): bool
    {
        if($startPosition === $goalPosition) {
        return false;
        }

        return true;
    }
}