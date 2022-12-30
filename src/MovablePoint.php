<?php

namespace JPry\AdventOfCode;

use RuntimeException;

/**
 * @method void L(int $distance)
 * @method void R(int $distance)
 * @method void U(int $distance)
 * @method void D(int $distance)
 */
class MovablePoint extends Point
{

	/**
	 * is triggered when invoking inaccessible methods in an object context.
	 *
	 * @param string $name
	 * @param array $arguments
	 * @return void
	 */
	public function __call(string $name, array $arguments)
	{
		$distance = (int) $arguments[0];
		switch ($name) {
			case 'L':
				$this->column -= $distance;
				break;

			case 'R':
				$this->column += $distance;
				break;

			case 'U':
				$this->row -= $distance;
				break;

			case 'D':
				$this->row += $distance;
				break;

			default:
				throw new RuntimeException('Invalid direction');
		}
	}

	public function moveRow(int $distance)
	{
		$this->row += $distance;
	}

	public function moveColumn(int $distance)
	{
		$this->column += $distance;
	}

	public function updateRow(int $newRow)
	{
		$this->row = $newRow;
	}

	public function updateColumn(int $newColumn)
	{
		$this->column = $newColumn;
	}
}
