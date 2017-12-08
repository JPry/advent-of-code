<?php
/**
 * You receive a signal directly from the CPU. Because of your recent assistance with jump instructions, it would like
 * you to compute the result of a series of unusual register instructions.
 *
 * Each instruction consists of several parts: the register to modify, whether to increase or decrease that register's
 * value, the amount by which to increase or decrease it, and a condition. If the condition fails, skip the instruction
 * without modifying the register. The registers all start at 0. The instructions look like this:
 *
 * b inc 5 if a > 1
 * a inc 1 if b < 5
 * c dec -10 if a >= 1
 * c inc -20 if c == 10
 *
 * These instructions would be processed as follows:
 *
 * - Because a starts at 0, it is not greater than 1, and so b is not modified.
 * - a is increased by 1 (to 1) because b is less than 5 (it is 0).
 * - c is decreased by -10 (to 10) because a is now greater than or equal to 1 (it is 1).
 * - c is increased by -20 (to -10) because c is equal to 10.
 *
 * After this process, the largest value in any register is 1.
 *
 * You might also encounter <= (less than or equal to) or != (not equal to). However, the CPU doesn't have the
 * bandwidth to tell you what all the registers are named, and leaves that to you to determine.
 *
 * What is the largest value in any register after completing the instructions in your puzzle input?
 */

namespace JPry\AdventOfCode\y2017\day08;

function compare($a, $b, $operator): bool
{
    switch ($operator) {
        case '<':
            return $a < $b;
        case '>':
            return $a > $b;
        case '<=':
            return $a <= $b;
        case '>=':
            return $a >= $b;
        case '==':
            return $a == $b;
        case '!=':
            return $a != $b;
        default:
            echo "Missing operator {$operator}\n";

            return false;
    }
}

function processRegisters(string $raw): array
{
    $store = [];
    $regex = '#(\w+)\s+(inc|dec)\s+(-?\d+)\s+if\s+(\w+)\s+([<>=!]{1,2})\s+(-?\d+)#m';

    // Simple helper functions.
    $inc = function($a, $b) {
        return $a + $b;
    };
    $dec = function($a, $b) {
        return $a - $b;
    };

    // Parse the string for matches.
    preg_match_all($regex, $raw, $matches, PREG_SET_ORDER);

    // Process the instructions.
    foreach ($matches as $key => $match) {
        $store[$match[1]] = $store[$match[1]] ?? 0;
        $store[$match[4]] = $store[$match[4]] ?? 0;

        /*
         * I tried using version_compare() here, but then I realized that it doesn't handle
         * negative numbers properly. I guess that makes sense, since you wouldn't have
         * a negative version. 🤷🏼‍♂️
         */
        if (compare($store[$match[4]], intval($match[6]), $match[5])) {
            $store[$match[1]] = ${$match[2]}($store[$match[1]], intval($match[3]));
        }
    }

    return $store;
}

$test1 = <<< TEST
b inc 5 if a > 1
a inc 1 if b < 5
c dec -10 if a >= 1
c inc -20 if c == 10
TEST;

$input = trim(file_get_contents(__DIR__ . '/input.txt'));

$test1Result = processRegisters($test1);
$inputResult = processRegisters($input);

echo 'test max is: ' . max($test1Result) . PHP_EOL;
echo 'input max is: ' . max($inputResult) . PHP_EOL;
