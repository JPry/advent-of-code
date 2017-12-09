<?php
/**
 * Now, you're ready to remove the garbage.
 *
 * To prove you've removed it, you need to count all of the characters within the garbage. The leading and trailing <
 * and > don't count, nor do any canceled characters or the ! doing the canceling.
 *
 * - <>, 0 characters.
 * - <random characters>, 17 characters.
 * - <<<<>, 3 characters.
 * - <{!>}>, 2 characters.
 * - <!!>, 0 characters.
 * - <!!!>>, 0 characters.
 * - <{o"i!a,<{i<a>, 10 characters.
 *
 * How many non-canceled characters are within the garbage in your puzzle input?
 */

namespace JPry\AdventOfCode\y2017\day09;

function sumGarbage(string $stream)
{
    $isGarbage = false;
    $sum       = 0;
    $nest      = 0;
    $length    = strlen($stream);

    for ($i = 0; $i < $length; $i++) {
        // If we're in garbage, keep looking for the end of garbage
        if ($isGarbage) {
            // Handle ignored character.
            if ('!' === $stream[$i]) {
                $i++;
                continue;
            } elseif ('>' === $stream[$i]) {
                $isGarbage = false;
            } else {
                $sum++;
            }
        } else {
            if ('<' === $stream[$i]) {
                $isGarbage = true;
            } elseif ('{' === $stream[$i]) {
                $nest++;
            } elseif ('}' === $stream[$i]) {
                $nest--;
            }
        }
    }

    return $sum;
}

$test1 = '<random characters>';
$test2 = '<<<<>';
$test3 = '<!!!>>';
$test4 = '<{o"i!a,<{i<a>';
$test5 = '{<a>,<a>,<a>,<a>}';
$test6 = '{{<ab>},{<ab>},{<ab>},{<ab>}}';
$test7 = '{{<!!>},{<!!>},{<!!>},{<!!>}}';
$test8 = '{{<a!>},{<a!>},{<a!>},{<ab>}}';
$input = trim(file_get_contents(__DIR__ . '/input.txt'));

foreach (range(1, 8) as $num) {
    echo "test{$num} score: " . sumGarbage(${"test{$num}"}) . PHP_EOL;
}

echo 'input score: ' . sumGarbage($input) . PHP_EOL;

