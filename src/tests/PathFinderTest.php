<?php

namespace Tests;

use PathFinder\GridSearch;
use PathFinder\GridRules;
use PathFinder\PathFinder;
use PHPUnit\Framework\TestCase;

class PathFinderTest extends TestCase
{
    private PathFinder $pathFinder;

    protected function setUp(): void
    {
        parent::setUp();

        $rules = new GridRules();
        $gridSearch = new GridSearch($rules);

        $this->pathFinder = new PathFinder($gridSearch, $rules);
    }

    private function createWalkableGrid(): array
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

        $distance = $this->pathFinder->pathFind(
            $grid,
            [0, 0],
            [2, 2],
        );

        $this->assertSame(4, $distance);
    }

    public function testFindsPathAroundObstacle(): void
    {
        $grid = $this->createWalkableGrid();

        $grid[1][2] = false;
        $grid[2][1] = false;

        $distance = $this->pathFinder->pathFind(
            $grid,
            [0, 0],
            [3, 3],
        );

        $this->assertSame(6, $distance);
    }

    public function testReturnsMinusOneIfGoalIsUnreachable(): void
    {
        $grid = $this->createWalkableGrid();

        $goalRow = 1;
        $goalColumn = 1;

        $grid[0][1] = false;
        $grid[2][1] = false;
        $grid[1][0] = false;
        $grid[1][2] = false;

        $distance = $this->pathFinder->pathFind(
            $grid,
            [0, 0],
            [$goalRow, $goalColumn],
        );

        $this->assertSame(-1, $distance);
    }

    public function testThrowsIfStartIsOutOfBounds(): void
    {
        $grid = $this->createWalkableGrid();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Start position is out of bounds.');

        $this->pathFinder->pathFind(
            $grid,
            [-1, 0],
            [2, 2],
        );
    }

    public function testThrowsIfGoalIsOutOfBounds(): void
    {
        $grid = $this->createWalkableGrid();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Goal position is out of bounds.');

        $this->pathFinder->pathFind(
            $grid,
            [0, 0],
            [10, 10],
        );
    }

    public function testThrowsIfStartEqualsGoal(): void
    {
        $grid = $this->createWalkableGrid();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Start and goal positions must be different.');

        $this->pathFinder->pathFind(
            $grid,
            [1, 1],
            [1, 1],
        );
    }
}
