<?php


namespace JPry\AdventOfCode\y2021\day01;

use JPry\AdventOfCode\DayPuzzle;

class Solver extends DayPuzzle
{
	public function testData()
	{
		$handle = $this->getHandleForFile('test');
		$expected = 7;

		$current = null;
		$increases = 0;
		while($row = fgets($handle)) {
			// handle the first row.
			if (null === $current) {
				$current = $row;
				continue;
			}

			if ($row > $current) {
				$increases++;
			}

			$current = $row;
		}

		printf("Found %d increases, expected %d\n", $increases, $expected);
	}

	protected function part1()
	{
		$handle = $this->getHandleForFile('input');
		$current = null;
		$increases = 0;
		while($row = fgets($handle)) {
			$row = intval(trim($row));

			// handle the first row.
			if (null === $current) {
				$current = $row;
				continue;
			}

			// handle empty data
			if (empty($row)) {
				continue;
			}

			if ($row > $current) {
				$increases++;
			}

			$current = $row;
		}

		printf("Part 1: Found %d increases\n", $increases);
	}


	protected function part2()
	{
		$increases = 0;
		$rows = array_map(
			function($value) {
				return intval(trim($value));
			},
			file($this->getFilePath('input'))
		);

		// Work through each set of three.
		$lastIndex = count($rows) - 1;
		$currentIndex = 2;
		$previousSum = 0;
		while($currentIndex <= $lastIndex) {
			$slice = array_slice($rows, $currentIndex - 2, 3);
			$sum = array_sum($slice);
			if (0 === $previousSum) {
				$previousSum = $sum;
				$currentIndex++;
				continue;
			}

			if ($sum > $previousSum) {
				$increases++;
			}

			$previousSum = $sum;
			$currentIndex++;
		}

		printf("Part 2: Found %d increases\n", $increases);
	}

	protected function getNamespace()
	{
		return __NAMESPACE__;
	}
}
