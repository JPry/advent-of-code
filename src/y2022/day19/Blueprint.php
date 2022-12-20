<?php

namespace JPry\AdventOfCode\y2022\day19;

use InvalidArgumentException;

/**
 * Class Blueprint
 *
 * @since %VERSION%
 */
class Blueprint
{
	/** @var Robot[] */
	protected array $robots;

	public function __construct(array $robots)
	{
		$this->validateRobots($robots);
		$this->robots = $robots;
	}

	private function validateRobots(array $robots)
	{
		foreach ($robots as $robot) {
			if (!$robot instanceof Robot::class) {
				throw new InvalidArgumentException(sprintf('Robots must be an instance of %s', Robot::class));
			}
		}
	}
}
