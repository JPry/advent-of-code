<?php

namespace JPry\AdventOfCode\y2021\day11;

use JPry\AdventOfCode\Map;

class Octopod extends Map
{

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
}
