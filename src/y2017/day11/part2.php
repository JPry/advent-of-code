<?php
/**
 * How many steps away is the furthest he ever got from his starting position?
 */

namespace JPry\AdventOfCode\y2017\day11;

/*
 * My first thought at reading the second challenge is disappointment. I don't think
 * that my initial code is set up to handle farthest distance, so I'll have to write
 * entirely new code.
 */

$test1 = 'ne,ne,ne';
$test2 = 'ne,ne,sw,sw';
$test3 = 'ne,ne,s,s';
$test4 = 'se,sw,se,sw,sw';

$input   = explode(',', trim(file_get_contents(__DIR__ . '/input.txt')));

//foreach (range(1, 4) as $i) {
//    $testReduced = reduceSides(reduceOpposites(array_count_values(explode(',', ${"test{$i}"}))));
//    echo "steps test{$i}: " . array_sum($testReduced) . PHP_EOL;
//}
//echo "input steps: " . array_sum($reduced) . PHP_EOL;
