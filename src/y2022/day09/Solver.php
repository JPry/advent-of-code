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

	protected $map = [];

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

		$row = 0;
		$col = 0;

		// We need to work through each line.
		$this->walkResourceWithCallback(
			$input,
			function($line) use (&$head, &$tail, &$col, &$row) {
				[$direction, $distance] = explode(' ', $line);
				$distance = (int) $distance;

				// See if we have enough room to move, and expand the map if necessary.
				$where = $direction === 'L' || $direction === 'U'
					? 'before'
					: 'after';

				switch ($direction) {
					case 'L':
						$difference = $col - $distance;
						if ($difference < 0) {

						}
						break;

					case 'U':
						$difference = $row - $distance;
				}

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
		$cols = count($this->map[0]);
		$newRows = array_fill(
			0,
			$number,
			array_fill(0, $cols, 0)
		);

		switch ($where) {
			case 'before':
				array_unshift($this->map, ...$newRows);
				break;

			case 'after':
				$map = array_merge($this->map, $newRows);
				break;

			default:
				throw new Exception('Stop it, go to sleep');
		}
	}

	protected function addColumns(string $where, int $number)
	{
		$newColumns = array_fill(0, $number, 0);
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
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
