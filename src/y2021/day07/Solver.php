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
	}

	protected function part2()
	{
	}

	protected function part1Logic(array $crabbies)
	{
		$min = min($crabbies);
		$max = max($crabbies);

		// Find the most common number
		$counts = array_count_values($crabbies);
		rsort($counts);

		// Let's try some numbers.
		$trialMin = $counts[0] - 10 < $min ? $min : $counts[0] - 10;
		$trialMax = $counts[0] + 10 > $max ? $max : $counts[0] + 10;
		$trials = range($trialMin, $trialMax);



	}

	protected function part2Logic($input)
	{

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

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
