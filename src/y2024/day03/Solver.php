<?php
declare(strict_types=1);

namespace JPry\AdventOfCode\y2024\day03;

use JPry\AdventOfCode\DayPuzzle;

/**
 * Day Solver Class.
 *
 * @link https://adventofcode.com/2024/day/03
 */
class Solver extends DayPuzzle
{
	public function runTests()
	{
		$data = $this->getFileContents('test');
		$this->part1Logic($data);
		$this->part2Logic($data);
	}

	protected function part1()
	{
		return $this->part1Logic($this->getFileContents());
	}

	protected function part2()
	{
		return $this->part2Logic($this->getFileContents());
	}

	protected function part1Logic($input)
	{
		$sums = [];
		preg_match_all('#mul\((\d+),(\d+)\)#', $input, $matches);
		foreach ($matches[0] as $index => $match) {
			$sums[] = (int) $matches[1][$index] * (int) $matches[2][$index];
		}

		return array_sum($sums);
	}

	protected function part2Logic($input)
	{
		$sums = [];
		$is_matching = true;
		preg_match_all('#mul\((\d+),(\d+)\)|do\(\)|don\'t\(\)#', $input, $matches);
		foreach ($matches[0] as $index => $match) {
			match ($match) {
				'do()' => $is_matching = true,
				"don't()" => $is_matching = false,
				default => $sums[] = $is_matching ? (int) $matches[1][$index] * (int) $matches[2][$index] : 0,
			};
		}

		return array_sum($sums);
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
