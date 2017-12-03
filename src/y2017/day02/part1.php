<?php
/**
 * As you walk through the door, a glowing humanoid shape yells in your direction. "You there! Your state appears to be
 * idle. Come help us repair the corruption in this spreadsheet - if we take another millisecond, we'll have to display
 * an hourglass cursor!"
 *
 * The spreadsheet consists of rows of apparently-random numbers. To make sure the recovery process is on the right
 * track, they need you to calculate the spreadsheet's checksum. For each row, determine the difference between the
 * largest value and the smallest value; the checksum is the sum of all of these differences.
 *
 * For example, given the following spreadsheet:
 *
 * 5 1 9 5
 * 7 5 3
 * 2 4 6 8
 *
 * - The first row's largest and smallest values are 9 and 1, and their difference is 8.
 * - The second row's largest and smallest values are 7 and 3, and their difference is 4.
 * - The third row's difference is 6.
 *
 * In this example, the spreadsheet's checksum would be 8 + 4 + 6 = 18.
 */

namespace JPry\AdventOfCode\y2017\day02;

/**
 * Find the difference between largest and smallest values in each row of a spreadsheet.
 *
 * @author Jeremy Pry
 *
 * @param string $data
 *
 * @return int
 */
function checksum($data): int
{
    $to_sum = [];

    // Split into array of rows.
    $rows = explode("\n", $data);

    foreach ($rows as $row) {
        // Split each row into an array.
        $row_array = explode("\t", $row);

        // Find the difference between the maximum value and minimum value of the row.
        $to_sum[] = max($row_array) - min($row_array);
    }

    return array_sum($to_sum);
}

$input = file_get_contents(__DIR__ . '/data.txt');
$test1 = file_get_contents(__DIR__ . '/test1.txt');

echo 'CHECKSUM' . PHP_EOL;
echo 'test data: ' . checksum($test1) . PHP_EOL;
echo 'real data: ' . checksum($input) . PHP_EOL;
