<?php
declare(strict_types=1);

namespace JPry\AdventOfCode\y2024\day02;

use JPry\AdventOfCode\DayPuzzle;
use ReturnTypeWillChange;

/**
 * Day Solver Class.
 *
 * @link https://adventofcode.com/2024/day/02
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
		return $this->part1Logic($this->getFileAsArray());
	}

	protected function part2()
	{
		return $this->part2Logic($this->getFileAsArray());
	}

	protected function part1Logic($input)
	{
		$safe = 0;
		foreach ($input as $line) {
			$levels = array_map('intval', explode(' ', $line));
			$last_level = array_shift($levels);
			$last_diff = null;

			foreach ($levels as $level) {
				$diff = $last_level - $level;

				// If it's more than 3 difference, it's not safe.
				$last_level = $level;
				if (abs($diff) > 3) {
					continue 2;
				}

				// See if the difference is increasing or decreasing

				// If diff is 0, this isn't safe.
				if (0 === $diff) {
					continue 2;
				}

				// If we don't have a last diff, set it and continue.
				if (null === $last_diff) {
					$last_diff = $diff;
					continue;
				}

				// If the last diff was negative, and this one is positive, it's not safe.
				if ($last_diff < 0 && $diff > 0) {
					continue 2;
				}

				// If the last diff was positive, and this one is negative, it's not safe.
				if ($last_diff > 0 && $diff < 0) {
					continue 2;
				}
			}

			$safe++;
		}

		$this->writeln("The number of safe levels is: {$safe}");

		return $safe;
	}

	protected function part2Logic($input)
	{
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
