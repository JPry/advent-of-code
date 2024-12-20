<?php
declare(strict_types=1);

namespace JPry\AdventOfCode\y2024\day02;

use JPry\AdventOfCode\DayPuzzle;

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

		return $safe;
	}

	protected function part2Logic($input)
	{
		$safe = 0;
		foreach ($input as $line) {
			$levels = array_map('intval', explode(' ', $line));

			if ($this->is_line_safe($levels)) {
				$safe++;
			}
		}

		return $safe;
	}

	public function is_line_safe(array $levels): bool
	{
		static $is_trying_removal = false;
		$last_level = null;
		$last_diff = null;

		foreach ($levels as $level) {
			// Set up the first element and continue.
			if (null === $last_level) {
				$last_level = $level;
				continue;
			}

			// Calculate the difference between the last level and this one.
			$diff = $last_level - $level;
			$last_level = $level;


			$big_diff = abs($diff) > 3;
			$no_diff = 0 === $diff;

			if ($big_diff || $no_diff) {
				// If we're already trying to remove, return false for these because
				// we can only remove one element.
				if ($is_trying_removal) {
					return false;
				}

				$is_trying_removal = true;
				$is_line_safe = $this->try_level_without_index($levels);
				$is_trying_removal = false;

				return $is_line_safe;
			}

			// If we don't have a last diff, set it and continue.
			if (null === $last_diff) {
				$last_diff = $diff;
				continue;
			}

			if (($last_diff < 0 && $diff > 0) || ($last_diff > 0 && $diff < 0)) {
				if ($is_trying_removal) {
					return false;
				}

				// Check if either of these is safe.
				$is_trying_removal = true;
				$is_line_safe = $this->try_level_without_index($levels);
				$is_trying_removal = false;

				return $is_line_safe;
			}
		}

		return true;
	}

	protected function try_level_without_index(array $levels): bool
	{
		// Try removing each element one by one to see if any work.
		for ($i = 0; $i < count($levels); $i++) {
			$copy = $levels;
			unset($copy[$i]);
			if ($this->is_line_safe($copy)) {
				return true;
			}
		}

		return false;
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
