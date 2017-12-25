<?php
/**
 * Suddenly, a scheduled job activates the system's disk defragmenter. Were the situation different, you might sit and
 * watch it for a while, but today, you just don't have that kind of time. It's soaking up valuable system resources
 * that are needed elsewhere, and so the only option is to help it finish its task as soon as possible.
 *
 * The disk in question consists of a 128x128 grid; each square of the grid is either free or used. On this disk, the
 * state of the grid is tracked by the bits in a sequence of knot hashes.
 *
 * A total of 128 knot hashes are calculated, each corresponding to a single row in the grid; each hash contains 128
 * bits which correspond to individual grid squares. Each bit of a hash indicates whether that square is free (0) or
 * used (1).
 *
 * The hash inputs are a key string (your puzzle input), a dash, and a number from 0 to 127 corresponding to the row.
 * For example, if your key string were flqrgnkx, then the first row would be given by the bits of the knot hash of
 * flqrgnkx-0, the second row from the bits of the knot hash of flqrgnkx-1, and so on until the last row, flqrgnkx-127.
 *
 * The output of a knot hash is traditionally represented by 32 hexadecimal digits; each of these digits correspond to
 * 4 bits, for a total of 4 * 32 = 128 bits. To convert to bits, turn each hexadecimal digit to its equivalent binary
 * value, high-bit first: 0 becomes 0000, 1 becomes 0001, e becomes 1110, f becomes 1111, and so on; a hash that begins
 * with a0c2017... in hexadecimal would begin with 10100000110000100000000101110000... in binary.
 *
 * Continuing this process, the first 8 rows and columns for key flqrgnkx appear as follows, using # to denote used
 * squares, and . to denote free ones:
 *
 * ##.#.#..-->
 * .#.#.#.#
 * ....#.#.
 * #.#.##.#
 * .##.#...
 * ##..#..#
 * .#...#..
 * ##.#.##.-->
 * |      |
 * V      V
 * In this example, 8108 squares are used across the entire 128x128 grid.
 *
 * Given your actual key string, how many squares are used?
 */

namespace JPry\AdventOfCode\y2017\day14;

/*
 * This seems a bit tricky at first, but I like that I can reuse code from day 10!
 */

use JPry\AdventOfCode\y2017\day10;

require_once dirname(__DIR__) . '/day10/part2.php';

define('INPUT', 'hfdlxzhv');

function countUsed(string $input): int
{
    $count = 0;

    for ($i = 0; $i < 128; $i++) {
        $hash = day10\tightenTheKnot("{$input}-{$i}");

        // Strip zeros and count string length.
        $noZeros = str_replace('0', '', convertHash($hash));
        $count   += strlen($noZeros);
    }

    return $count;
}

/**
 * Convert each piece into binary and concatenate.
 *
 * @author Jeremy Pry
 *
 * @param string $hash
 *
 * @return string
 */
function convertHash(string $hash): string
{
    $bin    = '';
    $pieces = str_split($hash, 1);
    foreach ($pieces as $piece) {
        // Need to make sure to pad the string with zeros to get the proper length.
        $bin .= str_pad(base_convert($piece, 16, 2), 4, '0', STR_PAD_LEFT);
    }

    return $bin;
}

if (__FILE__ === get_included_files()[0]) {
    echo 'input count: ' . countUsed(INPUT) . PHP_EOL;
}
