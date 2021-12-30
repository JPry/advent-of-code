<?php


namespace JPry\AdventOfCode\y2021\day14;

use JPry\AdventOfCode\DayPuzzle;

class Solver extends DayPuzzle
{
	public function runTests()
	{
		$data = $this->getFileContents('test');
		$this->part2Logic($data);
		$this->part2Logic($data, 40);
	}

	protected function part1()
	{
		$this->part1Logic($this->getFileContents());
	}

	protected function part2()
	{
		$this->part2Logic($this->getFileContents(), 40);
	}

	protected function part1Logic(string $input, int $steps = 10)
	{
		[$template, $instructions] = $this->parseInput($input);
		unset($input);

		$newTemplate = '';
		$iterations = 0;
		do {
			$length = strlen($template);
			$offset = 0;
			while (true) {
				if ($offset + 2 > $length) {
					$newTemplate .= substr($template, -1);
					break;
				}
				$piece = substr($template, $offset, 2);
				$newTemplate .= substr($piece, 0, 1) . $instructions[$piece];
				$offset++;
			}

			$template = $newTemplate;
			$newTemplate = '';
			$iterations++;
		} while ($iterations < $steps);

		$counts = array_count_values(str_split($template));
		$max = max($counts);
		$min = min($counts);
		printf("Most common count (%d) - least common count (%d): %d\n", $max, $min, $max - $min);
	}

	protected function part2Logic(string $input, int $steps = 10)
	{
		[$template, $instructions] = $this->parseInput($input);
		$firstLetter = substr($template, 0, 1);
		$lastLetter = substr($template, -1);
		unset($input);

		// Set up initial pair counts.
		$pairCounts = [];
		$length = strlen($template);
		$offset = 0;
		while (true) {
			if ($offset + 2 > $length) {
				break;
			}

			$piece = substr($template, $offset, 2);
			if (!array_key_exists($piece, $pairCounts)) {
				$pairCounts[$piece] = 1;
			} else {
				$pairCounts[$piece]++;
			}

			$offset++;
		}

		$i = 0;
		do {
			$pairsThisCycle = $this->getNewPairs($pairCounts, $instructions);
			foreach ($pairsThisCycle as $pair => $count) {
				if (!array_key_exists($pair, $pairCounts)) {
					$pairCounts[$pair] = 0;
				}
				$pairCounts[$pair] += $count;
			}

			$i++;
		} while ($i < $steps);

		$chars = $this->countChars($pairCounts, $firstLetter, $lastLetter);

		$max = max($chars);
		$min = min($chars);
		printf("Most common count (%d) - least common count (%d): %d\n", $max, $min, $max - $min);
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}

	/**
	 * @param string $input
	 * @return array
	 */
	protected function parseInput(string $input): array
	{
		[$template, $rawInstructions] = explode("\n\n", $input);
		$instructions = [];
		array_map(
			function (string $line) use (&$instructions) {
				[$index, $value] = explode(' -> ', $line);
				$instructions[$index] = $value;
			},
			explode("\n", $rawInstructions)
		);
		return [$template, $instructions];
	}

	/**
	 * @return int[]
	 */
	protected function countChars(array $pairCounts, string $firstLetter, string $lastLetter): array
	{
		$chars = [];
		foreach ($pairCounts as $pair => $count) {
			foreach (str_split($pair) as $char) {
				if (!array_key_exists($char, $chars)) {
					$chars[$char] = $count;
				} else {
					$chars[$char] += $count;
				}
			}
		}

		/*
		 * Normalize the letters.
		 *
		 * All of the letters are doubled, except the first and last letters. So we increment
		 * each of those, and then divide all of the results by 2.
		 */
		$chars[$firstLetter]++;
		$chars[$lastLetter]++;
		foreach ($chars as &$count) {
			$count = $count / 2;
		}

		return $chars;
	}

	protected function getNewPairs(array &$pairCounts, array $instructions): array
	{
		$pairsThisCycle = [];
		foreach ($pairCounts as $pair => &$count) {
			$newChar = $instructions[$pair];
			$newPairs = [
				substr($pair, 0,1 ) . $newChar,
				$newChar . substr($pair, -1)
			];

			foreach ($newPairs as $newPair) {
				if (!array_key_exists($newPair, $pairsThisCycle)) {
					$pairsThisCycle[$newPair] = $count;
				} else {
					$pairsThisCycle[$newPair] += $count;
				}
			}

			$count = 0;
		}

		return $pairsThisCycle;
	}
}
