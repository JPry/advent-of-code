<?php
declare(strict_types=1);

namespace JPry\AdventOfCode\y2022\day13;

use JPry\AdventOfCode\DayPuzzle;
use JPry\AdventOfCode\Utils\StringManipulation;
use JPry\AdventOfCode\Utils\WalkResource;

/**
 * Day Solver Class.
 *
 * @link https://adventofcode.com/2022/day/13
 */
class Solver extends DayPuzzle
{
	use StringManipulation;

	public function runTests()
	{
		$data = $this->splitFileByDoubleNewLine('test');
		$this->part1Logic($data);
		$this->part2Logic($data);
	}

	protected function part1()
	{
	}

	protected function part2()
	{
	}

	protected function part1Logic($input)
	{
		$pairs = [];
		foreach ($input as $rawPair) {
			$pair = explode("\n", $rawPair);
			$pair = array_map(
				function($value) {
					return json_decode($value);
				},
				$pair
			);

			$pairs[] = $pair;
		}
	}

	protected function part2Logic($input)
	{

	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
