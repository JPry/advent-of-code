<?php
/**
 * The logic you've constructed forms a single round of the Knot Hash algorithm; running the full thing requires many
 * of these rounds. Some input and output processing is also required.
 *
 * First, from now on, your input should be taken not as a list of numbers, but as a string of bytes instead. Unless
 * otherwise specified, convert characters to bytes using their ASCII codes. This will allow you to handle arbitrary
 * ASCII strings, and it also ensures that your input lengths are never larger than 255. For example, if you are given
 * 1,2,3, you should convert it to the ASCII codes for each character: 49,44,50,44,51.
 *
 * Once you have determined the sequence of lengths to use, add the following lengths to the end of the sequence: 17,
 * 31, 73, 47, 23. For example, if you are given 1,2,3, your final sequence of lengths should be
 * 49,44,50,44,51,17,31,73,47,23 (the ASCII codes from the input string combined with the standard length suffix
 * values).
 *
 * Second, instead of merely running one round like you did above, run a total of 64 rounds, using the same length
 * sequence in each round. The current position and skip size should be preserved between rounds. For example, if the
 * previous example was your first round, you would start your second round with the same length sequence (3, 4, 1, 5,
 * 17, 31, 73, 47, 23, now assuming they came from ASCII codes and include the suffix), but start with the previous
 * round's current position (4) and skip size (4).
 *
 * Once the rounds are complete, you will be left with the numbers from 0 to 255 in some order, called the sparse hash.
 * Your next task is to reduce these to a list of only 16 numbers called the dense hash. To do this, use numeric
 * bitwise XOR to combine each consecutive block of 16 numbers in the sparse hash (there are 16 such blocks in a list
 * of 256 numbers). So, the first element in the dense hash is the first sixteen elements of the sparse hash XOR'd
 * together, the second element in the dense hash is the second sixteen elements of the sparse hash XOR'd together,
 * etc.
 *
 * For example, if the first sixteen elements of your sparse hash are as shown below, and the XOR operator is ^, you
 * would calculate the first output number like this:
 *
 * 65 ^ 27 ^ 9 ^ 1 ^ 4 ^ 3 ^ 40 ^ 50 ^ 91 ^ 7 ^ 6 ^ 0 ^ 2 ^ 5 ^ 68 ^ 22 = 64
 *
 * Perform this operation on each of the sixteen blocks of sixteen numbers in your sparse hash to determine the sixteen
 * numbers in your dense hash.
 *
 * Finally, the standard way to represent a Knot Hash is as a single hexadecimal string; the final output is the dense
 * hash in hexadecimal notation. Because each number in your dense hash will be between 0 and 255 (inclusive), always
 * represent each number as two hexadecimal digits (including a leading zero as necessary). So, if your first three
 * numbers are 64, 7, 255, they correspond to the hexadecimal numbers 40, 07, ff, and so the first six characters of
 * the hash would be 4007ff. Because every Knot Hash is sixteen such numbers, the hexadecimal representation is always
 * 32 hexadecimal digits (0-f) long.
 *
 * Here are some example hashes:
 *
 * - The empty string becomes a2582a3a0e66e6e86e3812dcb672a272.
 * - AoC 2017 becomes 33efeb34ea91902bb2f59c9920caa6cd.
 * - 1,2,3 becomes 3efbe78a8d82f29979031a4aa0b16a9d.
 * - 1,2,4 becomes 63960835bcdc130f0b66d7ff4f6a5a8e.
 *
 * Treating your puzzle input as a string of ASCII characters, what is the Knot Hash of your puzzle input? Ignore any
 * leading or trailing whitespace you might encounter.
 */

namespace JPry\AdventOfCode\y2017\day10;

function tieTheKnot(array $lengths, int $rounds = 64): array
{
    $list            = range(0, 255);
    $listLength      = count($list);
    $skipSize        = 0;
    $currentPosition = 0;

    for ($i = 0; $i < $rounds; $i++) {
        foreach ($lengths as $length) {
            // Get elements needing reversed.
            $piece = array_slice($list, $currentPosition, $length, true);
            if (count($piece) < $length) {
                $more = array_slice($list, 0, $length - count($piece), true);
                foreach ($more as $key => $value) {
                    $piece[$key] = $value;
                }
            }

            // Combine reversed elements with their original indexes.
            $indexes = array_keys($piece);
            $new     = array_combine($indexes, array_reverse($piece));
            foreach ($new as $key => $value) {
                $list[$key] = $value;
            }

            // Move the current position.
            $currentPosition += $length + $skipSize;
            while ($currentPosition >= $listLength) {
                $currentPosition -= $listLength;
            }

            $skipSize++;
        }
    }

    return $list;
}

function tightenTheKnot(string $string)
{
    // Convert to ASCII array.
    $ascii = array_filter(getAscii($string));

    // Sparse hash.
    $list = tieTheKnot($ascii);

    $segments = intval(floor(count($list) / 16));
    $return   = '';
    for ($i = 0; $i < $segments; $i++) {
        $section = array_slice($list, $i * 16, 16);
        $xor     = array_shift($section);

        while (!empty($section)) {
            $xor ^= array_shift($section);
        }

        $return .= str_pad(dechex($xor), 2, '0', STR_PAD_LEFT);
    }

    return $return;
}

function getAscii(string $string): array
{
    return array_merge(array_map('ord', str_split($string)), [17, 31, 73, 47, 23]);
}

$lengths = trim(file_get_contents(__DIR__ . '/input.txt'));
$test1   = '';
$test2   = 'AoC 2017';
$test3   = '1,2,3';
$test4   = '1,2,4';

foreach (range(1, 4) as $i) {
    echo "test{$i} hash: " . tightenTheKnot(${"test{$i}"}) . PHP_EOL;
}
echo 'input hash: ' . tightenTheKnot($lengths) . PHP_EOL;
