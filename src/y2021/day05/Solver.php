<?php


namespace JPry\AdventOfCode\y2021\day05;

use JPry\AdventOfCode\DayPuzzle;
use JPry\AdventOfCode\Input;

class Solver extends DayPuzzle
{
	/** @var Line[] */
	protected $lines = [];

	/** @var Line[] */
	protected $testLines = [];

	public function __construct(?Input $input = null)
	{
		parent::__construct($input);
		$this->lines = $this->parseLines($this->getHandleForFile('input'));
		$this->testLines = $this->parseLines($this->getHandleForFile('test'));
	}

	public function runTests()
	{
		$this->part1Logic($this->testLines);
		$this->part2Logic($this->testLines);
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

	/**
	 * @param resource $input
	 * @return Line[]
	 */
	protected function parseLines($input): array
	{
		$lines = [];
		while ($line = fgets($input)) {
			if (empty(trim($line))) {
				continue;
			}

			preg_match('#^(\d+),(\d+)\s*\->\s*(\d+),(\d+)#', $line, $matches);

			$lines[] = new Line(
				new Point($matches[1], $matches[2]),
				new Point($matches[3], $matches[4])
			);
		}

		return $lines;
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
