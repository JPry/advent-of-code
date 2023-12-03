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

	protected array $inverted;

	protected string $topLevel = '';

	protected string $containsHuman = '';

	protected int $toMatch = 0;

	public function runTests()
	{
		$this->part1Logic($this->getHandleForFile('test'));
//		$this->part2Logic($this->getHandleForFile('test'));
	}

	protected function part1()
	{
		$this->part1Logic($this->getHandleForFile());
	}

	protected function part2()
	{
//		$this->part2Logic($this->getHandleForFile());
	}

	protected function part1Logic($input)
	{
		$this->walkResourceWithCallback(
			$input,
			function($line) {
				[$monkey, $operation] = explode(': ', $line);

				if ('humn' === $monkey) {
					$this->monkeys[$monkey] = function() use ($operation) {
						printf("Top level: %s\n", $this->topLevel);
						$this->containsHuman = $this->topLevel;
						return $operation;
					};
					return;
				}

				// If we've already got a number, store that as a callable and move on.
				if (is_numeric($operation)) {
					$callable = function() use ($operation) {
						return (int) $operation;
					};

					$this->monkeys[$monkey] = $callable;
					$this->inverted[$monkey] = $callable;
					return;
				}

				preg_match('#(\w+) ([+\-*/]) (\w+)#', $operation, $matches);
				$m1 = $matches[1];
				$m2 = $matches[3];
				$op = $matches[2];

				if ('root' === $monkey) {
					$callable = function() use ($m1, $m2) {
						$this->topLevel = $m1;
						$m1result = $this->monkeys[$m1]();
						isset($this->containsHuman) && $this->toMatch = $m1result;
						$this->topLevel = '';

						$this->topLevel = $m2;
						$m2result = $this->monkeys[$m2]();
						!isset($this->toMatch) && $this->toMatch = $m1result;
						$this->topLevel = '';

						printf("Number to match: %d\n", $this->toMatch);

						return $m1result + $m2result;
					};

					$this->monkeys[$monkey] = $callable;
					return;
				}

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

		printf("Monkey root will yell out: %d\n", $this->monkeys['root']());
	}

	protected function part2Logic($input)
	{
		$monkeys = [];
		$stack = [];
		$human = 0;
		$this->walkResourceWithCallback(
			$input,
			function($line) use (&$monkeys, &$stack) {
				[$monkey, $operation] = explode(': ', $line);



				if ('humn' === $monkey) {

				}

				// If we've already got a number, store that as a callable and move on.
				if (is_numeric($operation)) {
					$monkeys[$monkey] = function() use ($operation) {
						return (int) $operation;
					};
					return;
				}

				if ('root' === $monkey) {

				}

				preg_match('#(\w+) ([+\-*/]) (\w+)#', $operation, $matches);
				$m1 = $matches[1];
				$m2 = $matches[3];
				$op = $matches[2];

				switch ($op) {
					case '+':
						$callable = function() use ($m1, $m2, &$monkeys, &$stack) {
							return $monkeys[$m1]() + $monkeys[$m2]();
						};
						break;

					case '-':
						$callable = function() use ($m1, $m2, &$monkeys) {
							return $monkeys[$m1]() - $monkeys[$m2]();
						};
						break;

					case '*':
						$callable = function() use ($m1, $m2, &$monkeys) {
							return $monkeys[$m1]() * $monkeys[$m2]();
						};
						break;

					case '/':
						$callable = function() use ($m1, $m2, &$monkeys) {
							return $monkeys[$m1]() / $monkeys[$m2]();
						};
						break;

					default:
						throw new RuntimeException(sprintf('Unknown operator: %s', $op));
				}

				$monkeys[$monkey] = $callable;
			}
		);

		printf("Human should yell out: %d\n", $this->monkeys['root']());
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
