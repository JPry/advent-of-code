<?php


namespace JPry\AdventOfCode\y2021\day04;

use JPry\AdventOfCode\DayPuzzle;
use JPry\AdventOfCode\Input;

class Solver extends DayPuzzle
{
	protected $toCheck = [];

	public function __construct(?Input $input = null)
	{
		parent::__construct($input);

		// Generate row and column arrays.
		$values = array_fill(0, 5, 1);
		$rowStart = 1;
		for ($index = 1; $index < 6; $index++) {
			// Add row to the toCheck array.
			$this->toCheck[] = array_combine(range($rowStart, $rowStart + 4), $values);

			// Add column to the toCheck array.
			$this->toCheck[] = array_combine(range($index, 25, 5), $values);
			$rowStart += 5;
		}
	}

	public function runTests()
	{
		$handle = $this->getFilePath('test');
		$this->part1Logic($handle);
		$this->part2Logic($handle);
	}

	protected function part1()
	{
		$this->part1Logic($this->getFilePath('input'));
	}

	protected function part2()
	{
		$this->part2Logic($this->getFilePath('input'));
	}

	protected function part1Logic($input)
	{
		[$numbers, $boards] = $this->parseInput(file_get_contents($input));

		// Process the first 5 moves before checking
		$moves = 1;

		$foundBingo = false;
		foreach ($numbers as $number) {
			foreach ($boards as $index => &$board) {
				$this->processNumber($number, $board);
				if ($moves >= 5 && $this->hasBingo($board)) {
					$foundBingo = true;
					break 2;
				}
			}
			$moves++;
		}
		// We have the final board, so process the result.

		if ($foundBingo) {
			/** @noinspection PhpUndefinedVariableInspection */
			printf(
				"Bingo found for board %d.\nCalculated score is: %d\n",
				$index + 1,
				$this->calculateScore($board, $number)
			);
		} else {
			echo "Uh-oh, there was no bingo found.\n";
		}
	}

	protected function part2Logic($input)
	{
		[$numbers, $boards] = $this->parseInput(file_get_contents($input));

		$foundLastBingo = false;
		$numberIndex = 0;
		do {
			$currentNumber = $numbers[$numberIndex];
			foreach ($boards as $index => &$board) {
				$this->processNumber($currentNumber, $board);

				// Remove each board as it achieves a bingo.
				if ($numberIndex >= 4 && $this->hasBingo($board)) {
					if (count($boards) > 1) {
						unset($boards[$index]);
					} else {
						$foundLastBingo = true;
					}
				}
			}
			$numberIndex++;
		} while (!$foundLastBingo);

		/** @noinspection PhpUndefinedVariableInspection */
		printf(
			"Bingo found for last board.\nCalculated score is: %d\n",
			$this->calculateScore($board, $currentNumber)
		);
	}

	protected function parseInput(string $input): array
	{
		$rawBoards = explode("\n\n", $input);
		$numbers = explode(',', array_shift($rawBoards));
		$boards = [];
		$range = range(1, 25);

		foreach ($rawBoards as $rawBoard) {
			$places = array_map(
				function ($number) {
					return trim($number);
				},
				preg_split('#\s+#', $rawBoard, 0, PREG_SPLIT_NO_EMPTY)
			);

			$board = array_combine($places, $range);
			$answers = array_fill(1, 25, 0);

			$boards[] = [
				'board' => $board,
				'answers' => $answers,
			];
		}

		return [$numbers, $boards];
	}

	protected function processNumber(string $number, array &$board)
	{
		if (array_key_exists($number, $board['board'])) {
			$board['answers'][$board['board'][$number]] = 1;
		}
	}

	protected function hasBingo(array $board): bool
	{
		// Check rows and columns for bingo.
		foreach ($this->toCheck as $row ) {
			$found = array_sum(array_intersect_key($board['answers'], $row));
			if (5 === $found) {
				return true;
			}
		}

		return false;
	}

	protected function calculateScore(array $board, string $lastMove): int
	{
		$coveredSquares = array_keys(array_filter($board['answers']));
		$unmarkedNumbers = array_diff($board['board'], $coveredSquares);

		return array_sum(array_keys($unmarkedNumbers)) * intval($lastMove);
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
