<?php
declare(strict_types=1);

namespace JPry\AdventOfCode\y2024\day10;

use JPry\AdventOfCode\DayPuzzle;
use JPry\AdventOfCode\Map;
use JPry\AdventOfCode\Point;

/**
 * Day Solver Class.
 *
 * @link https://adventofcode.com/2024/day/10
 */
class Solver extends DayPuzzle
{
	protected array $found = [];
	protected bool $useFound = false;

	public function runTests()
	{
		$data = $this->getFileAsArray('test');
		$this->part1Logic($data);
		$this->part2Logic($data);
	}

	protected function part1()
	{
		return $this->part1Logic($this->getFileAsArray());
	}

	protected function part2()
	{
		return $this->part2Logic($this->getFileAsArray());
	}

	protected function part1Logic($input)
	{
		$this->useFound = true;
		$map = $this->setupMap($input);
		$trailheads = $this->getTrailheads($map);

		$value = 0;
		foreach ($trailheads as $trailhead) {
			$carry = 0;
			$this->found = [];
			$this->processPoint($trailhead, $map, $carry);
			$value += $carry;
		}

		return $value;
	}

	protected function processPoint(Point $point, Map &$map, int &$carry): void
	{
		// Ensure we don't find the same point more than once.
		if ($this->useFound && array_key_exists((string) $point, $this->found)) {
			return;
		}

		$pointValue = $map->getValue($point);
		if (9 === $pointValue) {
			$carry++;
			$this->found[(string) $point] = true;
			return;
		}

		$nearbyPoints = $map->getOrthogonalPointsAround($point);
		foreach ($nearbyPoints as $nearbyPoint) {
			// If it's not the next higher number, then no need to process it.
			if ($map->getValue($nearbyPoint) !== $pointValue + 1) {
				continue;
			}

			$this->processPoint($nearbyPoint, $map, $carry);
		}
	}

	protected function part2Logic($input)
	{
		$this->useFound = false;
		$map = $this->setupMap($input);
		$trailheads = $this->getTrailheads($map);

		$value = 0;
		foreach ($trailheads as $trailhead) {
			$carry = 0;
			$this->processPoint($trailhead, $map, $carry);
			$value += $carry;
		}

		return $value;
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}

	/**
	 * @param $input
	 * @return Map
	 */
	protected function setupMap($input): Map
	{
		$mapped = [];
		foreach ($input as $line) {
			$mapped[] = array_map(
				function ($item) {
					return is_numeric($item) ? (int) $item : -1;
				},
				str_split($line)
			);
		}

		$map = new Map($mapped);
		return $map;
	}

	/**
	 * @param Map $map
	 * @return array
	 */
	protected function getTrailheads(Map $map): array
	{
		$trailheads = [];
		$map->walkMap(function ($value, $row, $column) use (&$trailheads) {
			if (0 === $value) {
				$trailheads[] = new Point($row, $column);
			}
		});
		return $trailheads;
	}
}
