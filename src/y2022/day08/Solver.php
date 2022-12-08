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
		$current = [0, 0];
		$lastColumnIndex = $this->map->getLastColumnIndex();
		$lastRowIndex = $this->map->getLastRowIndex();
		$count = 0;

		// Count the number of visible trees.
		do {
			// Handle the first and last row with no extra logic
			if ($current[0] === 0) {
				$count += $this->map->getColumnCount();
				$current[0]++;
				continue;
			}

			if ($current[0] === $lastRowIndex) {
				$count += $this->map->getColumnCount();
				break;
			}

			// Handle each column.
			while ($current[1] <= $lastColumnIndex) {
				if ($current[1] === 0 || $current[1] === $lastColumnIndex) {
					$count++;
				} else {
					$treeHeight = $this->map->getValue(new Point(...$current));

					// look west
					$looking = $current;
					do {
						$looking[1]--;

						// If a tree is taller, this one isn't visible, break out of this loop.
						if ($treeHeight <= $this->map->getValue(new Point(...$looking))) {
							break;
						}

						// If we've gotten to the edge, the inner tree is taller than the rest.
						if ($looking[1] === 0) {
							$count++;
							$current[1]++;
							continue 2;
						}
					} while ($looking[1] > 0);

					// look north
					$looking = $current;
					do {
						$looking[0]--;
						// If a tree is taller, this one isn't visible, break out of this loop.
						if ($treeHeight <= $this->map->getValue(new Point(...$looking))) {
							break;
						}

						// If we've gotten to the edge, the inner tree is taller than the rest.
						if ($looking[0] === 0) {
							$count++;
							$current[1]++;
							continue 2;
						}
					} while ($looking[0] > 0);

					// look east
					$looking = $current;
					do {
						$looking[1]++;
						// If a tree is taller, this one isn't visible, break out of this loop.
						if ($treeHeight <= $this->map->getValue(new Point(...$looking))) {
							break;
						}

						// If we've gotten to the edge, the inner tree is taller than the rest.
						if ($looking[1] === $lastColumnIndex) {
							$count++;
							$current[1]++;
							continue 2;
						}
					} while ($looking[1] <= $lastColumnIndex);

					// look south
					$looking = $current;
					do {
						$looking[0]++;
						// If a tree is taller, this one isn't visible, break out of this loop.
						if ($treeHeight < $this->map->getValue(new Point(...$looking))) {
							break;
						}

						// If we've gotten to the edge, the inner tree is taller than the rest.
						if ($looking[0] === $lastRowIndex) {
							$count++;
							$current[1]++;
							continue 2;
						}
					} while ($looking[0] < $lastRowIndex);
				}
				$current[1]++;
			}
			$current[0]++;
			$current[1] = 0;
		} while (true);

		printf("The number of visible trees is: %d\n", $count);
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
