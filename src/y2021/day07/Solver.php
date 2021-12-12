<?php


namespace JPry\AdventOfCode\y2021\day07;

use JPry\AdventOfCode\DayPuzzle;

class Solver extends DayPuzzle
{
	public function runTests()
	{
		$testData = $this->stringToNumbers($this->getFileContents('test'));
		$this->part1Logic($testData);
		$this->part2Logic($testData);
	}

	protected function part1()
	{
		$this->part1Logic($this->stringToNumbers($this->getFileContents('input')));
	}

	protected function part2()
	{
		$this->part2Logic($this->stringToNumbers($this->getFileContents('input')));
	}

	protected function part1Logic(array $crabbies)
	{
		// Find the most common number
		$counts = array_count_values($crabbies);
		rsort($counts);

		// Start with the most common number and increment up.
		$current = $counts[0];
		$maybeLowest = $this->calculateFuel($crabbies, $current);
		$foundLowest = false;
		while (!$foundLowest) {
			$current++;
			$latest = $this->calculateFuel($crabbies, $current);
			if ($latest > $maybeLowest) {
				$foundLowest = true;
			} else {
				$maybeLowest = $latest;
			}
		}

		printf("Minimum fuel: %d\n", $maybeLowest);
	}

	protected function part2Logic(array $crabbies)
	{
		// Find the most common number
		$counts = array_count_values($crabbies);
		rsort($counts);

		// Start with the most common number and increment up.
		$current = $counts[0];
		$maybeLowest = $this->betterCalculateFuel($crabbies, $current);
		$foundLowest = false;
		while (!$foundLowest) {
			$current++;
			$latest = $this->betterCalculateFuel($crabbies, $current);
			if ($latest > $maybeLowest) {
				$foundLowest = true;
			} else {
				$maybeLowest = $latest;
			}
		}

		printf("Minimum fuel: %d\n", $maybeLowest);
	}

	protected function calculateFuel(array $crabbies, int $number)
	{
		$fuels = array_map(
			function ($value) use ($number) {
				return abs($value - $number);
			},
			$crabbies
		);

		return array_sum($fuels);
	}

	protected function betterCalculateFuel(array $crabbies, int $number)
	{
		$fuels = array_map(
			function ($value) use ($number) {
				$difference = abs($value - $number);
				return array_sum(range(0, $difference));
			},
			$crabbies
		);

		return array_sum($fuels);
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
