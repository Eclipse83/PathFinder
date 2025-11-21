<?php

namespace PathFinder;

use SplQueue;

class GridSearch
{
    public function isInBounds(int $row, int $column, int $rows, int $columns): bool
    {
        if($row < 0 || $row >= $rows || $column < 0 || $column >= $columns) {
        return false;
        }
        return true;
    }

    public function queue(array $grid, int $startRow, int $startColumn, int $goalRow, int $goalColumn, int $rows, int $columns): int|string
    {
        $queue = new SplQueue();
        
        $queue->enqueue([$startRow, $startColumn, 0]);
        
        $grid[$startRow][$startColumn] = false;

        $directions = [[1, 0], [-1, 0], [0, 1], [0, -1]];

        while (!$queue->isEmpty()) {
            [$row, $column, $distance] = $queue->dequeue();

            if ($row === $goalRow && $column === $goalColumn) {
                return $distance;
            }

            foreach ($directions as [$directionRow, $directionColumn]) {
                $neighbourRow = $row + $directionRow;
                $neighbourColumn = $column + $directionColumn;

                if (!$this->isInBounds($neighbourRow, $neighbourColumn, $rows, $columns)) {
                    continue;
                }

                if ($grid[$neighbourRow][$neighbourColumn] === true) {

                    $grid[$neighbourRow][$neighbourColumn] = false; 

                    $queue->enqueue([$neighbourRow, $neighbourColumn, $distance + 1]);
                }
            }
        }

        return 'Goal is unreachable';
    }
}