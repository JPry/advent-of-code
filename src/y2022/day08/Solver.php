<?php
declare(strict_types=1);

namespace JPry\AdventOfCode\y2022\day08;

use JPry\AdventOfCode\DayPuzzle;
use JPry\AdventOfCode\Map;
use JPry\AdventOfCode\MapTrait;
use JPry\AdventOfCode\Point;

/**
 * Day Solver Class.
 *
 * @link https://adventofcode.com/2022/day/8
 */
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
		$lastColumnIndex = $this->map->getLastColumnIndex();
		$lastRowIndex = $this->map->getLastRowIndex();

		// Count the number of visible trees.
		$current = [
			'row' => 0,
			'column' => 0,
		];

		$counts = [];
		$rowCount = 0;
		do {
			// All outside trees are visible. Handle first and last row and continue.
			if ($current['row'] === 0) {
				$counts[] = $this->map->getColumnCount();
				$current['row']++;
				continue;
			}

			if ($current['row'] === $lastRowIndex) {
				$counts[] = $this->map->getColumnCount();
				break;
			}

			// Handle each column.
			while ($current['column'] <= $lastColumnIndex) {
				$treeHeight = $this->map->getValue(new Point(...array_values($current)));
				if ($current['column'] === 0 || $current['column'] === $lastColumnIndex) {
					$rowCount++;
					$current['column']++;
					continue;
				}

				if ($treeHeight === 0) {
					$current['column']++;
					continue;
				}

				// Look west.
				$west = array_slice($input[$current['row']], 0, $current['column']);
				if (max($west) < $treeHeight) {
					$rowCount++;
					$current['column']++;
					continue;
				}

				// Look east.
				$east = array_slice($input[$current['row']], $current['column'] + 1);
				if (max($east) < $treeHeight) {
					$rowCount++;
					$current['column']++;
					continue;
				}

				// Look north.
				$currentColumn = array_column($input, $current['column']);
				$north = array_slice($currentColumn, 0, $current['row']);
				if (max($north) < $treeHeight) {
					$rowCount++;
					$current['column']++;
					continue;
				}

				// Look south.
				$south = array_slice($currentColumn, $current['row'] + 1);
				if (max($south) < $treeHeight) {
					$rowCount++;
					$current['column']++;
					continue;
				}

				$current['column']++;
			}

			$counts[] = $rowCount;
			$current['row']++;
			$current['column'] = 0;
			$rowCount = 0;
		} while (true);

		printf("The number of visible trees is: %d\n", array_sum($counts));
	}

	protected function part2Logic($input)
	{
	}

	protected function getInput(string $filename = 'input'): array
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
