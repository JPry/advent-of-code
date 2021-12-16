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

		$octopi = array_map(
			function ($line) {
				return array_map('intval', str_split($line));
			},
			$lines
		);

		do {
			// Increment each number.
			array_walk_recursive(
				$octopi,
				function (&$value) {
					$value++;
				}
			);

			// Do the flashes.
			$flashedThisStep = [];
			do {
				$didFlashes = false;
				foreach ($octopi as $rowIndex => $row) {
					foreach ($row as $columnIndex => $value) {
						// Each octopus flashes once per step.
						$key = sprintf('%d,%d', $rowIndex, $columnIndex);
						if (array_key_exists($key, $flashedThisStep)) {
							continue;
						}

						if ($value > 9) {
							$didFlashes = true;
							$flashedThisStep[$key] = true;
							$this->energizeAround($octopi, $rowIndex, $columnIndex);
						}
					}
				}
			} while ($didFlashes);

			$steps++;
		} while ($steps < 100);

		printf("There were %d flashes", $flashes);
	}

	protected function part2Logic($input)
	{

	}

	protected function energizeAround(array &$octopi, int $row, int $column)
	{
		$toEnergize = array_merge(

		);
		$canDoPriorRow = $row > 0;
		$canDoNextRow = $row < (count($octopi) - 1);
		$canDoPriorColumn = $column > 0;
		$canDoNextColumn = $column < (count($octopi[$row]) - 1);

	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
