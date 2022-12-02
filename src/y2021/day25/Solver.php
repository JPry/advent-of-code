<?php


namespace JPry\AdventOfCode\y2021\day25;

use JPry\AdventOfCode\DayPuzzle;

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
		$this->part1Logic($this->getFileAsArray());
	}

	protected function part2()
	{
	}

	protected function part1Logic(array $input)
	{
		// Convert the input into cucumbers.

		// Move the eastward ones first

		// Move the southward ones next
	}

	protected function part2Logic($input)
	{

	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
