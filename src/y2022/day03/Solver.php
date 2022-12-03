<?php
declare(strict_types=1);

namespace JPry\AdventOfCode\y2022\day03;

use JPry\AdventOfCode\DayPuzzle;

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
			$firstPieces = array_fill_keys(str_split($first), 1);
			$secondPieces = array_fill_keys(str_split($second), 1);

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
				$piece = array_fill_keys(str_split($piece), 1);
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
