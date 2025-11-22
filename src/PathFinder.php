<?php

namespace PathFinder;

use PathFinder\GridSearch;
use PathFinder\GridRules;

class PathFinder
{
    public function __construct(private GridSearch $gridSearch, private GridRules $gridRules)
    {}

    public function pathFind(array $grid, array $startPosition, array $goalPosition): int
    {
        if (!is_array($grid[0])) {        
            throw new \InvalidArgumentException('First argument must be an array.');
        }

        $rows = count($grid);
        $columns = count($grid[0]);

        for($row = 0; $row < $rows; $row++) {
            if(!is_array($grid[$row])) {
                throw new \InvalidArgumentException('First argument must be a two-dimensional array.');
            }
            if(count($grid[$row]) !== $columns) {
                throw new \InvalidArgumentException('All rows in the first argument must have the same number of columns.');
            }
            for ($column = 0; $column < $columns; $column++) {
                if(!is_bool($grid[$row][$column])) {
                    throw new \InvalidArgumentException('All elements in the first argument must be boolean values.');
                }
            }
        }

        [$startRow, $startColumn] = $startPosition;
        [$goalRow, $goalColumn] = $goalPosition;

        if(!$this->gridRules->isInBounds($startRow, $startColumn, $rows, $columns)) {
            throw new \InvalidArgumentException('Start position is out of bounds.');
        }

        if(!$this->gridRules->isInBounds($goalRow, $goalColumn, $rows, $columns)) {
            throw new \InvalidArgumentException('Goal position is out of bounds.');
        }

        if(!$this->gridRules->isStartDestinationValid($grid[$startRow][$startColumn])) {
            throw new \InvalidArgumentException('Start position must not be false');
        }

        if(!$this->gridRules->isGoalDestinationValid($grid[$goalRow][$goalColumn])) {
            throw new \InvalidArgumentException('Goal position must not be false');
        }

        if(!$this->gridRules->isStartEndPositionsDifferent($startPosition, $goalPosition)) {
            throw new \InvalidArgumentException('Start and goal positions must be different.');
        }

        return $this->gridSearch->queue($grid, $startRow, $startColumn, $goalRow, $goalColumn, $rows, $columns);
    }
}
