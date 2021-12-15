<?php

namespace JPry\AdventOfCode\y2021\day09;

use LogicException;

class Basin
{
	use MapTrait;

	protected Point $lowPoint;

	/** @var Point[] */
	protected array $allPoints = [];

	public function __construct(Point $lowPoint)
	{
		$this->lowPoint = $lowPoint;
	}

	public function mapBasin()
	{
		if (empty($this->map)) {
			throw new LogicException('Map property not set.');
		}

		$startingPoints = $this->getSurroundingPoints($this->lowPoint);


	}

	public function getBasinPointCount(): int
	{
		return count($this->allPoints);
	}

	protected function walkPoints(array &$points)
	{
		foreach ($points as $point => $_) {

		}
	}

	protected function isPointInBasin()
	{

	}

	protected function canMapPoint(string $coordinates)
	{
		$point = Point::fromString($coordinates);
		return $this->map->isValidPoint($point) && $this->map[$row][$column] < 9;
	}

	protected function getSurroundingPoints(Point $point): array
	{
		return [
			sprintf('%d,%d', $point->row - 1, $point->column) => true,
			sprintf('%d,%d', $point->row, $point->column + 1) => true,
			sprintf('%d,%d', $point->row + 1, $point->column) => true,
			sprintf('%d,%d', $point->row, $point->column - 1) => true,
		];
	}
}
