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

		printf("Found %d increases\n", $increases);
	}


	protected function part2()
	{
	}

	protected function getNamespace()
	{
		return __NAMESPACE__;
	}
}
