<?php

namespace JPry\AdventOfCode;

use LogicException;

/**
 * Class Point
 *
 * @property int row
 * @property int column
 */
class Point
{
	protected int $row;
	protected int $column;

	public function __construct(int $row, int $column)
	{
		$this->row = $row;
		$this->column = $column;
	}

	public static function fromString(string $point): self
	{
		[$row, $column] = array_map('intval', explode(',', $point));

		return new self($row, $column);
	}

	public function __isset($name)
	{
		return $name === 'row' || $name === 'column';
	}

	public function __get($name)
	{
		switch ($name) {
			case 'row':
				return $this->row;
			case 'column':
				return $this->column;

			default:
				throw new LogicException(sprintf('No such property: %s', $name));
		}
	}

	public function __toString(): string
	{
		return sprintf('%d,%d', $this->row, $this->column);
	}
}
