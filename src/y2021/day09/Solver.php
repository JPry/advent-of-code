<?php


namespace JPry\AdventOfCode\y2021\day09;

use JPry\AdventOfCode\DayPuzzle;

class Solver extends DayPuzzle
{
	public function runTests()
	{
		$map = $this->inputToArray(file($this->getFilePath('test')));
		$this->part1Logic($map);
		$this->part2Logic($map);
	}

	protected function part1()
	{
		$this->part1Logic($this->inputToArray(file($this->getFilePath('input'))));
	}

	protected function part2()
	{
		$this->part2Logic($this->inputToArray(file($this->getFilePath('input'))));
	}

	protected function part1Logic(array $map)
	{
		$riskCount = 0;
		$lastRowindex = count($map) - 1;
		array_walk(
			$map,
			function ($row, $rowIndex) use ($map, &$riskCount, $lastRowindex) {
				$lastColumnIndex = count($row) - 1;
				foreach ($row as $columnIndex => $value) {
					// 9 can never be a low spot.
					if (9 === $value) {
						continue;
					}

					// A 0 must be a low spot... I think?
					if (0 === $value) {
						$riskCount++;
						continue;
					}

					// Check all available directions.
					$comparison = [];

					// To the north.
					if ($rowIndex > 0) {
						$comparison['north'] = $map[$rowIndex - 1][$columnIndex] > $value;
					}

					// To the east.
					if ($columnIndex < $lastColumnIndex) {
						$comparison['east'] = $map[$rowIndex][$columnIndex + 1] > $value;
					}

					// To the south.
					if ($rowIndex < $lastRowindex) {
						$comparison['south'] = $map[$rowIndex + 1][$columnIndex] > $value;
					}

					// To the west.
					if ($columnIndex > 0) {
						$comparison['west'] = $map[$rowIndex][$columnIndex - 1] > $value;
					}

					$isLowest = array_reduce(
						$comparison,
						function (bool $carry, bool $value) {
							return $carry && $value;
						},
						true
					);

					if ($isLowest) {
						$riskCount += 1 + $value;
					}
				}
			}
		);

		printf("Risk count is: %d\n", $riskCount);
	}

	protected function part2Logic(array $map)
	{
		$lowPoints = $this->findLowPoints($map);
		$sizes = [];
		foreach ($lowPoints as [$row, $column]) {
			$basin = new Basin(new Point($row, $column));
			$basin->setMap(new Map($map));
			$basin->mapBasin();
			$sizes[] = $basin->getBasinPointCount();
		}

		rsort($sizes);
		$result = array_reduce(
			array_slice($sizes, 0, 3),
			function (int $carry, int $value) {
				return $carry * $value;
			},
			1
		);

		printf("Multiple of 3 largest basins is: %d\n", $result);
	}

	protected function inputToArray(array $inputLines): array
	{
		return array_filter(
			array_map(
				function ($line) {
					return array_map('intval', str_split(trim($line)));
				},
				$inputLines
			)
		);
	}

	protected function findLowPoints(array &$map): array
	{
		$points = [];
		$lastRowindex = count($map) - 1;
		array_walk(
			$map,
			function ($row, $rowIndex) use ($map, &$points, $lastRowindex) {
				$lastColumnIndex = count($row) - 1;
				foreach ($row as $columnIndex => $value) {
					// 9 can never be a low spot.
					if (9 === $value) {
						continue;
					}

					// A 0 must be a low spot... I think?
					if (0 === $value) {
						$points[] = [$rowIndex, $columnIndex];
						continue;
					}

					// Check all available directions.
					$comparison = [];

					// To the north.
					if ($rowIndex > 0) {
						$comparison['north'] = $map[$rowIndex - 1][$columnIndex] > $value;
					}

					// To the east.
					if ($columnIndex < $lastColumnIndex) {
						$comparison['east'] = $map[$rowIndex][$columnIndex + 1] > $value;
					}

					// To the south.
					if ($rowIndex < $lastRowindex) {
						$comparison['south'] = $map[$rowIndex + 1][$columnIndex] > $value;
					}

					// To the west.
					if ($columnIndex > 0) {
						$comparison['west'] = $map[$rowIndex][$columnIndex - 1] > $value;
					}

					$isLowest = array_reduce(
						$comparison,
						function (bool $carry, bool $value) {
							return $carry && $value;
						},
						true
					);

					if ($isLowest) {
						$points[] = [$rowIndex, $columnIndex];
					}
				}
			}
		);

		return $points;
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
