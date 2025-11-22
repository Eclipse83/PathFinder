<?php

namespace Tests;

use PathFinder\GridSearch;
use PathFinder\GridRules;
use PathFinder\PathFinder;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\InvalidArgumentException;

class PathFinderTest extends TestCase
{
    protected int $rows = 4;
    protected int $columns = 4;
    protected GridSearch $mockGridSearch;
    protected PathFinder $pathFinder;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->mockGridSearch = $this->createMock(GridSearch::class);
        
        $this->pathFinder = new PathFinder($this->mockGridSearch, new GridRules());
    }

    protected function createWalkableGrid(): array
    {
        return [
            [true, true, true, true],
            [true, true, true, true],
            [true, true, true, true],
            [true, true, true, true],
        ];
    }

    public function testShortestPathIsFoundOnOpenGrid(): void
    {
        $grid = $this->createWalkableGrid();
        
        $distance = $this->mockGridSearch->queue(
            $grid,
            0, 0,
            2, 2,
            $this->rows,
            $this->columns
        );

        $this->assertEquals(4, $distance);
    }

    public function testFindsPathAroundObstacle(): void
    {
        $grid = $this->createWalkableGrid();
        
        $grid[1][2] = false; 
        $grid[2][1] = false; 

        $distance = $this->mockGridSearch->queue(
            $grid,
            0, 0,
            3, 3,
            $this->rows,
            $this->columns
        );

        $this->assertEquals(6, $distance);
    }


    public function testReturnsIfGoalIsUnreachable(): void
    {
        $grid = $this->createWalkableGrid();
        
        $goalRow = 1;
        $goalColumn = 1;
        $grid[0][1] = false;
        $grid[2][1] = false;
        $grid[1][0] = false;
        $grid[1][2] = false;

        $distance = $this->mockGridSearch->queue(
            $grid,
            0, 0,
            $goalRow, $goalColumn,
            $this->rows,
            $this->columns
        );

        $this->assertEquals('Goal is unreachable', $distance);
    }

    public function testReturnsMinusOneIfStartIsOutOfBounds(): void
    {
        $grid = $this->createWalkableGrid();
        
        $distance = $this->mockGridSearch->queue(
            $grid,
            -1, 0,
            2, 2,
            $this->rows,
            $this->columns
        );

        $this->assertEquals(-1, $distance);
        
        $this->assertTrue($grid[0][0]);
    }

    public function testReturnsIfGoalIsOutOfBounds(): void
    {
        $grid = $this->createWalkableGrid();
        
        $distance = $this->mockGridSearch->queue(
            $grid,
            0, 0,
            10, 10,
            $this->rows,
            $this->columns
        );

        $this->assertEquals('Goal is unreachable', $distance);
    }
    
    public function testReturnsErrorIfStartEqualsGoal(): void
    {
        $grid = $this->createWalkableGrid();

        $this->expectException(InvalidArgumentException::class);
        
        $this->pathFinder->pathFind(
            $grid, 
            [1, 1], 
            [1, 1]
        );

        $this->expectExceptionMessage('Start and goal positions must be different.');
    }
}