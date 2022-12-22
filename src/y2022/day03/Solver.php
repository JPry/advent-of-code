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
		$this->part1Logic($this->getFileAsArray());
	}

	protected function part2()
	{
		$this->part2Logic($this->getFileAsArray());
	}

	protected function part1Logic($input)
	{
		$count = 0;
		foreach ($input as $line) {
			[$first, $second] = str_split($line, strlen($line)/2);
			$firstPieces = $this->stringToKeyedArray($first);
			$secondPieces = $this->stringToKeyedArray($second);

			$leftover = array_intersect_key($firstPieces, $secondPieces);
			$count += $this->calculateValue(array_flip($leftover)[1]);
		}

		printf("The count was: %d\n", $count);
	}

	protected function part2Logic($input)
	{
		$count = 0;
		$offset = 0;

		do {
			$pieces = array_map(
				[$this, 'stringToKeyedArray'],
				array_slice($input, $offset, 3)
			);

			$common = array_intersect_key(...$pieces);
			$count += $this->calculateValue(array_flip($common)[1]);

			$offset += 3;
		} while (count($input) > $offset);

		printf("The count was: %d\n", $count);
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}

	/**
	 * Use ASCII values to calculate an integer.
	 *
	 * @link https://www.man7.org/linux/man-pages/man7/ascii.7.html
	 *
	 * @param string $char
	 *
	 * @return int
	 */
	protected function calculateValue(string $char): int
	{
		$value = ord($char);
		return $value >= 97 ? $value - 96 : $value - 38;
	}
}
