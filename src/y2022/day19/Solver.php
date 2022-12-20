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

	protected array $blueprints = [];

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
		$this->parseBlueprints($input);
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
				preg_match('#[^\d]+(\d+)$#', $b, $matches);
				$id = (int) $matches[1];

				$robots = [];
				$rawRobots = explode('. ', $r);
				foreach ($rawRobots as $rawRobot) {
					preg_match('#Each (\w+) robot costs (\d+) (\w+)(?: and (\d+) (\w+))?#', $rawRobot, $matches);
					$type = $matches[1];
					$cost = [
						$matches[3] => (int) $matches[2],
					];
					if (isset($matches[4], $matches[5])) {
						$cost[$matches[5]] = (int) $matches[4];
					}

					$robots[] = new Robot($type, new Cost($cost));
				}

				$this->blueprints[$id] = $robots;
			}
		);
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
