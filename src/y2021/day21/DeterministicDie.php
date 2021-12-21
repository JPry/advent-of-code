<?php

namespace JPry\AdventOfCode\y2021\day21;

/**
 * Class DeterministicDie
 *
 * @since %VERSION%
 */
class DeterministicDie implements RollableDie
{
	protected $rollCount = 0;

	/**
	 * @param int $howMany
	 * @return int[]
	 */
	public function getRolls(int $howMany = 3): array
	{
		$return = [];
		for ($i = 1; $i <= $howMany; $i++) {
			$return[] = $this->getRoll();
		}

		return $return;
	}

	public function getRoll(): int
	{
		$this->rollCount++;
		return $this->rollCount % 100 ?: 100;
	}

	public function getRollCount(): int
	{
		return $this->rollCount;
	}
}
