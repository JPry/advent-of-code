<?php
declare(strict_types=1);

namespace JPry\AdventOfCode\y2024\day11;

use JPry\AdventOfCode\DayPuzzle;

/**
 * Day Solver Class.
 *
 * @link https://adventofcode.com/2024/day/11
 */
class Solver extends DayPuzzle
{
	protected int $iterations = 25;
	protected array $knownResults = [
		'0' => ['1'],
	];

	public function runTests()
	{
		$data = $this->getFileAsArray('test');
		$this->part1Logic($data);
		$this->part2Logic($data);
	}

	protected function part1()
	{
		$this->iterations = 25;
		return $this->part1Logic($this->getFileContents());
	}

	protected function part2()
	{
		$this->iterations = 75;
		return $this->part2Logic($this->getFileContents());
	}

	protected function part1Logic($input)
	{
		$numbers = explode(' ', $input);
		$iterations = 0;
		$numberMap = [];
		foreach ($numbers as $number) {
			$numberMap[$number] = 1;
		}

		do {
			$newMap = [];
			foreach ($numberMap as $number => $count) {
				// Ensure we've got strings for the numbers.
				$number = (string) $number;

				// If the count is already 0, we can skip this number.
				if ($count === 0) {
					continue;
				}

				// Get the numbers that result from applying the rules.
				$newNumbers = $this->applyRulesToNumber($number);

				// Add the new numbers to the map, and set their count based on the count of the number we started with.
				foreach ($newNumbers as $newNumber) {
					if (!array_key_exists($newNumber, $newMap)) {
						$newMap[$newNumber] = $count;
					} else {
						$newMap[$newNumber] += $count;
					}
				}
			}

			$iterations++;
			$numberMap = $newMap;
		} while ($iterations < $this->iterations);

		return array_sum($numberMap);
	}

	public function applyRulesToNumber(string $number): array
	{
		if (array_key_exists($number, $this->knownResults)) {
			return $this->knownResults[$number];
		}

		if (strlen($number) % 2 === 0) {
			$half = strlen($number) / 2;
			$result = array_map(
				// Convert to int and then back to string to remove leading zeros.
				fn($value) => (string) intval($value),
				str_split($number, $half)
			);
		} else {
			$result = [(string) ($number * 2024)];
		}

		$this->knownResults[$number] = $result;
		return $result;
	}

	protected function part2Logic($input)
	{
		$this->iterations = 75;
		return $this->part1Logic($input);
	}

	public function returnTest1($input = null, $filename = 'test')
	{
		$this->iterations = 25;
		return $this->part1Logic($this->getFileContents($filename));
	}


	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
