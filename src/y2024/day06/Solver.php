<?php
declare(strict_types=1);

namespace JPry\AdventOfCode\y2024\day06;

use JPry\AdventOfCode\DayPuzzle;
use JPry\AdventOfCode\Map;

/**
 * Day Solver Class.
 *
 * @link https://adventofcode.com/2024/day/06
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
		return $this->part1Logic($this->getFileAsArray());
	}

	protected function part2()
	{
		return $this->part2Logic($this->getFileAsArray());
	}

	protected function part1Logic($input)
	{
		$map = new GuardMap($input);
		while (true) {
			try {
				$map->moveGuard();
			} catch (\Exception $e) {
				break;
			}
		}

		return $map->sumGuardPositions();
	}

	protected function part2Logic($input)
	{
		$map = new GuardMap($input);
		while (true) {
			try {
				$map->moveGuard();
			} catch (\Exception $e) {
				break;
			}
		}

//		file_put_contents(
//			$this->input->createFile('output-2-test'),
//			$map->getMapAsString()
//		);

		return $map->sumLoopPosition();
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
