<?php


namespace JPry\AdventOfCode\y2021\day08;

use Exception;
use JPry\AdventOfCode\DayPuzzle;

class Solver extends DayPuzzle
{
	public function runTests()
	{
		$handle = $this->getHandleForFile('test');
		$this->part1Logic($handle);
		rewind($handle);
		$this->part2Logic($handle);
	}

	protected function part1()
	{
		$this->part1Logic($this->getHandleForFile('input'));
	}

	protected function part2()
	{
	}

	protected function part1Logic($input)
	{
		$digitCount = 0;
		while ($line = fgets($input)) {
			$line = trim($line);
			if (empty($line)) {
				continue;
			}

			[$patterns, $output] = explode(' | ', $line);

			$pieces = explode(' ', $output);
			if (4 !== count($pieces)) {
				throw new Exception("Whoops, wrong count.");
			}

			foreach ($pieces as $piece) {
				switch (strlen($piece)) {
					case 2:
					case 3:
					case 4:
					case 7:
						$digitCount++;
						break;
				}
			}
		}

		printf("Found %d digits.\n", $digitCount);
	}

	protected function part2Logic($input)
	{

	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
