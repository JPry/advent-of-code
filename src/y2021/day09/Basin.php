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

		if (empty($this->allPoints)) {
			$this->allPoints[] = $this->lowPoint;
			$startingPoints = $this->getSurroundingPoints($this->lowPoint);
			$currentValue = $this->map->getValue($this->lowPoint);
			$this->walkPoints($startingPoints, $currentValue);
		}
	}

	public function getBasinPointCount(): int
	{
		return count($this->allPoints);
	}

	/**
	 * @param Point[] $checkPoints
	 * @param int $currentValue
	 * @return void
	 */
	protected function walkPoints(array &$checkPoints, int $currentValue)
	{
		foreach ($checkPoints as $coordinate => $point) {
			if (!$this->map->isValidPoint($point)) {
				continue;
			}

			$value = $this->map->getValue($point);
			if (9 === $value) {
				continue;
			}
		}
	}

	protected function getSurroundingPoints(Point $point): array
	{
		$northPoint = new Point($point->row - 1, $point->column);
		$eastPoint = new Point($point->row, $point->column + 1);
		$southPoint = new Point($point->row + 1, $point->column);
		$westPoint = new Point($point->row, $point->column - 1);

		return [
			(string)$northPoint => $northPoint,
			(string)$eastPoint => $eastPoint,
			(string)$southPoint => $southPoint,
			(string)$westPoint => $westPoint,
		];
	}
}
