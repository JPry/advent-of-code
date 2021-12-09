<?php


namespace JPry\AdventOfCode\y2021\day06;

use JPry\AdventOfCode\DayPuzzle;

class Solver extends DayPuzzle
{
	public function runTests()
	{
		$testData = array_map('intval', explode(',', trim(file_get_contents($this->getFilePath('test')))));
		$this->part1Logic($testData);
		$this->part2Logic($testData);
	}

	protected function part1()
	{
	}

	protected function part2()
	{
	}

	protected function part1Logic(array $fishes)
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
//			printf("After %d %s: %d\n", $dayCount, $dayCount > 1 ? 'days' : 'day', count($fishes));
		} while ($dayCount < 80);

		printf("There are %d fish!\n", count($fishes));
	}

	protected function part2Logic($input)
	{

	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
