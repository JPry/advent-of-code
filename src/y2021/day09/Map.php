<?php

namespace JPry\AdventOfCode\y2021\day09;

use ArrayAccess;

class Map implements ArrayAccess
{
	protected array $map;
	protected int $lastRowIndex;
	protected int $lastColumnIndex;

	public function __construct(array $map)
	{
		$this->map = $map;
		$this->lastRowIndex = count($map) - 1;
		$this->lastColumnIndex = count($map[0]) - 1;
	}

	public function offsetExists($offset): bool
	{
		return array_key_exists($offset, $this->map);
	}

	public function offsetGet($offset)
	{
		return $this->map[$offset];
	}

	public function offsetSet($offset, $value)
	{
		// No need to set in this implementation.
	}

	public function offsetUnset($offset)
	{
		// No need to unset in this implementation.
	}

	public function isValidPoint(Point $point)
	{
		return isset($this->map[$point->row][$point->column]);
	}
}
