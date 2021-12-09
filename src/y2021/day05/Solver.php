<?php


namespace JPry\AdventOfCode\y2021\day05;

use JPry\AdventOfCode\DayPuzzle;

class Solver extends DayPuzzle
{
	/** @var Line[] */
	protected $lines = [];

	/** @var array */
	protected array $map = [];

	public function runTests()
	{
		$testLines = $this->parseLines($this->getHandleForFile('test'));
		$this->part1Logic($testLines);
		$this->part2Logic($testLines);
	}

	protected function part1()
	{
		$this->lines = $this->parseLines($this->getHandleForFile('input'));
		$this->part1Logic($this->lines);
	}

	protected function part2()
	{
	}

	/**
	 * @param Line[] $input
	 */
	protected function part1Logic(array $input)
	{
		// Generate the map based on the largest values of X and Y.
		$mergedInputs = array_merge(
			array_column($input, 'start'),
			array_column($input, 'end')
		);
		$maxX = max(array_column($mergedInputs, 'x'));
		$maxY = max(array_column($mergedInputs, 'y'));
		$this->map = array_fill(
			0,
			$maxY + 1,
			array_fill(0, $maxX + 1, 0)
		);

		// Work through all of the points to increment them.
		foreach ($input as $line) {
			foreach ($line->getPoints() as $point) {
				$this->map[$point->y][$point->x]++;
			}
		}

		$pointsGreaterThanOne = 0;
		foreach ($this->map as $row) {
			$pointsGreaterThanOne += count(
				array_filter(
					$row,
					function ($value) {
						return $value > 1;
					}
				)
			);
		}

		printf("There are %d points 2 or greater.\n", $pointsGreaterThanOne);
	}

	protected function part2Logic($input)
	{

	}

	/**
	 * @param resource $input
	 * @return Line[]
	 */
	protected function parseLines($input): array
	{
		$lines = [];
		while ($line = fgets($input)) {
			if (empty(trim($line))) {
				continue;
			}

			preg_match('#^(\d+),(\d+)\s*\->\s*(\d+),(\d+)#', $line, $matches);

			$lines[] = new Line(
				new Point($matches[1], $matches[2]),
				new Point($matches[3], $matches[4])
			);
		}

		return $lines;
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
