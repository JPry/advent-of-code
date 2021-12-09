<?php

namespace JPry\AdventOfCode\y2021\day05;

use LogicException;

/**
 * Class Point
 *
 * @property int x
 * @property int y
 */
class Point
{
	protected int $x;
	protected int $y;

	public function __construct(int $x, int $y)
	{
		$this->x = $x;
		$this->y = $y;
	}

	public function __isset($name)
	{
		return $name === 'x' || $name === 'y';
	}

	public function __get($name)
	{
		switch ($name) {
			case 'x':
				return $this->x;
			case 'y':
				return $this->y;

			default:
				throw new LogicException(sprintf('No such property: %s', $name));
		}
	}
}
