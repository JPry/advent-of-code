<?php
declare(strict_types=1);

namespace JPry\AdventOfCode\y2022\day09;

use Exception;
use JPry\AdventOfCode\DayPuzzle;
use JPry\AdventOfCode\Point;
use JPry\AdventOfCode\Utils\WalkResource;

class Solver extends DayPuzzle
{
	use WalkResource;

	/** @var array */
	protected $map = [];

	/** @var int */
	protected $row = 0;

	/** @var int  */
	protected $col = 0;

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
		$head = new Point(0,0);
		$tail = new Point(0,0);

		// We need to track where the tail has been.

		// Let's start with a 20x20 grid.
		$this->map = array_fill(
			0,
			10,
			array_fill(0, 10, 0)
		);

		// We start at 0,0, so mark that as being visited
		$this->map[0][0] = 1;

		// We need to work through each line.
		$this->walkResourceWithCallback(
			$input,
			function($line) use (&$head, &$tail) {
				[$direction, $distance] = explode(' ', $line);
				$distance = (int) $distance;

				// See if we have enough room to move, and expand the map if necessary.
				switch ($direction) {
					case 'L':
						$difference = $this->col - $distance;
						if ($difference < 0) {
							// Add more columns to the left
							$this->addColumns('before', abs($difference));
						}
						break;

					case 'R':
						$difference = $this->col + $distance;
						if ($difference >= count($this->map[0])) {
							$this->addColumns('after', $difference - count($this->map[0]));
						}
						break;

					case 'U':
						$difference = $this->row - $distance;
						if ($difference < 0) {
							$this->addRows('before', abs($difference));
						}
						break;

					case 'D':
						$difference = $this->row + $distance;
						if ($difference >= count($this->map)) {
							$this->addRows('after', $difference - count($this->map));
						}
						break;
				}

				// Do the moving.
				while ($distance > 0) {

					$distance--;
				}
			}
		);
	}

	protected function moveHeadAndTail(string $direction, string $distance)
	{
		$distance = (int) $distance;
		while ($distance > 0) {

			$distance--;
		}
	}

	protected function part2Logic($input)
	{

	}

	protected function addRows(string $where, int $number)
	{
		printf("Adding new %d new rows %s\n", $number, $where);
		$cols = count($this->map[0]);
		$newRows = array_fill(
			0,
			$number,
			array_fill(0, $cols, 0)
		);

		switch ($where) {
			case 'before':
				array_unshift($this->map, ...$newRows);
				$this->row += $number;
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
		printf("Adding new %d new columns %s\n", $number, $where);
		foreach ($this->map as &$row) {
			switch ($where) {
				case 'before':
					$row = array_merge($newColumns, $row);
					$this->col += $number;
					break;

				case 'after':
					$row = array_merge($row, $newColumns);
					break;

				default:
					throw new Exception('Stop it, go to sleep');
			}
		}
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
