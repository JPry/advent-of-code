<?php
declare(strict_types=1);

namespace JPry\AdventOfCode\y2022\day08;

use JPry\AdventOfCode\DayPuzzle;
use JPry\AdventOfCode\Map;
use JPry\AdventOfCode\MapTrait;
use JPry\AdventOfCode\Point;

class Solver extends DayPuzzle
{
	use MapTrait;

	public function runTests()
	{
		$data = $this->getInput('test');
		$this->part1Logic($data);
		$this->part2Logic($data);

		$test1 = [];
		for ($i=0; $i < 10; $i++) {
			$test1[] = array_fill(0, 10, $i);
		}

		$this->part1Logic($test1);

		$test2 = [];
		for ($i = 0; $i < 10; $i++) {
			$test2[] = range(0, 9);
		}

		$this->part1Logic($test2);
	}

	protected function part1()
	{
		$this->part1Logic($this->getInput());
	}

	protected function part2()
	{
	}

	protected function part1Logic(array $input)
	{
		$this->setMap(new Map($input));
		$lastColumnIndex = $this->map->getLastColumnIndex();
		$lastRowIndex = $this->map->getLastRowIndex();

		// Count the number of visible trees.
		$current = [0, 0];
		$counts = [];
		$rowCount = 0;
		do {
			// Handle the first and last row with no extra logic
			if ($current[0] === 0) {
				$counts[0] = $this->map->getColumnCount();
				$current[0]++;
				continue;
			}

			if ($current[0] === $lastRowIndex) {
				$counts[$lastRowIndex] = $this->map->getColumnCount();
				break;
			}

			// Handle each column.
			while ($current[1] <= $lastColumnIndex) {
				$treeHeight = $this->map->getValue(new Point(...$current));
				if ($current[1] === 0 || $current[1] === $lastColumnIndex) {
					$rowCount++;
				} elseif ($treeHeight === 0) {
					$current[1]++;
					continue;
				} else {
					// look west
					$looking = $current;
					do {
						$looking[1]--;

						// If a tree is taller or the same height, this one isn't visible, break out of this loop.
						if ($treeHeight <= $this->map->getValue(new Point(...$looking))) {
							break;
						}

						// If we've gotten to the edge, the inner tree is taller than the rest.
						if ($looking[1] === 0) {
							$rowCount++;
							$current[1]++;
							continue 2;
						}
					} while (true);

					// look north
					$looking = $current;
					do {
						$looking[0]--;
						// If a tree is taller or the same height, this one isn't visible, break out of this loop.
						if ($treeHeight <= $this->map->getValue(new Point(...$looking))) {
							break;
						}

						// If we've gotten to the edge, the inner tree is taller than the rest.
						if ($looking[0] === 0) {
							$rowCount++;
							$current[1]++;
							continue 2;
						}
					} while (true);

					// look east
					$looking = $current;
					do {
						$looking[1]++;
						// If a tree is taller or the same height, this one isn't visible, break out of this loop.
						if ($treeHeight <= $this->map->getValue(new Point(...$looking))) {
							break;
						}

						// If we've gotten to the edge, the inner tree is taller than the rest.
						if ($looking[1] === $lastColumnIndex) {
							$rowCount++;
							$current[1]++;
							continue 2;
						}
					} while (true);

					// look south
					$looking = $current;
					do {
						$looking[0]++;
						// If a tree is taller or the same height, this one isn't visible, break out of this loop.
						if ($treeHeight < $this->map->getValue(new Point(...$looking))) {
							break;
						}

						// If we've gotten to the edge, the inner tree is taller than the rest.
						if ($looking[0] === $lastRowIndex) {
							$rowCount++;
							$current[1]++;
							continue 2;
						}
					} while (true);
				}
				$current[1]++;
			}

			$counts[$current[0]] = $rowCount;
			$current[0]++;
			$current[1] = 0;
			$rowCount = 0;
		} while (true);

		printf("The number of visible trees is: %d\n", array_sum($counts));
	}

	protected function part2Logic($input)
	{

	}

	protected function getInput(string $filename = 'input')
	{
		return array_map(
			function($value) {
				return array_map(
					function($value) {
						return (int) $value;
					},
					str_split($value)
				);
			},
			$this->getFileAsArray($filename)
		);
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
