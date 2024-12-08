<?php

namespace JPry\AdventOfCode\y2024\day06;

use Exception;
use JPry\AdventOfCode\Map;
use JPry\AdventOfCode\Point;
use RuntimeException;

class GuardMap extends Map
{

	protected string $currentDirection = 'north';
	protected Point $currentPoint;
	protected Map $guardPositionMap;

	public function __construct(array $rawMap)
	{
		$map = [];
		$guardPositionMap = [];
		foreach ($rawMap as $row => $line) {
			// Set up filled map with zeros.
			$guardPositionMap[] = array_fill(0, strlen($line), 0);

			$maybePosition = strpos($line, '^');
			if ($maybePosition !== false) {
				$guardPositionMap[$row][$maybePosition] = 1;
				$this->currentPoint = new Point($row, $maybePosition);
			}

			$map[] = str_split($line);
		}

		parent::__construct($map);

		if (!isset($this->currentPoint)) {
			throw new RuntimeException('Guard position not found in map.');
		}

		$this->guardPositionMap = new Map($guardPositionMap);
	}

	public function sumGuardPositions(): int
	{
		return array_reduce(
			$this->guardPositionMap->map,
			static function (int $carry, array $row): int {
				return $carry + array_sum($row);
			},
			0
		);
	}

	/**
	 * @return void
	 * @throws Exception When the guard moves off the map.
	 */
	public function moveGuard(): void
	{
		// Determine the next point and whether it is valid.
		$nextPoint = $this->currentPoint->{$this->currentDirection}();
		if (!$this->guardPositionMap->isValidPoint($nextPoint)) {
			throw new Exception('Guard moved off the map.');
		}

		// If the next point is an obstacle, change direction only.
		if ('#' === $this->getValue($nextPoint)) {
			$this->changeDirection();
			return;
		}

		// If the next point is not an obstacle, update the guard's position.
		$this->guardPositionMap->setValue($nextPoint, 1);
		$this->currentPoint = $nextPoint;
	}

	protected function changeDirection(): void
	{
		match ($this->currentDirection) {
			'north' => $this->currentDirection = 'east',
			'east' => $this->currentDirection = 'south',
			'south' => $this->currentDirection = 'west',
			'west' => $this->currentDirection = 'north',
		};
	}
}
