<?php


namespace JPry\AdventOfCode\y2021\day06;

use JPry\AdventOfCode\DayPuzzle;

class Solver extends DayPuzzle
{
	public function runTests()
	{
		$testData = $this->convertInputToFish(file_get_contents($this->getFilePath('test')));
		$this->part1Logic($testData, 18);
		$this->part2Logic($testData, 256);
	}

	protected function part1()
	{
		$this->part2Logic(
			$this->convertInputToFish(file_get_contents($this->getFilePath('input'))),
			80
		);
	}

	protected function part2()
	{
		$this->part2Logic(
			$this->convertInputToFish(file_get_contents($this->getFilePath('input'))),
			256
		);
	}

	protected function part1Logic(array $fishes, int $days)
	{
		$dayCount = 0;
		do {
			$newFish = 0;
			foreach ($fishes as &$fish) {
				if (0 === $fish) {
					$newFish++;
					$fish = 6;
					continue;
				}

				$fish--;
			}

			$fishes = array_merge($fishes, array_fill(0, $newFish, 8));

			$dayCount++;
		} while ($dayCount < $days);

		printf("There are %d fish!\n", count($fishes));
	}

	protected function part2Logic(array $fishes, int $days)
	{
		$fishForDays = array_replace(
			array_fill(0, 9, 0),
			array_count_values($fishes)
		);

		$dayCount = 0;
		do {
			$shiftedFish = array_fill(0, 9, 0);
			foreach($fishForDays as $index => $count) {
				if (0 === $index) {
					$shiftedFish[8] = $count;
					$shiftedFish[6] = $count;
					continue;
				}

				$shiftedFish[$index - 1] += $count;
			}

			$fishForDays = $shiftedFish;

			$dayCount++;
		} while ($dayCount < $days);
		printf("There are %d fish after %d days\n", array_sum($fishForDays), $dayCount);
	}

	protected function convertInputToFish(string $input): array
	{
		return array_map('intval', explode(',', trim($input)));
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
