<?php

namespace JPry\AdventOfCode\y2021\day09;

use LogicException;
use RuntimeException;

class Basin
{
	use MapTrait;

	protected Point $lowPoint;

	/** @var Point[] */
	protected array $allPoints = [];

	protected array $checkedPoints = [];

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
			$this->allPoints[(string) $this->lowPoint] = $this->lowPoint;
			$this->checkedPoints[(string) $this->lowPoint] = true;
			$this->walkPoints(
				$this->getSurroundingPoints($this->lowPoint),
				$this->map->getValue($this->lowPoint)
			);
		}
	}

	public function getBasinPointCount(): int
	{
		return count($this->allPoints);
	}

	/**
	 * @param Point[] $surroundingPoints
	 * @param int $currentValue
	 * @return void
	 */
	protected function walkPoints(array $surroundingPoints, int $currentValue): void
	{
		foreach ($surroundingPoints as $coordinate => $point) {
			if (array_key_exists((string) $point, $this->checkedPoints)) {
				continue;
			}

			try {
				$value = $this->map->getValue($point);
			} catch (RuntimeException $e) {
				$this->checkedPoints[(string) $point] = true;
				continue;
			}

			if (9 === $value) {
				continue;
			}

			if ($value > $currentValue) {
				$this->allPoints[$coordinate] = $point;
				$this->walkPoints($this->getSurroundingPoints($point), $value);
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
