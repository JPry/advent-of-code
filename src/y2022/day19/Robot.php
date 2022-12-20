<?php

namespace JPry\AdventOfCode\y2022\day19;

/**
 * Class Robot
 *
 * @since %VERSION%
 */
class Robot
{
	use ValidTypes;

	protected string $type;

	protected Cost $cost;

	/**
	 * @param string $type
	 * @param Cost $cost
	 */
	public function __construct(string $type, Cost $cost)
	{
		$this->validTypes['geode'] = 1;
		$this->validateType($type);
		$this->type = $type;
		$this->cost = $cost;
	}
}
