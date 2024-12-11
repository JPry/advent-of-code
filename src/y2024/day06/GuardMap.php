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
	protected Map $loopPositionMap;
	protected Point $startPoint;

	public function __construct(array $rawMap)
	{
		$map = [];
		$guardPositionMap = [];
		$loopPositionMap = [];
		foreach ($rawMap as $row => $line) {
			// Set up filled map with zeros.
			$guardPositionMap[] = array_fill(0, strlen($line), 0);
			$loopPositionMap[] = array_fill(0, strlen($line), 0);

			$maybePosition = strpos($line, '^');
			if ($maybePosition !== false) {
				$guardPositionMap[$row][$maybePosition] = 1;
				$this->currentPoint = new Point($row, $maybePosition);
				$this->startPoint = new Point($row, $maybePosition);
			}

			$map[] = str_split($line);
		}

		parent::__construct($map);

		if (!isset($this->currentPoint)) {
			throw new RuntimeException('Guard position not found in map.');
		}

		$this->guardPositionMap = new Map($guardPositionMap);
		$this->loopPositionMap = new Map($loopPositionMap);
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

	public function sumLoopPosition(): int
	{
		return array_reduce(
			$this->loopPositionMap->map,
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

		// Check for a loop point.
//		$this->checkForLoopPoint($nextPoint);

		$this->setValue(
			$nextPoint,
			match ($this->currentDirection) {
				'north' => '^',
				'east' => '>',
				'south' => 'v',
				'west' => '<',
			}
		);

		// If the next point is not an obstacle, update the guard's position.
		$this->guardPositionMap->setValue($nextPoint, 1);
		$this->currentPoint = $nextPoint;
	}

	protected function changeDirection(): void
	{
		$this->currentDirection = $this->getNextDirection($this->currentDirection);
	}

	protected function getNextDirection(string $currentDirection): string
	{
		return match ($currentDirection) {
			'north' => 'east',
			'east' => 'south',
			'south' => 'west',
			'west' => 'north',
		};
	}

	protected function checkForLoopPoint(Point $nextPoint)
	{
		static $doLoopPoint = false;
		if ((string) $this->startPoint !== (string) $nextPoint) {
			return;
		}

		if ($doLoopPoint) {
			$this->loopPositionMap->setValue($nextPoint, 1);
			$doLoopPoint = false;

			// Now we need to see if another loop point is possible.
			$currentPoint = clone $this->currentPoint;
			$direction = $this->getNextDirection($this->currentDirection);

			while (false) {
				try {
					$nextPoint = $currentPoint->{$direction}();
					if (!empty($this->loopPositionMap->getValue($nextPoint))) {
						break;
					}
				} catch (Exception $e) {
					break;
				}
			}
		}

		// If we've been to the next point before, we can do a loop point on the next round.
		if (1 === $this->guardPositionMap->getValue($nextPoint)) {
			$doLoopPoint = true;
		}
	}

	public function getMapAsString(): string
	{
		return array_reduce(
			$this->map,
			static function (string $carry, array $row): string {
				return $carry . implode('', $row) . PHP_EOL;
			},
			''
		);
	}
}
