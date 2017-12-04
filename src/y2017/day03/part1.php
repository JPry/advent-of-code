<?php
/**
 * You come across an experimental new kind of memory stored on an infinite two-dimensional grid.
 *
 * Each square on the grid is allocated in a spiral pattern starting at a location marked 1 and then counting up while
 * spiraling outward. For example, the first few squares are allocated like this:
 *
 * 37  36  35  34  33  32  31
 * 38  17  16  15  14  13  30
 * 39  18   5   4   3  12  29
 * 40  19   6   1   2  11  28  ^
 * 41  20   7   8   9  10  27  |
 * 42  21  22  23  24  25  26  |
 * 43  44  45  46  47  48  49 ...
 *
 * While this is very space-efficient (no squares are skipped), requested data must be carried back to square 1 (the
 * location of the only access port for this memory system) by programs that can only move up, down, left, or right.
 * They always take the shortest path: the Manhattan Distance between the location of the data and square 1.
 *
 * For example:
 *
 * - Data from square 1 is carried 0 steps, since it's at the access port.
 * - Data from square 12 is carried 3 steps, such as: down, left, left.
 * - Data from square 23 is carried only 2 steps: up twice.
 * - Data from square 1024 must be carried 31 steps.
 *
 * How many steps are required to carry the data from the square identified in your puzzle input all the way to the
 * access port?
 *
 * Your puzzle input is 265149.
 */

namespace JPry\AdventOfCode\y2017\day03;

define('INPUT', 265149);

/*
 * My first thought is that I should try to actually model the spiral up to the number I need. However,
 * I think this is too much effort.
 *
 * I notice that the spiral follows a pattern on the down-right diagonal from center: 1 squared in the center,
 * then 3 squared down-right, then 5 squared, etc. I think my first step is to find out what odd-square I'm dealing
 * with.
 */


function findLowestSquare($value): int
{
    $lowest = 1;
    while ($lowest ** 2 < $value) {
        $lowest += 2;
    }

    return $lowest;
}

//echo '3: ' . findLowestSquare(3) . PHP_EOL;
//echo '12: ' . findLowestSquare(12) . PHP_EOL;
//echo '25: ' . findLowestSquare(25) . PHP_EOL;
//echo '32: ' . findLowestSquare(32) . PHP_EOL;
echo 'input lowest square: ' . findLowestSquare(INPUT) . PHP_EOL;

/*
 * The next step is to find where we are on the side relative to key points.
 */


function findSteps($value, $lowestSquare): int
{
    $squared      = $lowestSquare ** 2;
    $squareLength = $lowestSquare - 1;

    // Figure out the axis numbers for each side.
    $bottom = $squared - ($squareLength / 2);
    $left   = $bottom - $squareLength;
    $top    = $left - $squareLength;
    $right  = $top - $squareLength;

    // Find nearest axis number for $value.
    $axes   = compact('bottom', 'left', 'top', 'right');
    $lowest = $squareLength;

    foreach ($axes as $location => $number) {
        $difference = abs($value - $number);
        if ($difference < $lowest) {
            $lowest = $difference;
        }
    }

    return ($squareLength / 2) + $lowest;
}

//echo '3: ' . findSteps(3, findLowestSquare(3)) . PHP_EOL;
//echo '44: ' . findSteps(44, findLowestSquare(44)) . PHP_EOL;
//echo '25: ' . findSteps(25, findLowestSquare(25)) . PHP_EOL;
//echo '51: ' . findSteps(51, findLowestSquare(51)) . PHP_EOL;
//echo '65: ' . findSteps(65, findLowestSquare(65)) . PHP_EOL;
echo 'input number of steps: ' . findSteps(INPUT, findLowestSquare(INPUT)) . PHP_EOL;
