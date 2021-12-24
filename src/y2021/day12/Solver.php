<?php


namespace JPry\AdventOfCode\y2021\day12;

use JPry\AdventOfCode\DayPuzzle;

class Solver extends DayPuzzle
{
	public function runTests()
	{
		$data = $this->getFileAsArray('test1');
		$this->part1Logic($data);
		$this->part2Logic($data);
	}

	protected function part1()
	{
	}

	protected function part2()
	{
	}

	protected function part1Logic(array $input)
	{
		$map = $this->parseLines($input);
		$paths = $this->countPaths($map, 'start');
	}

	protected function part2Logic($input)
	{

	}

	protected function parseLines(array $input): array
	{
		$return = [];
		foreach ($input as $line) {
			[$point, $connection] = explode('-', $line);
			if (!array_key_exists($point, $return)) {
				$return[$point] = [];
			}

			if (!array_key_exists($connection, $return)) {
				$return[$connection] = [];
			}

			$return[$point][] = $connection;
			$return[$connection][] = $point;
		}

		foreach ($return as &$values) {
			$values = array_unique($values);
		}

		return $return;
	}

	protected function countPaths(array &$map, string $point)
	{
		$visited = [];
		$validPaths = 0;
		$isSmallCave = false;

		// $caves is an array of all adjacent caves
		$caves = $map[$point];

		$findValidPaths = function($caves) use (&$map) {

		};

		// Loop through all caves, and all of their caves, and their caves, etc.
		foreach ($caves as $cave) {
			$isSmallCave = strtolower($cave) === $cave;
		}


	}

	protected function walkPath()
	{

	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
