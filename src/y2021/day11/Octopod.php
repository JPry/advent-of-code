<?php

namespace JPry\AdventOfCode\y2021\day11;

use JPry\AdventOfCode\Map;
use JPry\AdventOfCode\Point;

class Octopod extends Map
{
	protected int $size;

	public function __construct(array $map)
	{
		$this->size = count($map) * count($map[0]);
		parent::__construct($map);
	}

	public function energize(): void
	{
		array_walk_recursive(
			$this->map,
			function (&$value) {
				$value++;
			}
		);
	}

	public function getFlashCount(): int
	{
		$flashedThisStep = [];
		do {
			$didFlashes = false;
			foreach ($this->map as $rowIndex => $row) {
				foreach ($row as $columnIndex => $value) {
					// Each octopus flashes once per step.
					$key = sprintf('%d,%d', $rowIndex, $columnIndex);
					if (array_key_exists($key, $flashedThisStep)) {
						continue;
					}

					if ($value > 9) {
						$didFlashes = true;
						$flashedThisStep[$key] = true;
						$this->energizeAround($rowIndex, $columnIndex);
					}
				}
			}
		} while ($didFlashes);

		$this->resetEnergy();

		return count($flashedThisStep);
	}

	protected function energizeAround(int $rowIndex, int $columnIndex)
	{
		$possiblePoints = [];
		foreach (range(-1, 1) as $row) {
			$actualRow = $rowIndex + $row;
			foreach (range(-1, 1) as $column) {
				// Skip the current point
				if (0 === $row && 0 === $column) {
					continue;
				}
				$actualColumn = $columnIndex + $column;
				$newPoint = new Point($actualRow, $actualColumn);
				$possiblePoints[(string) $newPoint] = $newPoint;
			}
		}

		foreach ($possiblePoints as $index => $point) {
			if (!$this->isValidPoint($point)) {
				continue;
			}

			$this->map[$point->row][$point->column]++;
		}
	}

	protected function resetEnergy(): void
	{
		array_walk_recursive(
			$this->map,
			function (&$value) {
				if ($value > 9) {
					$value = 0;
				}
			}
		);
	}

	public function getSize(): int
	{
		return $this->size;
	}
}
