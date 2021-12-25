<?php

namespace JPry\AdventOfCode\y2021\day13;

use JPry\AdventOfCode\Map;
use JPry\AdventOfCode\Point;
use LogicException;

class Manual extends Map
{
	public function markPoint(Point $point)
	{
		$this->validatePoint($point);
		$this->map[$point->row][$point->column] = '#';
	}

	public function printDots()
	{
		echo "----------------\n";
		foreach ($this->map as $row) {
			printf("%s\n", join('', $row));
		}
		echo "----------------\n";
	}

	public function foldAlongRow(int $row)
	{
		$slice = array_reverse(array_slice($this->map, $row + 1));
		$this->map = array_slice($this->map, 0, $row);
		foreach ($slice as $row => $values) {
			foreach ($values as $column => $value) {
				if ('#' === $value) {
					$this->map[$row][$column] = $value;
				}
			}
		}
	}

	public function foldAlongColumn(int $column)
	{
		foreach ($this->map as &$values) {
			$slice = array_reverse(array_slice($values, $column + 1));
			$values = array_slice($values, 0, $column);
			foreach ($slice as $index => $value) {
				if ('#' === $value) {
					$values[$index] = $value;
				}
			}
		}
	}

	public function countDots(): int
	{
		$total = 0;
		foreach ($this->map as $row) {
			$counts = array_count_values($row);
			$total += $counts['#'] ?? 0;
		}

		return $total;
	}
}
