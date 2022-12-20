<?php

namespace JPry\AdventOfCode\y2022\day19;

use Exception;

/**
 * Class Cost
 *
 * @method int ore() Get the ore cost.
 * @method int clay() Get the clay cost.
 * @method int obsidian() Get the obsidian cost.
 */
class Cost
{
	use ValidTypes;

	protected array $cost;

	public function __construct(array $cost)
	{
		$this->validateTypes($cost);
		$this->cost = $cost;
	}

	public function __call(string $name, array $arguments)
	{
		$this->validateType($name);
		return $this->cost[$name] ?? 0;
	}
}
