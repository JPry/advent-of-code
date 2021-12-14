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
		$this->part2Logic($this->getHandleForFile('input'));
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
		while ($line = fgets($input)) {
			$line = trim($line);
			if (empty($line)) {
				continue;
			}

			[$patterns, $output] = explode(' | ', $line);
			$patterns = explode(' ', $patterns);
			$output = explode(' ', $output);

			$result = $this->uncrossWires($patterns, $output);
		}
	}

	protected function uncrossWires(array $patterns): array
	{
		$digits = [];
		$pieces = $patterns;
		usort(
			$pieces,
			function ($a, $b) {
				$lengthA = strlen($a);
				$lengthB = strlen($b);
				return $lengthA === $lengthB
					? 0
					: ($lengthA < $lengthB ? -1 : 1);
			}
		);

		$fives = [];
		$sixes = [];
		foreach ($pieces as $piece) {
			$length = strlen($piece);
			switch ($length) {
				case 2:
					$digits[1] = $piece;
					break;

				case 3:
					$digits[7] = $piece;
					break;

				case 4:
					$digits[4] = $piece;
					break;

				// This will give us the digits 2, 3, and 5
				case 5:
					$fives[] = $piece;
					break;

				// This will give us the digits 6, 9, and 0
				case 6:
					$sixes[] = $piece;
					break;

				case 7:
					$digits[8] = $piece;
					break;
			}
		}

		// Figure out digits with length of five.
		// The digit for 2 will only have 2 letters in common with 4.
		$fourPieces = str_split($digits[4]);
		foreach ($fives as $key => $five) {
			if (2 === count(str_replace($fourPieces, '', $five))) {
				$digits[2] = $five;
				unset($fives[$key]);
				break;
			}
		}

		return $digits;
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
