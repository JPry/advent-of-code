<?php
declare(strict_types=1);

namespace JPry\AdventOfCode\y2023\day05;

use JPry\AdventOfCode\DayPuzzle;

/**
 * Day Solver Class.
 *
 * @link https://adventofcode.com/2023/day/05
 */
class Solver extends DayPuzzle
{
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

	/**
	 * @param string[] $input
	 * @return void
	 */
	protected function part1Logic(array $input)
	{
		$rawSeeds = array_shift($input);
		[, $seeds] = explode(': ', $rawSeeds);
		$seeds = array_map('intval', explode(' ', $seeds));

		// Figure out min and max seeds so we don't need to look outside the needed range.
		$minSeed = min($seeds);
		$maxSeed = max($seeds);

		$seed = array_combine(
			range($minSeed, $maxSeed),
			range($minSeed, $maxSeed),
		);

		$maps = ['seed' => []];
		foreach ($input as $map) {
			[$key, $values] = explode(" map:\n", $map);
			[$source, $destination] = explode('-to-', $key);
			$values = explode("\n", $values);
			foreach ($values as $value) {
				[$destinationStart, $sourceStart, $length] = explode(' ', $value);
			}
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
