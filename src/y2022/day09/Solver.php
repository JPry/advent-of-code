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

	protected ?MovablePoint $start;

	/** @var MovablePoint[] */
	protected array $knots = [];

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

		$this->part1Logic($this->getHandleForFile('test'));
		$this->part1Logic($this->getHandleForFile('test'), 10);
	}

	protected function part1()
	{
		$this->part1Logic($this->getHandleForFile());
	}

	protected function part2()
	{
		$this->part1Logic($this->getHandleForFile(), 10);
	}

	/**
	 * @param resource $input
	 * @param int $totalKnots
	 * @return void
	 * @throws Exception
	 */
	protected function part1Logic($input, int $totalKnots = 2)
	{
		// Set the starting point.
		$this->start = new MovablePoint(20, 20);

		// We need to track the head and tail of the rope.
		$this->head = clone $this->start;
		$this->tail = clone $this->start;

		// Build the array of knots
		$this->knots = [];
		for ($i = 2; $i < $totalKnots; $i++) {
			$this->knots[] = clone $this->start;
		}
		$this->knots[] = $this->tail;

		// Let's start with a 20x20 grid.
		$this->map = array_fill(
			0,
			50,
			array_fill(0, 50, 0)
		);

		// Mark the start as being visited.
		$this->markVisited($this->start);

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
					$this->maybeMoveKnots();
					$distance--;
				}
			}
		);

		// Render at the end
		$this->writePositionsToFile($totalKnots);

		// Add up all the places the tail visited.
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

	protected function maybeMoveKnots()
	{
		$previousKnot = $this->head;
		foreach ($this->knots as $knot) {
			$this->maybeMoveKnotHorizontally($knot, $previousKnot);
			$this->maybeMoveKnotVertically($knot, $previousKnot);
			$this->maybeMoveKnotDiagonally($knot, $previousKnot);
			$previousKnot = $knot;
		}

		$this->markVisited($this->tail);
	}

	protected function maybeMoveKnotHorizontally(MovablePoint $knotToMove, MovablePoint $knotToFollow)
	{
		// If not in the same row, skip.
		if ($knotToFollow->row !== $knotToMove->row) {
			return;
		}

		// If the distance is less than 2 in either direction, no move needed.
		// This also covers when they're in the same spot.
		if (abs($knotToFollow->column - $knotToMove->column) < 2) {
			return;
		}

		$knotToMove->moveColumn($knotToFollow->column <=> $knotToMove->column);
	}

	protected function maybeMoveKnotVertically(MovablePoint $knotToMove, MovablePoint $knotToFollow)
	{
		// If not in the same column, skip.
		if ($knotToFollow->column !== $knotToMove->column) {
			return;
		}

		// If the distance is less than 2 in either direction, no move needed.
		// This also covers when they're in the same spot.
		if (abs($knotToFollow->row - $knotToMove->row) < 2) {
			return;
		}

		$knotToMove->moveRow($knotToFollow->row <=> $knotToMove->row);
	}

	protected function maybeMoveKnotDiagonally(MovablePoint $knotToMove, MovablePoint $knotToFollow)
	{
		// If this is the same row or column, skip.
		if ($knotToFollow->row === $knotToMove->row || $knotToFollow->column === $knotToMove->column) {
			return;
		}

		$toMoveColumnDifference = $knotToFollow->column - $knotToMove->column;
		$toMoveRowDifference = $knotToFollow->row - $knotToMove->row;

		// Primary moving directions.
		$movingUp = -2 === $toMoveRowDifference;
		$movingDown = 2 === $toMoveRowDifference;
		$movingRight = 2 === $toMoveColumnDifference;
		$movingLeft = -2 === $toMoveColumnDifference;

		// Row and columns offsets.
		$columnBehind = $toMoveColumnDifference === 1;
		$columnAhead = $toMoveColumnDifference === -1;
		$rowBehind = $toMoveRowDifference === 1;
		$rowAhead = $toMoveRowDifference === -1;

		/*
		 * Move up/right
		 *
		 * Scenarios:
		 * - Tail is 1 column behind, 2 rows ahead (head moving up)
		 * - Tail is 2 columns behind, 2 rows ahead (head moving up/right)
		 * - Tail is 2 columns behind, 1 row ahead (head moving right)
		 */
		if (
			($columnBehind && $movingUp) ||
			($movingUp && $movingRight) ||
			($movingRight && $rowAhead)
		) {
			$knotToMove->moveRow(-1);
			$knotToMove->moveColumn(1);
			return;
		}

		/*
		 * Move up/left
		 *
		 * Scenarios:
		 * - Tail is 1 column ahead, 2 rows ahead (head moving up)
		 * - Tail is 2 columns ahead, 2 rows ahead (head moving up/left)
		 * - Tail is 2 columns ahead, 1 row ahead (head moving left)
		 */
		if (
			($columnAhead && $movingUp) ||
			($movingUp && $movingLeft) ||
			($movingLeft && $rowAhead)
		) {
			$knotToMove->moveRow(-1);
			$knotToMove->moveColumn(-1);
			return;
		}

		/*
		 * Move down/right
		 *
		 * Scenarios:
		 * - Tail is one column behind, 2 rows behind (head moving down)
		 * - Tail is two columns behind, 1 row behind (head moving right)
		 */
		if (
			($columnBehind && $movingDown) ||
			($movingDown && $movingRight) ||
			($movingRight && $rowBehind)
		) {
			$knotToMove->moveRow(1);
			$knotToMove->moveColumn(1);
			return;
		}

		/*
		 * Move down/left
		 *
		 * Scenarios:
		 * - Tail is one column ahead, 2 rows behind (head moving down)
		 * - Tail is two columns ahead, 1 row behind (head moving left)
		 */
		if (
			($columnAhead && $movingDown) ||
			($movingDown && $movingLeft) ||
			($movingLeft && $rowBehind)
		) {
			$knotToMove->moveRow(1);
			$knotToMove->moveColumn(-1);
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
				$this->start->moveRow($number);
				foreach ($this->knots as $knot) {
					$knot->moveRow($number);
				}
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
			$this->start->moveColumn($number);
			foreach ($this->knots as $knot) {
				$knot->moveColumn($number);
			}
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

	/**
	 * @param int $totalKnots
	 * @return void
	 */
	protected function writePositionsToFile(int $totalKnots): void
	{
		$knots = $this->knots;

		// Remove the tail, we'll handle that separately.
		array_pop($knots);

		// Add each element's point. These can overwrite each other on purpose.
		$points = [];
		foreach ($knots as $index => $knot) {
			$points[(string) $knot] ??= $index + 1;
		}

		ob_start();
		foreach ($this->map as $row => $columns) {
			foreach ($columns as $column => $data) {
				$location = "{$row},{$column}";
				if ($this->head->row === $row && $this->head->column === $column) {
					echo 'H';
				} elseif ($this->tail->row === $row && $this->tail->column === $column) {
					echo 'T';
				} elseif ($this->start->row === $row && $this->start->column === $column) {
					echo 'S';
				} elseif (array_key_exists($location, $points)) {
					echo $points[$location];
				} else {
					echo $data === 1 ? '#' : '.';
				}
			}
			echo "\n";
		}

		file_put_contents($this->input->createFile("output-{$totalKnots}.txt"), ob_get_clean());
	}
}
