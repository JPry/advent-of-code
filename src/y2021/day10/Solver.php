<?php


namespace JPry\AdventOfCode\y2021\day10;

use Exception;
use JPry\AdventOfCode\DayPuzzle;

class Solver extends DayPuzzle
{
	public function runTests()
	{
		$lines = file($this->getFilePath('test'), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		$this->part1Logic($lines);
		$this->part2Logic($lines);
	}

	protected function part1()
	{
		$this->part1Logic(file($this->getFilePath(), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
	}

	protected function part2()
	{
//		$this->part2Logic(file($this->getFilePath(), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
	}

	protected function part1Logic(array $lines)
	{
		$corrupted = $completionStrings = $scores = [];
		foreach ($lines as $line) {
			try {
				$completionStrings[] = $this->getLineCompletionString($line);
			} catch (Exception $e) {
				$corrupted[] = $e->getMessage();
				continue;
			}
		}

		// Part 1
		$counts = array_count_values(array_filter($corrupted));
		$sum = 0;
		$closers = [
			')' => 3,
			']' => 57,
			'}' => 1197,
			'>' => 25137,
		];

		foreach ($counts as $character => $count) {
			$sum += $count * $closers[$character];
		}

		printf("Syntax error score is %d points!\n", $sum);

		// Part 2
		$closers = [
			')' => 1,
			']' => 2,
			'}' => 3,
			'>' => 4,
		];

		foreach ($completionStrings as $string) {
			$score = 0;
			array_map(
				function ($character) use (&$score, $closers) {
					$score = $score * 5 + $closers[$character];
				},
				str_split($string)
			);
			$scores[] = $score;
		}

		sort($scores);
		$index = intval(floor(count($scores) / 2));

		printf("Middle score for autocomplete is %d points!\n", $scores[$index]);
	}

	protected function part2Logic(array $lines)
	{
	}

	protected function getLineCompletionString(string $line): string
	{
		$openers = [
			'(' => ')',
			'[' => ']',
			'{' => '}',
			'<' => '>',
		];

		$stack = [];
		foreach (str_split($line) as $character) {
			if (array_key_exists($character, $openers)) {
				$stack[] = $character;
			} else {
				$expectedClose = array_pop($stack);
				if ($openers[$expectedClose] !== $character) {
					throw new Exception($character);
				}
			}
		}

		$return = '';
		while(0 < count($stack)) {
			$next = array_pop($stack);
			$return .= $openers[$next];
		}

		return $return;
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
