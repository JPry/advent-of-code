<?php
/**
 * How many steps away is the furthest he ever got from his starting position?
 */

namespace JPry\AdventOfCode\y2017\day11;

/*
 * My first thought at reading the second challenge is disappointment. I don't think
 * that my initial code is set up to handle farthest distance, so I'll have to write
 * entirely new code.
 *
 * My thought for how to accomplish this portion is to write a loop to actually walk
 * through each of the steps. Within the loop, it will compare the previous step to
 * the current step, and determine if the step takes us closer, further, or the same
 * distance from the start point. There can be a counter to determine the furthest
 * number of steps in the trip.
 *
 * The challenge will be in determining a change in ordinal direction. For example,
 * 'ne,ne,ne,ne' is further than 'sw,sw,sw', but we need to make sure that we have
 * a relation to the start point to accurately compare. Maybe the code from part 1
 * can help there?
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
