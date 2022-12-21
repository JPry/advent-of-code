<?php
declare(strict_types=1);

namespace JPry\AdventOfCode\y2022\day21;

use JPry\AdventOfCode\DayPuzzle;
use JPry\AdventOfCode\Utils\WalkResource;
use RuntimeException;

/**
 * Day Solver Class.
 *
 * @link https://adventofcode.com/2022/day/21
 */
class Solver extends DayPuzzle
{
	use WalkResource;

	/** @var callable[] */
	protected array $monkeys;

	public function runTests()
	{
		$data = $this->getHandleForFile('test');
		$this->part1Logic($data);
		$this->part2Logic($data);
	}

	protected function part1()
	{
		$this->part1Logic($this->getHandleForFile('input'));
	}

	protected function part2()
	{
	}

	protected function part1Logic($input)
	{
		$this->walkResourceWithCallback(
			$input,
			function($line) {
				[$monkey, $operation] = explode(': ', $line);

				// If we've already got a number, store that as a callable and move on.
				if (is_numeric($operation)) {
					$this->monkeys[$monkey] = function() use ($operation) {
						return (int) $operation;
					};
					return;
				}

				preg_match('#(\w+) ([+\-*/]) (\w+)#', $operation, $matches);
				$m1 = $matches[1];
				$m2 = $matches[3];
				$op = $matches[2];

				switch ($op) {
					case '+':
						$callable = function() use ($m1, $m2) {
							return $this->monkeys[$m1]() + $this->monkeys[$m2]();
						};
						break;

					case '-':
						$callable = function() use ($m1, $m2) {
							return $this->monkeys[$m1]() - $this->monkeys[$m2]();
						};
						break;

					case '*':
						$callable = function() use ($m1, $m2) {
							return $this->monkeys[$m1]() * $this->monkeys[$m2]();
						};
						break;

					case '/':
						$callable = function() use ($m1, $m2) {
							return $this->monkeys[$m1]() / $this->monkeys[$m2]();
						};
						break;

					default:
						throw new RuntimeException(sprintf('Unknown operator: %s', $op));
				}

				$this->monkeys[$monkey] = $callable;
			}
		);

		printf("Mokey root will yell out: %d\n", $this->monkeys['root']());
	}

	protected function part2Logic($input)
	{
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
