<?php
declare(strict_types=1);

namespace JPry\AdventOfCode\y2022\day09;

use Exception;
use JPry\AdventOfCode\DayPuzzle;
use JPry\AdventOfCode\MovablePoint;
use JPry\AdventOfCode\Point;
use JPry\AdventOfCode\Utils\WalkResource;

/**
 * Day Solver Class.
 *
 * @link https://adventofcode.com/2022/day/9
 */
class Solver extends DayPuzzle
{
	use WalkResource;

	/** @var array */
	protected array $map = [];

	protected ?MovablePoint $head;

	protected ?MovablePoint $tail;

	public function runTests()
	{
		$fileData = $this->getFileAsArray();
		$directions = [];
		$max = [];
		foreach ($fileData as $line) {
			[$direction, $distance] = explode(' ', $line);
			$directions[$direction] ??= 0;
			$max[$direction] ??= 0;

			$directions[$direction] += (int) $distance;
			if ($max[$direction] < $distance) {
				$max[$direction] = $distance;
			}
		}



		$data = $this->getHandleForFile('test');
		$this->part1Logic($data);
		$this->part2Logic($data);
	}

	protected function part1()
	{
		$this->part1Logic($this->getHandleForFile('input'));
	}

	protected function part2()
	{
	}

	/**
	 * @param resource $input
	 * @return void
	 */
	protected function part1Logic($input)
	{
		// We need to track the head and tail of the rope.
		$this->head = new MovablePoint(4,0);
		$this->tail = new MovablePoint(4,0);

		// We need to track where the tail has been.

		// Let's start with a 20x20 grid.
		$this->map = array_fill(
			0,
			5,
			array_fill(0, 5, 0)
		);

		// We start at 0,0, so mark that as being visited
		$this->map[4][0] = 1;

		// We need to work through each line.
		$this->walkResourceWithCallback(
			$input,
			function($line) {
				[$direction, $distance] = explode(' ', $line);
				$distance = (int) $distance;

				// See if we have enough room to move, and expand the map if necessary.
				switch ($direction) {
					case 'L':
						$difference = $this->head->column - $distance;
						if ($difference < 0) {
							// Add more columns to the left
							$this->addColumns('before', abs($difference));
						}
						break;

					case 'R':
						$difference = $this->head->column + $distance;
						if ($difference > count($this->map[0])) {
							$this->addColumns('after', $difference - count($this->map[0]));
						}
						break;

					case 'U':
						$difference = $this->head->row - $distance;
						if ($difference < 0) {
							$this->addRows('before', abs($difference));
						}
						break;

					case 'D':
						$difference = $this->head->row + $distance;
						if ($difference > count($this->map)) {
							$this->addRows('after', $difference - count($this->map));
						}
						break;
				}

				// Do the moving.
				while ($distance > 0) {
					// Move the head.
					$this->head->{$direction}(1);

					// Maybe move the tail
					$this->maybeMoveTail();
					$distance--;
				}
			}
		);

//		foreach ($this->map as $row) {
//			foreach ($row as $column) {
//				echo $column === 1 ? '#' : '.';
//			}
//			echo "\n";
//		}

		$sum = array_sum(
			array_map(
				function($array) {
					return array_sum($array);
				},
				$this->map
			)
		);

		printf("The tail visited %d locations\n", $sum);
	}

	protected function maybeMoveTail()
	{
		$rowDifference = $this->head->row - $this->tail->row;
		$columnDifference = $this->head->column - $this->tail->column;

		$rowAbs = abs($rowDifference);
		$columnAbs = abs($columnDifference);
		$needsMoved = $rowAbs > 1 || $columnAbs > 1;
		if (!$needsMoved) {
			return;
		}

		// It needs moved, now figure out which way.
		$rowMove = $this->tail->row + $rowDifference;
		if ($rowAbs > 1 || $columnDifference === 0) {
			$rowMove += $this->tail->row <=> $this->head->row;
		}

		$columnMove = $this->tail->column + $columnDifference;
		if ($columnAbs > 1 || $rowDifference === 0) {
			$columnMove += $this->tail->column <=> $this->head->column;
		}
		$this->tail->updateRow($rowMove);
		$this->tail->updateColumn($columnMove);
		$this->markVisited($this->tail);
	}

	protected function part2Logic($input)
	{

	}

	protected function addRows(string $where, int $number)
	{
		printf("Adding %d new rows %s\n", $number, $where);
		$cols = count($this->map[0]);
		$newRows = array_fill(
			0,
			$number,
			array_fill(0, $cols, 0)
		);

		switch ($where) {
			case 'before':
				array_unshift($this->map, ...$newRows);
				$this->head->updateRow($this->head->row + $number);
				$this->tail->updateRow($this->tail->row + $number);
				break;

			case 'after':
				$this->map = array_merge($this->map, $newRows);
				break;

			default:
				throw new Exception('Stop it, go to sleep');
		}
	}

	protected function addColumns(string $where, int $number)
	{
		$newColumns = array_fill(0, $number, 0);
		printf("Adding %d new columns %s\n", $number, $where);
		foreach ($this->map as &$row) {
			switch ($where) {
				case 'before':
					$row = array_merge($newColumns, $row);
					$this->head->updateColumn($this->head->column + $number);
					$this->tail->updateColumn($this->tail->column + $number);
					break;

				case 'after':
					$row = array_merge($row, $newColumns);
					break;

				default:
					throw new Exception('Stop it, go to sleep');
			}
		}
	}

	/**
	 * @param Point $point
	 * @return void
	 */
	protected function markVisited(Point $point)
	{
		$this->map[$point->row][$point->column] = 1;
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
