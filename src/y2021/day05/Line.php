<?php

namespace JPry\AdventOfCode\y2021\day05;

class Line
{
	protected Point $start;
	protected Point $end;
	protected array $points;

	public function __construct(Point $start, Point $end)
	{
		$this->start = $start;
		$this->end = $end;
	}

	/**
	 * @return Point[]
	 */
	public function getPoints(): array
	{
		if (empty($this->points)) {
			$this->generatePoints();
		}

		return $this->points;
	}

	protected function generatePoints()
	{
		$sameX = $this->start->x === $this->end->x;
		$sameY = $this->start->y === $this->end->y;

		// Check for identical points, e.g. a line with length of 1.
		if ($sameX && $sameY) {
			$this->points[] = $this->start;
			return;
		}

		if ($sameX) {
			$this->generateVerticalLine();
		} elseif ($sameY) {
			$this->generateHorizontalLine();
		} else {
			$this->generateDiagonalLine();
		}
	}

	/**
	 * The Y value stays the same.
	 */
	protected function generateHorizontalLine()
	{
		$y = $this->start->y;
		$xRange = range($this->start->x, $this->end->x);

		// Remove first and last points, as those are covered.
		array_pop($xRange);
		array_shift($xRange);

		$this->points[] = $this->start;
		foreach ($xRange as $x) {
			$this->points[] = new Point($x, $y);
		}
		$this->points[] = $this->end;
	}

	/**
	 * The X value stays the same.
	 */
	protected function generateVerticalLine()
	{
		$x = $this->start->x;
		$yRange = range($this->start->y, $this->end->y);

		// Remove first and last points, as those are covered.
		array_pop($yRange);
		array_shift($yRange);

		$this->points[] = $this->start;
		foreach ($yRange as $y) {
			$this->points[] = new Point($x, $y);
		}
		$this->points[] = $this->end;
	}

	protected function generateDiagonalLine()
	{
		printf('Found values not in horizontal or vertical lines');
	}
}
