<?php

namespace JPry\AdventOfCode\y2021\day21;

/**
 * Class Player
 *
 * @since %VERSION%
 */
class Player
{
	protected int $currentPosition;
	protected int $score;
	protected int $winThreshold;

	public function __construct(int $startingPosition, int $winThreshold = 1000)
	{
		$this->currentPosition = $startingPosition;
		$this->score = 0;
		$this->winThreshold = $winThreshold;
	}

	/**
	 * @param int[] ...$moves
	 * @return void
	 */
	public function move(...$moves): void
	{
		$move = $this->currentPosition + array_sum($moves);
		$this->currentPosition = $move % 10 ?: 10;
		$this->score += $this->currentPosition;
	}

	public function isWinner(): bool
	{
		return $this->score >= $this->winThreshold;
	}

	public function getScore(): int
	{
		return $this->score;
	}
}
