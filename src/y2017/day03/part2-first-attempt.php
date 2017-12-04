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


function square($value): int
{
    return $value ** 2;
}

function computeSpaces($value): int
{
    // Se initial values of the array (special cases)
    $a = [
        1 => 1,
        2 => 1,
        3 => 2,
        4 => 4,
        5 => 5,
        6 => 10,
        7 => 11,
        8 => 23,
        9 => 25,
    ];

    // Fill in the values to get the first greater value
    $lastValue   = 25;
    $position    = 10;
    $currentBase = 5;
    while ($lastValue < $value) {
        // The next value always includes the previous value.
        $lastValue = $a[$position - 1];

        if (isSquare($position, $currentBase)) {

            // Squares (bottom right corner) includes previous square corner, and 1 after previous square.
            $lastSquare = $currentBase - 2;
            $lastValue  += $a[square($lastSquare)];
            $lastValue  += $a[square($lastSquare) + 1];
        } elseif (isBeforeSquare($position, $currentBase)) {

            $lastSquare = $currentBase - 2;
            $lastValue  += $a[square($lastSquare)];
            $lastValue  += $a[square($lastSquare) + 1];
            $lastValue  += $a[square($lastSquare) - 1];
        } elseif ($corner = isCorner($position, $currentBase)) {

            // All other corners include just the previous corner
            $lastCorner = getLastCorner($corner, $currentBase);
            $lastValue  += $a[$lastCorner];
        } elseif ($corner = isBeforeCorner($position, $currentBase)) {

            // Special case for right before a corner.
            $lastCorner = getLastCorner($corner, $currentBase);
            $lastValue  += $a[$lastCorner];
            $lastValue  += $a[$lastCorner - 1];
        } elseif (isAfterSquare($position, $currentBase - 2)) {
            $lastValue += $a[square($currentBase - 4) + 1];
        }

        // Store the new value.
        $a[$position] = $lastValue;

        // Increment the position.
        $position++;

        // Possibly increment the square.
        if ($position > square($currentBase)) {
            $currentBase += 2;
        }
    }

    return $lastValue;
}

/**
 * Determine if the current position is a corner, and what number.
 *
 * Bottom right corner is 1.
 * Bottom left corner is 2.
 * Top left corner is 3.
 * Top right corner is 4.
 *
 * @author Jeremy Pry
 *
 * @param int $position
 * @param int $currentBase
 *
 * @return int
 */
function isCorner($position, $currentBase): int
{
    $corner       = square($currentBase);
    $cornerNumber = 1;
    do {
        if ($position === $corner) {
            return $cornerNumber;
        }

        $corner = ($corner - ($currentBase - 1));
        $cornerNumber++;
    } while ($cornerNumber <= 4);

    return 0;
}

/**
 * Determine if the current position is a square of the given base.
 *
 * @author Jeremy Pry
 *
 * @param $position
 * @param $base
 *
 * @return bool
 */
function isSquare($position, $base): bool
{
    return $position === square($base);
}

/**
 * Determine if the current position is immediately after a square.
 *
 * @author Jeremy Pry
 *
 * @param $position
 * @param $base
 *
 * @return bool
 */
function isAfterSquare($position, $base): bool
{
    return $position === (square($base) + 1);
}

/**
 * Determine if the current position is immediately before a square.
 *
 * @author Jeremy Pry
 *
 * @param $position
 * @param $currentBase
 *
 * @return bool
 */
function isBeforeSquare($position, $currentBase): bool
{
    return $position === (square($currentBase) - 1);
}

/**
 * Determine whether the current position is immediately before a corner.
 *
 * @author Jeremy Pry
 *
 * @param $position
 * @param $currentBase
 *
 * @return int
 */
function isBeforeCorner($position, $currentBase): int
{
    return isCorner($position + 1, $currentBase);
}

/**
 * Get the position for the same corner of the previous level.
 *
 * @author Jeremy Pry
 *
 * @param int $corner      The corner number. 2 is bottom left, 3 is top left, 4 is top right.
 * @param int $currentBase The current level
 *
 * @return int
 */
function getLastCorner($corner, $currentBase): int
{
    $lastBase     = $currentBase - 2;
    $lastSquare   = square($lastBase);
    $sideSubtract = $lastBase - 1;

    return $lastSquare - ($sideSubtract * ($corner - 1));
}

echo 'Space compute 25: ' . computeSpaces(25) . PHP_EOL;
echo 'Space compute 351: ' . computeSpaces(351) . PHP_EOL;
