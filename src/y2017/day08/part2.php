<?php
/**
 * To be safe, the CPU also needs to know the highest value held in any register during this process so that it can
 * decide how much memory to allocate to these operations. For example, in the above instructions, the highest value
 * ever held was 10 (in register c after the third instruction was evaluated).
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
    $max   = 0;
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

        if (compare($store[$match[4]], intval($match[6]), $match[5])) {
            $store[$match[1]] = ${$match[2]}($store[$match[1]], intval($match[3]));
            $max              = $store[$match[1]] > $max ? $store[$match[1]] : $max;
        }
    }

    echo "process max was: {$max}\n";

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
