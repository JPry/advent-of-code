<?php
declare(strict_types=1);

namespace JPry\AdventOfCode\y2023\day04;

use JPry\AdventOfCode\DayPuzzle;

/**
 * Day Solver Class.
 *
 * @link https://adventofcode.com/2023/day/04
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
		$sum = array_reduce(
			$input,
			function ($carry, $line) {
				[, $numbers] = explode(': ', $line);
				$count = $this->getWinCount($numbers);
				$score = $count > 0 ? 2 ** ($count - 1) : 0;

				return $carry + $score;
			},
			0
		);

		$this->writeln("The sum is: {$sum}");
	}

	protected function part2Logic($input)
	{
		$lastLine = count($input);
		$cards = array_fill_keys(range(1, $lastLine), 1);

		array_walk(
			$input,
			function ($line) use (&$cards, $lastLine) {
				[$cardID, $numbers] = explode(': ', $line);
				$cardID = (int) preg_replace('#\D#', '', $cardID);

				$count = $this->getWinCount($numbers);

				// Add more copies of subsequent cards based on the winnings.
				$nextCard = $cardID + 1;
				while ($count > 0 && $nextCard <= $lastLine) {
					$cards[$nextCard] += $cards[$cardID];
					$nextCard++;
					$count--;
				}
			}
		);

		$this->writeln("The sum is: " . array_sum($cards));
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}



	/**
	 * @param $line
	 * @return int
	 */
	protected function getWinCount($line): int
	{
		[$winningNumbers, $numbers] = explode(' | ', $line);

		$numbers = array_fill_keys(array_map('intval', array_filter(explode(' ', $numbers))), true);
		$winningNumbers = array_fill_keys(array_map('intval', array_filter(explode(' ', $winningNumbers))), true);

		$matches = array_intersect_key($numbers, $winningNumbers);

		return count($matches);
	}
}
