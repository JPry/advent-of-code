<?php
/**
 * Now, the jumps are even stranger: after each jump, if the offset was three or more, instead decrease it by 1.
 * Otherwise, increase it by 1 as before.
 *
 * Using this rule with the above example, the process now takes 10 steps, and the offset values after finding the exit
 * are left as 2 3 2 3 -1.
 *
 * How many steps does it now take to reach the exit?
 */

namespace JPry\AdventOfCode\y2017\day05;

function countJumps(array $jumps): int
{
    $jumpCount    = 0;
    $currentIndex = 0;

    do {
        // Store next jump.
        $nextIndex = $jumps[$currentIndex];

        // First increment/decrement the current index.
        if ($nextIndex >= 3) {
            $jumps[$currentIndex]--;
        } else {
            $jumps[$currentIndex]++;
        }

        // Jump to the next index.
        $currentIndex += $nextIndex;

        $jumpCount++;
    } while (array_key_exists($currentIndex, $jumps));

    return $jumpCount;
}

$input = array_map('intval', file(__DIR__ . '/input.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
$test1 = [0, 3, 0, 1, -3];

echo 'Test jumps: ' . countJumps($test1) . PHP_EOL;
echo 'Input jumps: ' . countJumps($input) . PHP_EOL;
