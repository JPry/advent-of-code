<?php
declare(strict_types=1);

namespace JPry\AdventOfCode\y2024\day01;

use JPry\AdventOfCode\DayPuzzle;

/**
 * Day Solver Class.
 *
 * @link https://adventofcode.com/2024/day/01
 */
class Solver extends DayPuzzle
{
	public function runTests()
	{
		$data = $this->getFileAsArray('test');
		$this->part1Logic($data);
		$this->part2Logic($data);
	}

	protected function part1()
	{
		$this->part1Logic($this->getFileAsArray());
	}

	protected function part2()
	{
		$this->part2Logic($this->getFileAsArray());
	}

	protected function part1Logic($input)
	{
		[$firsts, $seconds] = $this->get_lists($input);

		sort($firsts);
		sort($seconds);

		$sums = [];
		foreach ($firsts as $index => $first) {
			$sums[] = abs($first - $seconds[$index]);
		}

		$this->writeln("The sum is: " . array_sum($sums));
	}

	protected function part2Logic($input)
	{
		[$firsts, $seconds] = $this->get_lists($input);
		$counted = array_count_values($seconds);

		$sums = [];
		foreach ($firsts as $first) {
			if (!array_key_exists($first, $counted)) {
				continue;
			}

			$sums[] = $first * $counted[$first];
		}

		$this->writeln("The sum is: " . array_sum($sums));
	}

	protected function get_lists(array $input): array
	{
		$firsts = [];
		$seconds = [];
		foreach ($input as $line) {
			preg_match('#(\d+)\s*(\d+)#', $line, $matches);
			$firsts[] = (int) $matches[1];
			$seconds[] = (int) $matches[2];
		}

		return [$firsts, $seconds];
	}


	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
