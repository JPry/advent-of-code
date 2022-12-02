<?php

declare(strict_types=1);

namespace JPry\AdventOfCode\y2022\day02;

use JPry\AdventOfCode\DayPuzzle;
use JPry\AdventOfCode\Utils\WalkResource;

class Solver extends DayPuzzle
{
	use WalkResource;

	/**
	 * rock: 1
	 * paper: 2
	 * scissors: 3
	 *
	 * lose: 0
	 * draw: 3
	 * win: 6
	 * @var int[][]
	 */
	protected $scoreMap = [
		'A X' => ['opponent' => 1+3, 'player' => 1+3],
		'A Y' => ['opponent' => 1+0, 'player' => 2+6],
		'A Z' => ['opponent' => 1+6, 'player' => 3+0],
		'B X' => ['opponent' => 2+6, 'player' => 1+0],
		'B Y' => ['opponent' => 2+3, 'player' => 2+3],
		'B Z' => ['opponent' => 2+0, 'player' => 3+6],
		'C X' => ['opponent' => 3+0, 'player' => 1+6],
		'C Y' => ['opponent' => 3+6, 'player' => 2+0],
		'C Z' => ['opponent' => 3+3, 'player' => 3+3],
	];

	/**
	 * X is lose
	 * Y is draw
	 * Z is win
	 * @var \int[][]
	 */
	protected $correctScoreMap = [
		'A X' => 3+0, // rock, lose
		'A Y' => 1+3, // rock, draw
		'A Z' => 2+6, // rock, win

		'B X' => 1+0,
		'B Y' => 2+3,
		'B Z' => 3+6,

		'C X' => 2+0,
		'C Y' => 3+3,
		'C Z' => 1+6,
	];

	public function runTests()
	{
		$this->part1Logic($this->getHandleForFile('test'));
		$this->part2Logic($this->getHandleForFile('test'));
	}

	protected function part1()
	{
		$this->part1Logic($this->getHandleForFile('input'));
	}

	protected function part2()
	{
		$this->part2Logic($this->getHandleForFile('input'));
	}

	/**
	 * @param resource $input
	 * @return void
	 */
	protected function part1Logic($input)
	{
		$playerScore = 0;
		$this->walkResourceWithCallback(
			$input,
			function($line) use (&$playerScore) {
				$playerScore += $this->scoreMap[$line]['player'];
			}
		);

		printf("The player's score was: %d\n", $playerScore);
	}

	/**
	 * @param resource $input
	 * @return void
	 */
	protected function part2Logic($input)
	{
		$playerScore = 0;
		$this->walkResourceWithCallback(
			$input,
			function($line) use (&$playerScore) {
				$playerScore += $this->correctScoreMap[$line];
			}
		);

		printf("The correct player's score was: %d\n", $playerScore);
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
