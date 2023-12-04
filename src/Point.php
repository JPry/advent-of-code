<?php

namespace JPry\AdventOfCode;

use LogicException;

/**
 * Class Point
 *
 * @property int row
 * @property int column
 * @method Point north()
 * @method Point south()
 * @method Point east()
 * @method Point west()
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
		return new self(...array_map('intval', explode(',', $point)));
	}

	public static function fromReversedString(string $point): self
	{
		[$column, $row] = array_map('intval', explode(',', $point));

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

	public function __call($name, $arguments)
	{
		switch($name) {
			case 'north':
				return new static($this->row - 1, $this->column);

			case 'south':
				return new static($this->row + 1, $this->column);

			case 'east':
				return new static($this->row, $this->column + 1);

			case 'west':
				return new static($this->row, $this->column - 1);

			default:
				throw new LogicException(sprintf('No such method: %s', $name));
		}
	}
}
