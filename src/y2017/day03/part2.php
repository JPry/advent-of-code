<?php
/**
 * As a stress test on the system, the programs here clear the grid and then store the value 1 in square 1. Then, in
 * the same allocation order as shown above, they store the sum of the values in all adjacent squares, including
 * diagonals.
 *
 * So, the first few squares' values are chosen as follows:
 *
 * - Square 1 starts with the value 1.
 * - Square 2 has only one adjacent filled square (with value 1), so it also stores 1.
 * - Square 3 has both of the above squares as neighbors and stores the sum of their values, 2.
 * - Square 4 has all three of the aforementioned squares as neighbors and stores the sum of their values, 4.
 * - Square 5 only has the first and fourth squares as neighbors, so it gets the value 5.
 *
 * Once a square is written, its value does not change. Therefore, the first few squares would receive the following
 * values:
 *
 * 147  142  133  122   59
 * 304    5    4    2   57
 * 330   10    1    1   54
 * 351   11   23   25   26
 * 362  747  806--->   ...
 *
 * What is the first value written that is larger than your puzzle input?
 *
 * Your puzzle input is 265149.
 */

namespace JPry\AdventOfCode\y2017\day03;

define('INPUT', 265149);

/*
 * Square positions:
 *
 * 37  36  35  34  33  32  31
 * 38  17  16  15  14  13  30
 * 39  18   5   4   3  12  29
 * 40  19   6   1   2  11  28
 * 41  20   7   8   9  10  27
 * 42  21  22  23  24  25  26
 * 43  44  45  46  47  48  49
 */

function compute($value): int
{
    $row       = 0;
    $column    = 0;
    $lastValue = 1;
    $direction = 'right';
    $ring      = 1;
    $a         = [
        [1],
    ];

    while ($lastValue < $value) {

        // Move the row and column to the new location.
        switch ($direction) {
            case 'right':
                $column++;

                // Change to up when we're at the appropriate row length.
                if ($column === $ring) {
                    $direction = 'up';
                    $ring      += 2;
                }

                break;

            case 'up':
                $row--;

                // Maybe add a new row.
                if ($row < 0) {
                    $row       = 0;
                    $column    = $ring - 1;
                    $direction = 'left';

                    // Fill in new column at the beginning of each existing row.
                    foreach ($a as &$item) {
                        array_unshift($item, 0);
                    }

                    array_unshift($a, array_fill(0, $ring, 0));
                }

                break;

            case 'left':
                $column--;

                if ($column < 0) {
                    $column    = 0;
                    $direction = 'down';
                }

                break;

            case 'down':
                $row++;

                // Maybe add a new row.
                if (($row + 1) > count($a)) {
                    $a[]       = array_fill(0, $ring, 0);
                    $direction = 'right';
                }

                break;
        }

        // Check surrounding positions.
        $lastValue = 0;
        $lastValue += isset($a[$row + 0][$column + 1]) ? $a[$row + 0][$column + 1] : 0;
        $lastValue += isset($a[$row + 0][$column - 1]) ? $a[$row + 0][$column - 1] : 0;
        $lastValue += isset($a[$row + 1][$column + 0]) ? $a[$row + 1][$column + 0] : 0;
        $lastValue += isset($a[$row - 1][$column + 0]) ? $a[$row - 1][$column + 0] : 0;
        $lastValue += isset($a[$row + 1][$column + 1]) ? $a[$row + 1][$column + 1] : 0;
        $lastValue += isset($a[$row + 1][$column - 1]) ? $a[$row + 1][$column - 1] : 0;
        $lastValue += isset($a[$row - 1][$column + 1]) ? $a[$row - 1][$column + 1] : 0;
        $lastValue += isset($a[$row - 1][$column - 1]) ? $a[$row - 1][$column - 1] : 0;

        // Store the new value in the current location.
        $a[$row][$column] = $lastValue;
    }

    return $lastValue;
}

//echo 'last value 363: ' . compute(363) . PHP_EOL;
//echo 'last value 750: ' . compute(750) . PHP_EOL;
echo 'input value: ' . compute(INPUT) . PHP_EOL;
