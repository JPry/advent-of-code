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
		$this->part1Logic(file($this->getFilePath(), FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES));
	}

	protected function part2()
	{
		$this->part2Logic(file($this->getFilePath(), FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES));
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

		printf("There were %d flashes\n", $flashes);
	}

	protected function part2Logic($lines)
	{
		$octopod = new Octopod(
			array_map(
				function ($line) {
					return array_map('intval', str_split($line));
				},
				$lines
			)
		);

		$flashGoal = $octopod->getSize();
		$steps = 0;
		do {
			$octopod->energize();
			$flashes = $octopod->getFlashCount();
			$steps++;
		} while ($flashes !== $flashGoal);

		printf("It took %d steps to synchronize\n", $steps);
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
