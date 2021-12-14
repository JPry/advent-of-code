<?php


namespace JPry\AdventOfCode\y2021\day08;

use Exception;
use JPry\AdventOfCode\DayPuzzle;

class Solver extends DayPuzzle
{
	public function runTests()
	{
		$handle = $this->getHandleForFile('test');
//		$this->part1Logic($handle);
//		rewind($handle);
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

			[, $output] = explode(' | ', $line);

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
		$sort = function (string $value) {
			return $this->sortSegment($value);
		};
		$total = 0;
		while ($line = fgets($input)) {
			$line = trim($line);
			if (empty($line)) {
				continue;
			}

			[$patterns, $output] = explode(' | ', $line);
			$patterns = array_map($sort, explode(' ', $patterns));
			$output = array_map($sort, explode(' ', $output));

			$digits = $this->uncrossWires($patterns);

			$relevantDigits = array_reduce(
				$output,
				function (string $carry, string $digit) use ($digits) {
					return "{$carry}{$digits[$digit]}";
				},
				''
			);
			$total += intval($relevantDigits);
		}

		printf("Total for all lines is: %d\n", $total);
	}

	protected function uncrossWires(array $pieces): array
	{
		$digits = $fives = $sixes = [];
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

		// Figure out digits with length of five and six.
		$this->deduceFives($digits, $fives);
		$this->deduceSixes($digits, $sixes);

		return array_flip($digits);
	}

	protected function deduceFives(array &$digits, array $fives)
	{
		// The digit for 2 will only have 2 letters in common with 4.
		foreach ($fives as $key => $five) {
			$fivePieces = str_split($five);
			if (2 === strlen(str_replace($fivePieces, '', $digits[4]))) {
				$digits[2] = $five;
				unset($fives[$key]);
				break;
			}
		}

		if (!isset($digits[2])) {
			throw new Exception("Uh-oh, no digit 2 found 🤔");
		}

		/*
		 * Digit 2 has 4 characters in common with digit 3, but only 3 in common with 5.
		 * This means removing common characters will leave 1 left for the digit 3,
		 * and will leave 2 left for the digit 5.
		 */
		$twoPieces = str_split($digits[2]);
		foreach ($fives as $five) {
			$commonCount = strlen(str_replace($twoPieces, '', $five));
			if (1 === $commonCount) {
				$digits[3] = $five;
			} elseif (2 === $commonCount) {
				$digits[5] = $five;
			} else {
				throw new Exception("Uh-oh, couldn't match to 3 or 5 🤔");
			}
		}
	}

	protected function deduceSixes(array &$digits, array $sixes)
	{
		// The digit 9 is the digit 5 + 1. Eliminating common digits leaves nothing left
		$fiveSegments = str_split($digits[5]);
		$oneSegments = str_split($digits[1]);
		$oneAndFiveSegments = array_merge($fiveSegments, $oneSegments);
		$nineSegments = array_unique($oneAndFiveSegments);
		foreach ($sixes as $six) {
			// See if this is the digit nine.
			if (0 === strlen(str_replace($nineSegments, '', $six))) {
				$digits[9] = $six;
				continue;
			}

			/*
			 * If it's not the digit 9, we can compare to the digit 5.
			 * The digit 6 will have 1 character left compared to the digit 5,
			 * and the digit 0 will have 2 characters left.
			 */
			$commonCount = strlen(str_replace($fiveSegments, '', $six));
			if (1 === $commonCount) {
				$digits[6] = $six;
			} elseif (2 === $commonCount) {
				$digits[0] = $six;
			} else {
				throw new Exception("Uh-oh, couldn't match to 6 or 0 🤔");
			}
		}
	}

	protected function sortSegment(string $segment): string
	{
		$pieces = str_split($segment);
		sort($pieces);
		return join('', $pieces);
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
