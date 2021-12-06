<?php


namespace JPry\AdventOfCode\y2021\day04;

use JPry\AdventOfCode\DayPuzzle;

class Solver extends DayPuzzle
{
	public function runTests()
	{
		$handle = $this->getFilePath('test');
		$this->part1Logic($handle);
		$this->part2Logic($handle);
	}

	protected function part1()
	{
	}

	protected function part2()
	{
	}

	protected function part1Logic($input)
	{
		[$numbers, $boards] = $this->parseInput(file_get_contents($input));

		// Process the first 5 moves before checking
		$moves = 1;

		foreach ($numbers as $number) {
			foreach ($boards as &$board) {
				$this->processNumber($number, $board);
				if ($moves >= 5 && $this->hasBingo($board)) {
					break 2;
				}
			}
			$moves++;
		}

		// We have the final board, so process the result.


	}

	protected function part2Logic($input)
	{
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
		/*
		 * A bingo needs 5 in a row or 5 in a column.
		 */

		// Check rows.
		

		// Check columns.

		return false;
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
