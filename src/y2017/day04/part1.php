<?php
/**
 * A new system policy has been put in place that requires all accounts to use a passphrase instead of simply a
 * password. A passphrase consists of a series of words (lowercase letters) separated by spaces.
 *
 * To ensure security, a valid passphrase must contain no duplicate words.
 *
 * For example:
 *
 * - aa bb cc dd ee is valid.
 * - aa bb cc dd aa is not valid - the word aa appears more than once.
 * - aa bb cc dd aaa is valid - aa and aaa count as different words.
 *
 * The system's full passphrase list is available as your puzzle input. How many passphrases are valid?
 */

namespace JPry\AdventOfCode\y2017\day04;


function countValid($phrases): int
{
    $valid       = 0;
    $phraseArray = array_filter(explode("\n", $phrases));
    foreach ($phraseArray as $phrase) {
        $pieces = explode(' ', $phrase);
        do {
            $check = array_shift($pieces);
            if (in_array($check, $pieces)) {
                continue 2;
            }
        } while (!empty($pieces));

        $valid++;
    }

    return $valid;
}

$test = <<< TEST
aa bb cc dd ee
aa bb cc dd aa
aa bb cc dd aaa
aa bb cc dd dd
TEST;

$input = file_get_contents(__DIR__ . '/input.txt');

echo 'test valid: ' . countValid($test) . PHP_EOL;
echo 'input valid: ' . countValid($input) . PHP_EOL;
