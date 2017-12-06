<?php
/**
 * Out of curiosity, the debugger would also like to know the size of the loop: starting from a state that has already
 * been seen, how many block redistribution cycles must be performed before that same state is seen again?
 *
 * In the example above, 2 4 1 2 is seen again after four cycles, and so the answer in that example would be 4.
 *
 * How many cycles are in the infinite loop that arises from the configuration in your puzzle input?
 */

namespace JPry\AdventOfCode\y2017\day06;

/**
 * Redistribute the value of a given index in an array.
 *
 * @author Jeremy Pry
 *
 * @param array $a     The array (passed by reference).
 * @param int   $index The index to redistribute.
 */
function redistributeIndex(array &$a, $index)
{
    $value     = $a[$index];
    $a[$index] = 0;
    $maxIndex  = count($a) - 1;
    $index++;

    while ($value) {
        if ($index > $maxIndex) {
            $index = 0;
        }

        $a[$index]++;
        $value--;
        $index++;
    }
}

function redistribute(array &$a): array
{
    $patterns = [];
    $steps    = 0;

    while (true) {
        $maxIndex = array_search(max($a), $a);
        redistributeIndex($a, $maxIndex);
        $steps++;

        // Identify any patterns we've seen before to break out.
        $hash = sha1(join(',', $a));
        if (isset($patterns[$hash])) {
            break;
        }

        $patterns[$hash] = $steps;
    }

    return [
        'steps'    => $steps,
        'previous' => $patterns[$hash] ?? 0,
    ];
}

$test1 = [0, 2, 7, 0];
$input = explode("\t", trim(file_get_contents(__DIR__ . '/input.txt')));

$redistributeTest = redistribute($test1);
$redistributeInput = redistribute($input);

echo 'test steps: ' . $redistributeTest['steps'] . PHP_EOL;
echo 'test loop length: ' . ($redistributeTest['steps'] - $redistributeTest['previous']) . PHP_EOL;
echo 'input steps: ' . $redistributeInput['steps'] . PHP_EOL;
echo 'input loop length: ' . ($redistributeInput['steps'] - $redistributeInput['previous']) . PHP_EOL;
