<?php


namespace JPry\AdventOfCode\y2021\day06;

use JPry\AdventOfCode\DayPuzzle;

class Solver extends DayPuzzle
{
	public function runTests()
	{
		$handle = $this->getHandleForFile('test');
		$this->part1Logic($handle);
		rewind($handle);
		$this->part2Logic($handle);
	}

	protected function part1()
	{
	}

	protected function part2()
	{
	}

	protected function part1Logic($input)
	{

	}

	protected function part2Logic($input)
	{

	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
