<?php
declare(strict_types=1);

namespace JPry\AdventOfCode\y2023\day02;

use JPry\AdventOfCode\DayPuzzle;

/**
 * Day Solver Class.
 *
 * @link https://adventofcode.com/2023/day/02
 */
class Solver extends DayPuzzle
{
	public function runTests()
	{
		$data = $this->getFileAsArray('test');
		$this->part1Logic($data);
		$this->part2Logic($data);
	}

	protected function part1()
	{
	}

	protected function part2()
	{
	}

	protected function part1Logic($input)
	{
		$red = 12;
		$green = 13;
		$blue = 14;
		
		$valid = [];
		
		foreach ($input as $game) {
			[$id, $details] = explode(':', $game);
			$id = intval(preg_replace('#\D#', '', $id));
			
			$sets = explode(';', $details);
		}
		
	}

	protected function part2Logic($input)
	{

	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}