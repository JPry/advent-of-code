<?php
/**
 * Crossing the bridge, you've barely reached the other side of the stream when a program comes up to you, clearly in
 * distress. "It's my child process," she says, "he's gotten lost in an infinite grid!"
 *
 * Fortunately for her, you have plenty of experience with infinite grids.
 *
 * Unfortunately for you, it's a hex grid.
 *
 * The hexagons ("hexes") in this grid are aligned such that adjacent hexes can be found to the north, northeast,
 * southeast, south, southwest, and northwest:
 *
 *   \ n  /
 * nw +--+ ne
 *   /    \
 * -+      +-
 *   \    /
 * sw +--+ se
 *   / s  \
 *
 * You have the path the child process took. Starting where he started, you need to determine the fewest number of
 * steps required to reach him. (A "step" means to move from the hex you are in to any adjacent hex.)
 *
 * For example:
 *
 * - ne,ne,ne is 3 steps away.
 * - ne,ne,sw,sw is 0 steps away (back where you started).
 * - ne,ne,s,s is 2 steps away (se,se).
 * - se,sw,se,sw,sw is 3 steps away (s,s,sw).
 */

namespace JPry\AdventOfCode\y2017\day11;

function reduceOpposites(array $directions)
{
    $directions = array_merge(
        [
            'n'  => 0,
            'ne' => 0,
            'se' => 0,
            's'  => 0,
            'sw' => 0,
            'nw' => 0,
        ],
        $directions
    );
    $opposites  = [
        'n'  => 's',
        'ne' => 'sw',
        'nw' => 'se',
    ];

    foreach ($opposites as $one => $other) {
        $min                = min($directions[$one], $directions[$other]);
        $directions[$one]   -= $min;
        $directions[$other] -= $min;
    }

    return $directions;
}

function reduceSides(array $directions)
{
    $order  = ['n', 'ne', 'se', 's', 'sw', 'nw'];
    $length = count($order);
    $next   = function($index, $jump) use ($length) {
        $index += $jump;

        return $index >= $length ? $index - $length : $index;
    };

    $compare = function($side, $compare, $index) use (&$directions, $next, $order) {
        $result = $directions[$side] <=> $directions[$compare];
        if ($result <= 0) {
            $directions[$order[$next($index, 1)]] += $directions[$side];
            $directions[$compare]                 -= $directions[$side];
            $directions[$side]                    = 0;
        } else {
            $directions[$order[$next($index, 1)]] += $directions[$compare];
            $directions[$side]                    -= $directions[$compare];
            $directions[$compare]                 = 0;
        }
    };

    foreach ($order as $index => &$side) {
        if (0 === $directions[$side]) {
            continue;
        }

        $compare1 = $next($index, 2);
        $compare2 = $next($compare1, 2);

        $compare($side, $order[$compare1], $index);
        $compare($side, $order[$compare2], $index);
    }

    return $directions;
}

$test1 = 'ne,ne,ne';
$test2 = 'ne,ne,sw,sw';
$test3 = 'ne,ne,s,s';
$test4 = 'se,sw,se,sw,sw';

$input   = explode(',', trim(file_get_contents(__DIR__ . '/input.txt')));
$counted = array_count_values($input);
$reduced = reduceSides(reduceOpposites($counted));

foreach (range(1, 4) as $i) {
    $testReduced = reduceSides(reduceOpposites(array_count_values(explode(',', ${"test{$i}"}))));
    echo "steps test{$i}: " . array_sum($testReduced) . PHP_EOL;
}
echo "input steps: " . array_sum($reduced) . PHP_EOL;
