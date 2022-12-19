<?php
declare(strict_types=1);

namespace JPry\AdventOfCode\y2022\day03;

use JPry\AdventOfCode\DayPuzzle;
use JPry\AdventOfCode\Utils\StringManipulation;

/**
 * Day Solver Class.
 *
 * @link https://adventofcode.com/2022/day/3
 */
class Solver extends DayPuzzle
{
	use StringManipulation;

	public function runTests()
	{
		$data = $this->getFileAsArray('test');
		$this->part1Logic($data);
		$this->part2Logic($data);
	}

	protected function part1()
	{
		$this->part1Logic($this->getFileAsArray('input'));
	}

	protected function part2()
	{
		$this->part2Logic($this->getFileAsArray('input'));
	}

	protected function part1Logic($input)
	{
		$map = $this->getScoreMap();
		$count = 0;
		foreach ($input as $line) {
			[$first, $second] = str_split($line, strlen($line)/2);
			$firstPieces = $this->stringToKeyedArray($first);
			$secondPieces = $this->stringToKeyedArray($second);

			$leftover = array_intersect_key($firstPieces, $secondPieces);
			$count += $map[array_flip($leftover)[1]];
		}

		printf("The count was: %d\n", $count);
	}

	protected function part2Logic($input)
	{
		$map = $this->getScoreMap();
		$count = 0;

		do {
			// get 3 items.
			$pieces[] = array_shift($input);
			$pieces[] = array_shift($input);
			$pieces[] = array_shift($input);

			foreach ($pieces as &$piece) {
				$piece = $this->stringToKeyedArray($piece);
			}

			$common = array_intersect_key(...$pieces);
			$count += $map[array_flip($common)[1]];

			$pieces = [];
		} while (count($input) >= 3);

		printf("The count was: %d\n", $count);
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}

	/**
	 * @return array|false
	 */
	protected function getScoreMap()
	{
		return array_combine(
			array_merge(range('a', 'z'), range('A', 'Z')),
			range(1, 52)
		);
	}
}
