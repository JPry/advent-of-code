<?php
/**
 * "Great work; looks like we're on the right track after all. Here's a star for your effort." However, the program
 * seems a little worried. Can programs be worried?
 *
 * "Based on what we're seeing, it looks like all the User wanted is some information about the evenly divisible values
 * in the spreadsheet. Unfortunately, none of us are equipped for that kind of calculation - most of us specialize in
 * bitwise operations."
 *
 * It sounds like the goal is to find the only two numbers in each row where one evenly divides the other - that is,
 * where the result of the division operation is a whole number. They would like you to find those numbers on each
 * line, divide them, and add up each line's result.
 *
 * For example, given the following spreadsheet:
 *
 * 5 9 2 8
 * 9 4 7 3
 * 3 8 6 5
 *
 * - In the first row, the only two numbers that evenly divide are 8 and 2; the result of this division is 4.
 * - In the second row, the two numbers are 9 and 3; the result is 3.
 * - In the third row, the result is 2.
 *
 * In this example, the sum of the results would be 4 + 3 + 2 = 9.
 *
 * What is the sum of each row's result in your puzzle input?
 */

namespace JPry\AdventOfCode\y2017\day02;

/**
 * Find the 2 numbers in each row that are evenly divisible, and return their sum.
 *
 * @author Jeremy Pry
 *
 * @param string $data
 *
 * @return int
 */
function product_checksum($data): int
{
    $to_sum = [];

    // Split into array of rows.
    $rows = explode("\n", $data);

    foreach ($rows as $row) {
        // Split the row into an array.
        $row_array = explode("\t", $row);

        // Sort the numbers low to high.
        sort($row_array);
        $found = false;

        do {
            // Remove the highest number from the array.
            $max = array_pop($row_array);

            // Work through each of the remaining numbers, determining if it is evenly divisible into highest number
            foreach ($row_array as $item) {
                if (0 === ($max % $item)) {
                    $to_sum[] = $max / $item;
                    $found    = true;
                    break;
                }
            }
        } while (false === $found && !empty($row_array));
    }

    return array_sum($to_sum);
}

$input = file_get_contents(__DIR__ . '/data.txt');
$test2 = file_get_contents(__DIR__ . '/test2.txt');

echo 'PRODUCT' . PHP_EOL;
echo 'test data: ' . product_checksum($test2) . PHP_EOL;
echo 'real data: ' . product_checksum($input) . PHP_EOL;
