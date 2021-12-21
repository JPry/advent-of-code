<?php

namespace JPry\AdventOfCode\y2021\day21;


/**
 * Class DeterministicDie
 *
 * @since %VERSION%
 */
interface RollableDie
{
	/**
	 * @param int $howMany
	 * @return int[]
	 */
	public function getRolls(int $howMany = 3): array;

	public function getRoll(): int;

	public function getRollCount(): int;
}
