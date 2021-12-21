<?php


namespace JPry\AdventOfCode\y2021\day21;

use JPry\AdventOfCode\y2021\day21\DeterministicDie as DDie;
use JPry\AdventOfCode\DayPuzzle;

class Solver extends DayPuzzle
{
	public function runTests()
	{
		$data = $this->getFileAsArray('test');
		$this->part1Logic($data);
		$this->part2Logic($data);
	}

	protected function part1()
	{
		$this->part1Logic($this->getFileAsArray(), new DDie(), 1000);
	}

	protected function part2()
	{
	}

	protected function part1Logic(array $input, RollableDie $die, int $winningThreshold)
	{
		$p1 = new Player(intval(substr($input[0], -1)), $winningThreshold);
		$p2 = new Player(intval(substr($input[1], -1)), $winningThreshold);

		do {
			// Player 1 move and score check.
			$p1->move(...$die->getRolls());
			if ($p1->isWinner()) {
				$loser = 'p2';
				break;
			}

			// Player 2 move and score check.
			$p2->move(...$die->getRolls());
			if ($p2->isWinner()) {
				$loser = 'p1';
				break;
			}

		} while (true);


		printf("Player 1 score: %d\n", $p1->getScore());
		printf("Player 2 score: %d\n", $p2->getScore());
		printf(
			"Number of rolls (%d) x loser score (%d): %d\n",
			$die->getRollCount(),
			${$loser}->getScore(),
			$die->getRollCount() * ${$loser}->getScore()
		);
	}

	protected function part2Logic($input)
	{

	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
