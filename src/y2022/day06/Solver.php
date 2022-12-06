<?php
declare(strict_types=1);

namespace JPry\AdventOfCode\y2022\day06;

use JPry\AdventOfCode\DayPuzzle;

class Solver extends DayPuzzle
{
	public function runTests()
	{
		$data = $this->getFileContents('test');
		$this->part1Logic($data);
		$this->part1Logic($data, 14);
	}

	protected function part1()
	{
		$this->part1Logic($this->getFileContents());
	}

	protected function part2()
	{
		$this->part1Logic($this->getFileContents(), 14);
	}

	protected function part1Logic($input, $charLength = 4)
	{
		$signal = str_split($input);
		$offset = 0;
		do {
			$slice = array_slice($signal, $offset, $charLength);
			if (count(array_unique($slice)) === $charLength) {
				break;
			}

			$offset++;
		} while (true);

		printf("The first signal is after %d characters\n", $offset + $charLength);
	}

	protected function part2Logic($input) {}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
