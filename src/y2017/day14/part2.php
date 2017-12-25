<?php
/**
 * Now, all the defragmenter needs to know is the number of regions. A region is a group of used squares that are all
 * adjacent, not including diagonals. Every used square is in exactly one region: lone used squares form their own
 * isolated regions, while several adjacent squares all count as a single region.
 *
 * In the example above, the following nine regions are visible, each marked with a distinct digit:
 *
 * 11.2.3..-->
 * .1.2.3.4
 * ....5.6.
 * 7.8.55.9
 * .88.5...
 * 88..5..8
 * .8...8..
 * 88.8.88.-->
 * |      |
 * V      V
 *
 * Of particular interest is the region marked 8; while it does not appear contiguous in this small view, all of the
 * squares marked 8 are connected when considering the whole 128x128 grid. In total, in this example, 1242 regions are
 * present.
 *
 * How many regions are present given your key string?
 */

namespace JPry\AdventOfCode\y2017\day14;

use JPry\AdventOfCode\y2017\day10;

require_once __DIR__ . '/part1.php';

/*
 * I think that counting the regions will be a bit tricky. I would want to build out the regions as each
 * line is generated. However, what initially appear to be two (or more) separate regions could end up
 * being joined with subsequent rows. That leads me to think that I ought to build out the entire array
 * and then attempt to determine the regions.
 *
 * To count the regions I think the best way is to remove each region as it is counted. This would let me
 * ensure I don't accidentally double-count anything.
 */

function buildArray(string $input): array
{
    $a = [];
    for ($i = 0; $i < 128; $i++) {
        // Create the binary version
        $hash = day10\tightenTheKnot("{$input}-{$i}");
        $bin = convertHash($hash);
        $a[] = str_split($bin);
    }

    return $a;
}

function countRegions(array $input): int
{
    $regions = 0;

    /*
     * Loop through all rows and columns.
     *
     * This could be done with a while loop, but this seemed easier.
     */
    foreach ($input as $rindex => &$row){
        foreach ($row as $cindex => &$column) {
            $regions += (int) removeRegion($input, $rindex, $cindex);
        }
    }

    return $regions;
}

function removeRegion(array &$a, $x, $y)
{
    if ($x > 127 || $y > 127) {
        return false;
    } elseif ($x < 0 || $y < 0) {
        return false;
    }

    if ('1' !== $a[$x][$y]) {
        return false;
    }

    $a[$x][$y] = 0;
    removeRegion($a, $x, $y - 1);
    removeRegion($a, $x, $y + 1);
    removeRegion($a, $x - 1, $y);
    removeRegion($a, $x + 1, $y);

    return true;
}

if (__FILE__ === get_included_files()[0]) {
    echo 'test regions: ', countRegions(buildArray('flqrgnkx')), PHP_EOL;
    echo 'counted regions: ', countRegions(buildArray(INPUT)), PHP_EOL;
}

