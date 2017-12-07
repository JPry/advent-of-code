<?php
/**
 * Wandering further through the circuits of the computer, you come upon a tower of programs that have gotten
 * themselves into a bit of trouble. A recursive algorithm has gotten out of hand, and now they're balanced
 * precariously in a large tower.
 *
 * One program at the bottom supports the entire tower. It's holding a large disc, and on the disc are balanced several
 * more sub-towers. At the bottom of these sub-towers, standing on the bottom disc, are other programs, each holding
 * their own disc, and so on. At the very tops of these sub-sub-sub-...-towers, many programs stand simply keeping the
 * disc below them balanced but with no disc of their own.
 *
 * You offer to help, but first you need to understand the structure of these towers. You ask each program to yell out
 * their name, their weight, and (if they're holding a disc) the names of the programs immediately above them balancing
 * on that disc. You write this information down (your puzzle input). Unfortunately, in their panic, they don't do this
 * in an orderly fashion; by the time you're done, you're not sure which program gave which information.
 *
 * For example, if your list is the following:
 *
 * pbga (66)
 * xhth (57)
 * ebii (61)
 * havc (66)
 * ktlj (57)
 * fwft (72) -> ktlj, cntj, xhth
 * qoyq (66)
 * padx (45) -> pbga, havc, qoyq
 * tknk (41) -> ugml, padx, fwft
 * jptl (61)
 * ugml (68) -> gyxo, ebii, jptl
 * gyxo (61)
 * cntj (57)
 * ...then you would be able to recreate the structure of the towers that looks like this:
 *
 *                 gyxo
 *               /
 *          ugml - ebii
 *        /      \
 *       |         jptl
 *       |
 *       |         pbga
 *      /        /
 * tknk --- padx - havc
 *      \        \
 *       |         qoyq
 *       |
 *       |         ktlj
 *        \      /
 *          fwft - cntj
 *               \
 *                 xhth
 *
 * In this example, tknk is at the bottom of the tower (the bottom program), and is holding up ugml, padx, and fwft.
 * Those programs are, in turn, holding up other programs; in this example, none of those programs are holding up any
 * other programs, and are all the tops of their own towers. (The actual tower balancing in front of you is much
 * larger.)
 *
 * Before you're ready to help them, you need to make sure your information is correct. What is the name of the bottom
 * program?
 */

namespace JPry\AdventOfCode\y2017\day07;

function parseData(string $data): array
{
    $regex = '#(?P<name>\w+)\s+\((?P<weight>\d+)\)(?:\s-\>\s(?P<children>.*))?$#m';
    preg_match_all($regex, $data, $matches, PREG_SET_ORDER);

    // Clean up data.
    $cleaned = [];
    foreach ($matches as $match) {
        $cleaned[$match['name']] = [
            'weight'   => intval($match['weight']),
            'children' => isset($match['children']) ? explode(', ', $match['children']) : [],
        ];
    }

    return $cleaned;
}

function buildRelationships(array $data): array
{
    $ordered = [];
    foreach ($data as $name => $value) {
        // If there are children, update any existing elements with their parent.
        foreach ($value['children'] as $child) {
            if (!isset($ordered[$child])) {
                $ordered[$child] = [];
            }

            $ordered[$child]['parent'] = $name;
        }

        $ordered[$name] = array_merge(
            $ordered[$name] ?? ['parent' => null],
            [
                'name'     => $name,
                'weight'   => $value['weight'],
                'children' => $value['children'],
            ]
        );
    }

    return $ordered;
}

function getTop(array &$data): string
{
    return array_search(null, array_column($data, 'parent', 'name'));
}

$test1 = <<< TEST
pbga (66)
xhth (57)
ebii (61)
havc (66)
ktlj (57)
fwft (72) -> ktlj, cntj, xhth
qoyq (66)
padx (45) -> pbga, havc, qoyq
tknk (41) -> ugml, padx, fwft
jptl (61)
ugml (68) -> gyxo, ebii, jptl
gyxo (61)
cntj (57)
TEST;
$input = trim(file_get_contents(__DIR__ . '/input.txt'));

$testRelationships = buildRelationships(parseData($test1));
$inputRelationships = buildRelationships(parseData($input));

echo 'Bottom of test: ' . getTop($testRelationships) . PHP_EOL;
echo 'Bottom of input: ' . getTop($inputRelationships) . PHP_EOL;

