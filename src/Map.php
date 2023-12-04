<?php

namespace JPry\AdventOfCode;

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

	public function getValue(Point $point)
	{
		if (!$this->isValidPoint($point)) {
			throw new RuntimeException(sprintf('Point "%s" is invalid for map.', $point));
		}

		return $this->map[$point->row][$point->column];
	}

	protected function validatePoint(Point $point)
	{
		if (!$this->isValidPoint($point)) {
			throw new RuntimeException(sprintf('Point at location "%s" is invalid', $point));
		}
	}

	public function getRowCount(): int
	{
		return $this->lastRowIndex + 1;
	}

	public function getColumnCount(): int
	{
		return $this->lastColumnIndex + 1;
	}

	public function getLastRowIndex(): int
	{
		return $this->lastRowIndex;
	}

	public function getLastColumnIndex(): int
	{
		return $this->lastColumnIndex;
	}

	public function walkMap(callable $callback)
	{
		foreach ($this->map as $rowIndex => $row) {
			foreach ($row as $columnIndex => $value) {
				$callback($value, $rowIndex, $columnIndex, $this);
			}
		}
	}

	/**
	 * @param Point $point
	 * @return array<Point>
	 */
	public function getPointsAround(Point $point): array
	{
		$points = [];
		$points[] = new Point($point->row - 1, $point->column - 1);
		$points[] = new Point($point->row - 1, $point->column);
		$points[] = new Point($point->row - 1, $point->column + 1);
		$points[] = new Point($point->row, $point->column - 1);
		$points[] = new Point($point->row, $point->column + 1);
		$points[] = new Point($point->row + 1, $point->column - 1);
		$points[] = new Point($point->row + 1, $point->column);
		$points[] = new Point($point->row + 1, $point->column + 1);

		return array_filter(
			$points,
			function (Point $point) {
				return $this->isValidPoint($point);
			}
		);
	}
}
