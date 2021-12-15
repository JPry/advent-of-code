<?php

namespace JPry\AdventOfCode\y2021\day09;

use RuntimeException;

class Map
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

	public function isValidPoint(Point $point): bool
	{
		return $point->row <= $this->lastRowIndex
			&& $point->column <= $this->lastColumnIndex
			&& isset($this->map[$point->row][$point->column]);
	}

	public function getValue(Point $point): int
	{
		if (!$this->isValidPoint($point)) {
			throw new RuntimeException(sprintf('Point "%s" is invalid for map.', $point));
		}

		return $this->map[$point->row][$point->column];
	}
}
