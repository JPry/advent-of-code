<?php
declare(strict_types=1);

namespace JPry\AdventOfCode\y2023\day02;

use JPry\AdventOfCode\DayPuzzle;

/**
 * Day Solver Class.
 *
 * @link https://adventofcode.com/2023/day/02
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
		$red = 12;
		$green = 13;
		$blue = 14;

		$valid = [];

		foreach ($input as $game) {
			[$id, $details] = explode(': ', $game);
			$id = intval(preg_replace('#\D#', '', $id));

			foreach (explode('; ', $details) as $set) {
				foreach (explode(', ', $set) as $part) {
					[$value, $color] = explode(' ', $part);
					$color = trim($color);
					$value = intval(trim($value));

					if ($value > $$color) {
						continue 3;
					}
				}
			}

			$valid[] = $id;
		}

		$this->writeln('Valid ID sum: ' . array_sum($valid));
	}

	protected function part2Logic($input)
	{
		$sum = array_reduce(
			$input,
			function($carry, $line) {
				$red = 1;
				$blue = 1;
				$green = 1;

				[, $details] = explode(': ', $line);

				foreach (explode('; ', $details) as $set) {
					foreach (explode(', ', $set) as $part) {
						[$value, $color] = explode(' ', $part);
						$color = trim($color);
						$value = intval(trim($value));

						$$color = max($$color, $value);
					}
				}

				return ($red * $blue * $green) + $carry;
			},
			0
		);

		$this->writeln('Part 2 power sum: ' . $sum);
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
