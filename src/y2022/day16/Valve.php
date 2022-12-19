<?php

namespace JPry\AdventOfCode\y2022\day16;

/**
 * Class Valve
 *
 * @since %VERSION%
 */
class Valve
{
	protected bool $isOpen = false;


	protected int $flowRate = 0;
	protected int $minutesOpen = 0;

	public function __construct(int $flowRate)
	{
		$this->flowRate = $flowRate;
	}

	public function isOpen(): bool
	{
		return $this->isOpen;
	}

	public function open(int $minutesRemaining)
	{
		$this->minutesOpen = $minutesRemaining - 1;
		$this->isOpen = true;
	}

	public function getFlowRate(): int
	{
		return $this->flowRate;
	}

	public function getTotalPressureReleased(): int
	{
		return $this->minutesOpen * $this->flowRate;
	}
}
