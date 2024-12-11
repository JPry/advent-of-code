<?php
declare(strict_types=1);

namespace JPry\AdventOfCode\y2024\day04;

use JPry\AdventOfCode\DayPuzzle;
use JPry\AdventOfCode\Map;
use JPry\AdventOfCode\Point;

/**
 * Day Solver Class.
 *
 * @link https://adventofcode.com/2024/day/04
 */
class Solver extends DayPuzzle
{
	public function runTests()
	{
		$data = $this->getFileAsArray('test');
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
		$rawMap = array_map('str_split', $input);
		$map = new Map($rawMap);
		$found = 0;
		$map->walkMap(function($value, $row, $column, $map) use (&$found) {
			if ('X' !== $value) {
				return;
			}


		});
	}

	protected function checkForNextLetter(string $currentLetter, array $availablePoints)
	{

	}

	protected function part2Logic($input)
	{

	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
