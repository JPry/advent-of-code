<?php
declare(strict_types=1);

namespace JPry\AdventOfCode\y2022\day19;

use JPry\AdventOfCode\DayPuzzle;
use JPry\AdventOfCode\Utils\WalkResource;

/**
 * Day Solver Class.
 *
 * @link https://adventofcode.com/2022/day/19
 */
class Solver extends DayPuzzle
{
	use WalkResource;

	public function runTests()
	{
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

	protected function part1Logic($input)
	{

	}

	protected function part2Logic($input)
	{

	}

	/**
	 * @param resource $file
	 * @return void
	 */
	protected function parseBlueprints($file)
	{
		$this->walkResourceWithCallback(
			$file,
			function($line) {
				[$b, $r] = explode(': ', $line);
			}
		);
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
