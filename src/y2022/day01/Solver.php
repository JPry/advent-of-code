<?php
declare(strict_types=1);

namespace JPry\AdventOfCode\y2022\day01;

use JPry\AdventOfCode\DayPuzzle;
use JPry\AdventOfCode\Utils\WalkResource;

/**
 * Day Solver Class.
 *
 * @link https://adventofcode.com/2022/day/1
 */
class Solver extends DayPuzzle
{
	use WalkResource;

	public function runTests()
	{
		$data = $this->getHandleForFile('test');
//		$this->part1Logic($data);
		$this->part2Logic($data);
	}

	protected function part1()
	{
		$this->part1Logic($this->getHandleForFile('input'));
	}

	protected function part2()
	{
		$this->part2Logic($this->getHandleForFile('input'));
	}

	/**
	 * @param resource $input
	 * @return array
	 */
	protected function part1Logic($input): array
	{
		$elves = [0 => []];
		$elf = 0;

		$this->walkResourceWithCallback(
			$input,
			function($line) use (&$elves, &$elf) {
				if ('' === $line) {
					$elf++;
					$elves[$elf] ??= [];
					return;
				}

				$elves[$elf][] = (int) $line;
			}
		);

		$currentMax = 0;
		$maxElf = 0;
		foreach ($elves as $number => $foodItems) {
			$calories = array_sum($foodItems);
			$elves[$number]['sum'] = $calories;
			if ($calories > $currentMax) {
				$currentMax = $calories;
				$maxElf = $number;
			}
		}

		printf("Elf %d had the most calories: %d\n", $maxElf, $currentMax);

		return $elves;
	}

	/**
	 * @param resource $input
	 * @return void
	 */
	protected function part2Logic($input)
	{
		$elfData = $this->part1Logic($input);
		$sums = array_column($elfData, 'sum');
		rsort($sums);

		$top3 = array_slice($sums, 0, 3);

		printf("The top 3 elves had %d calories in total.\n", array_sum($top3));
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
