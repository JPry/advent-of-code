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
		$this->part1Logic($this->getHandleForFile());
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
		$this->head = new MovablePoint(24, 24);
		$this->tail = new MovablePoint(24, 24);

		// We need to track where the tail has been.

		// Let's start with a 20x20 grid.
		$this->map = array_fill(
			0,
			50,
			array_fill(0, 50, 0)
		);

		// We start at 4,0, so mark that as being visited
		$this->map[24][24] = 1;

		$lineNumber = 0;

		// We need to work through each line.
		$this->walkResourceWithCallback(
			$input,
			function ($line) use (&$lineNumber) {
				$lineNumber++;
				[$direction, $distance] = explode(' ', $line);
				$distance = (int) $distance;

				// See if we have enough room to move, and expand the map if necessary.
				$this->maybeAdjustMap($direction, $distance);

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
				function ($array) {
					return array_sum($array);
				},
				$this->map
			)
		);

		printf("The tail visited %d locations\n", $sum);
	}

	protected function maybeMoveTail()
	{
		$this->maybeMoveTailHorizontally();
		$this->maybeMoveTailVertically();
		$this->maybeMoveTailDiagonally();
		$this->markVisited($this->tail);
	}

	protected function maybeMoveTailHorizontally()
	{
		// If not in the same row, skip.
		if ($this->head->row !== $this->tail->row) {
			return;
		}

		// If the distance is less than 2 in either direction, no move needed.
		// This also covers when they're in the same spot.
		if (abs($this->head->column - $this->tail->column) < 2) {
			return;
		}

		$this->tail->moveColumn($this->head->column <=> $this->tail->column);
	}

	protected function maybeMoveTailVertically()
	{
		// If not in the same column, skip.
		if ($this->head->column !== $this->tail->column) {
			return;
		}

		// If the distance is less than 2 in either direction, no move needed.
		// This also covers when they're in the same spot.
		if (abs($this->head->row - $this->tail->row) < 2) {
			return;
		}

		$this->tail->moveRow($this->head->row <=> $this->tail->row);
	}

	protected function maybeMoveTailDiagonally()
	{
		$h = &$this->head;
		$t = &$this->tail;

		// If this is the same row or column, skip.
		if ($h->row === $t->row || $h->column === $t->column) {
			return;
		}

		/*
		 * Move up/right
		 *
		 * Scenarios:
		 * - Tail is one column behind, 2 rows ahead (head moving up)
		 * - Tail is one row ahead, 2 columns behind (head moving right)
		 */
		if (
			($t->row - $h->row === 2 && $h->column - $t->column === 1)
			|| ($t->row - $h->row === 1 && $h->column - $t->column === 2)
		) {
			$t->moveRow(-1);
			$t->moveColumn(1);
		}

		/*
		 * Move up/left
		 *
		 * Scenarios:
		 * - Tail is one column ahead, 2 rows ahead (head moving up)
		 * - Tail is one row ahead, 2 columns ahead (head moving left)
		 */
		if (
			($t->row - $h->row === 2 && $t->column - $h->column === 1)
			|| ($t->row - $h->row === 1 && $t->column - $h->column === 2)
		) {
			$t->moveRow(-1);
			$t->moveColumn(-1);
		}

		/*
		 * Move down/right
		 *
		 * Scenarios:
		 * - Tail is one column behind, 2 rows behind (head moving down)
		 * - Tail is two columns behind, 1 row behind (head moving right)
		 */
		if (
			($h->row - $t->row === 2 && $h->column - $t->column === 1)
			|| ($h->row - $t->row === 1 && $h->column - $t->column === 2)
		) {
			$t->moveRow(1);
			$t->moveColumn(1);
		}

		/*
		 * Move down/left
		 *
		 * Scenarios:
		 * - Tail is one column ahead, 2 rows behind (head moving down)
		 * - Tail is two columns ahead, 1 row behind (head moving left)
		 */
		if (
			($h->row - $t->row === 2 && $t->column - $h->column === 1)
			|| ($h->row - $t->row === 1 && $t->column - $h->column === 2)
		) {
			$t->moveRow(1);
			$t->moveColumn(-1);
		}
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
				$this->head->moveRow($number);
				$this->tail->moveRow($number);
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
					break;

				case 'after':
					$row = array_merge($row, $newColumns);
					break;

				default:
					throw new Exception('Stop it, go to sleep');
			}
		}

		if ('before' === $where) {
			$this->head->moveColumn($number);
			$this->tail->moveColumn($number);
		}
	}

	/**
	 * @param int $distance
	 * @return void
	 * @throws Exception
	 */
	protected function maybeAddColumnsBefore(int $distance)
	{
		$difference = $this->head->column - $distance;
		if ($difference < 0) {
			// Add more columns to the left
			$this->addColumns('before', abs($difference));
		}
	}

	/**
	 * @param int $distance
	 * @return void
	 * @throws Exception
	 */
	protected function maybeAddColumnsAfter(int $distance)
	{
		$difference = $this->head->column + $distance;
		if ($difference > count($this->map[0])) {
			$this->addColumns('after', $difference - count($this->map[0]));
		}
	}

	/**
	 * @param int $distance
	 * @return void
	 * @throws Exception
	 */
	function maybeAddRowsBefore(int $distance): void
	{
		$difference = $this->head->row - $distance;
		if ($difference < 0) {
			$this->addRows('before', abs($difference));
		}
	}

	/**
	 * @param int $distance
	 * @return void
	 * @throws Exception
	 */
	function maybeAddRowsAfter(int $distance): void
	{
		$difference = $this->head->row + $distance;
		$lastIndex = count($this->map) - 1;
		if ($difference > $lastIndex) {
			$this->addRows('after', $difference - $lastIndex);
		}
	}

	/**
	 * @param $direction
	 * @param int $distance
	 * @return void
	 * @throws Exception
	 */
	function maybeAdjustMap($direction, int $distance): void
	{
		switch ($direction) {
			case 'L':
				$this->maybeAddColumnsBefore($distance);
				break;

			case 'R':
				$this->maybeAddColumnsAfter($distance);
				break;

			case 'U':
				$this->maybeAddRowsBefore($distance);
				break;

			case 'D':
				$this->maybeAddRowsAfter($distance);
				break;
		}
	}

	/**
	 * @param Point $point
	 * @return void
	 * @throws Exception
	 */
	protected function markVisited(Point $point)
	{
		if (!array_key_exists($point->row, $this->map)) {
			throw new Exception("That place doesn't exist...");
		}
		$this->map[$point->row][$point->column] = 1;
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
