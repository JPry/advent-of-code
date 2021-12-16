<?php


namespace JPry\AdventOfCode\y2021\day11;

use JPry\AdventOfCode\DayPuzzle;

class Solver extends DayPuzzle
{
	public function runTests()
	{
		$lines = file($this->getFilePath('test'), FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
		$this->part1Logic($lines);
		$this->part2Logic($lines);
	}

	protected function part1()
	{
	}

	protected function part2()
	{
	}

	protected function part1Logic(array $lines)
	{
		$flashes = 0;
		$steps = 0;
		$octopod = new Octopod(
			array_map(
				function ($line) {
					return array_map('intval', str_split($line));
				},
				$lines
			)
		);

		do {
			$octopod->energize();
			$flashes += $octopod->getFlashCount();
			$steps++;
		} while ($steps < 100);

		printf("There were %d flashes", $flashes);
	}

	protected function part2Logic($input)
	{

	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
