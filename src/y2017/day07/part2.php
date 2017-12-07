<?php
/**
 * The programs explain the situation: they can't get down. Rather, they could get down, if they weren't expending all
 * of their energy trying to keep the tower balanced. Apparently, one program has the wrong weight, and until it's
 * fixed, they're stuck here.
 *
 * For any program holding a disc, each program standing on that disc forms a sub-tower. Each of those sub-towers are
 * supposed to be the same weight, or the disc itself isn't balanced. The weight of a tower is the sum of the weights
 * of the programs in that tower.
 *
 * In the example above, this means that for ugml's disc to be balanced, gyxo, ebii, and jptl must all have the same
 * weight, and they do: 61.
 *
 * However, for tknk to be balanced, each of the programs standing on its disc and all programs above it must each
 * match. This means that the following sums must all be the same:
 *
 * - ugml + (gyxo + ebii + jptl) = 68 + (61 + 61 + 61) = 251
 * - padx + (pbga + havc + qoyq) = 45 + (66 + 66 + 66) = 243
 * - fwft + (ktlj + cntj + xhth) = 72 + (57 + 57 + 57) = 243
 *
 * As you can see, tknk's disc is unbalanced: ugml's stack is heavier than the other two. Even though the nodes above
 * ugml are balanced, ugml itself is too heavy: it needs to be 8 units lighter for its stack to weigh 243 and keep the
 * towers balanced. If this change were made, its weight would be 60.
 *
 * Given that exactly one program is the wrong weight, what would its weight need to be to balance the entire tower?
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

function buildTree(array &$data): array
{
    // Find items with no children.
    $top = getTop($data);

    // Build the tree.
    $tree = [
        $top => recurseChildren($data, $top),
    ];

    return $tree;
}


function recurseChildren(array &$data, string $parent): array
{
    $return             = $data[$parent];
    $return['children'] = [];

    foreach ($data[$parent]['children'] as $child) {
        $return['children'][$child] = recurseChildren($data, $child);
    }

    $childrenWeight        = array_column($return['children'], 'totalWeight');
    $return['totalWeight'] = $return['weight'] + array_sum($childrenWeight);

    return $return;
}

function findUnbalanced(array &$data): string
{
    $tree = buildTree(buildRelationships($data));
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

$testTree = buildTree(buildRelationships(parseData($test1)));
//$inputTree = buildTree(buildRelationships(parseData($input)));

echo 'Test unbalanced: ' . findUnbalanced(parseData($test1)). PHP_EOL;


