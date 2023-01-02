<?php

declare(strict_types=1);

namespace JPry\AdventOfCode\y2022\day11;

use JPry\AdventOfCode\DayPuzzle;
use JPry\AdventOfCode\Utils\StringManipulation;

/**
 * Day Solver Class.
 *
 * @link https://adventofcode.com/2022/day/11
 */
class Solver extends DayPuzzle
{
	use StringManipulation;

	private array $monkeys;

	private array $inspections = [];

	public function runTests()
	{
		$monkeys = [
			[
				'items' => [79, 98],
				'operation' => function ($old) {
					return $old * 19;
				},
				'test' => function ($value) {
					return ($value % 23) === 0 ? 2 : 3;
				},
				'mod' => 23,
			],
			[
				'items' => [54, 65, 75, 74],
				'operation' => function ($old) {
					return $old + 6;
				},
				'test' => function ($value) {
					return ($value % 19) === 0 ? 2 : 0;
				},
				'mod' => 19,
			],
			[
				'items' => [79, 60, 97],
				'operation' => function ($old) {
					return $old * $old;
				},
				'test' => function ($value) {
					return ($value % 13) === 0 ? 1 : 3;
				},
				'mod' => 13,
			],
			[
				'items' => [74],
				'operation' => function ($old) {
					return $old + 3;
				},
				'test' => function ($value) {
					return ($value % 17) === 0 ? 0 : 1;
				},
				'mod' => 17,
			],
		];
//		$data = $this->splitFileByDoubleNewLine('test');
		$this->part1Logic($monkeys);
		$this->part2Logic($monkeys);
	}

	protected function part1()
	{
		$this->part1Logic($this->getMonkeys());
	}

	protected function part2()
	{
		$this->part2Logic($this->getMonkeys());
	}

	protected function part1Logic(array $monkeys)
	{
		$round = 1;
		$inspections = [];
		do {
			foreach ($monkeys as $index => &$monkey) {
				$inspections[$index] ??= 0;
				foreach ($monkey['items'] as $item) {
					$result = $monkey['operation']($item);
					$result = (int) floor($result / 3);
					$throwTo = $monkey['test']($result);
					$monkeys[$throwTo]['items'][] = $result;
					$inspections[$index]++;
				}

				// All of this monkey's items have been thrown.
				$monkey['items'] = [];
			}
			$round++;
		} while ($round <= 20);

		rsort($inspections);
		$result = array_reduce(
			array_slice($inspections, 0, 2),
			function ($carry, $item) {
				return $carry * $item;
			},
			1
		);

		printf("The product is %d\n", $result);
	}

	/**
	 * @see https://www.reddit.com/r/adventofcode/comments/zjsi12/2022_day_11_on_the_spoiler_math_involved/
	 * @param array $monkeys
	 * @return void
	 */
	protected function part2Logic(array $monkeys)
	{
		$round = 1;
		$inspections = [];
		$commonProduct = array_reduce(
			array_column($monkeys, 'mod'),
			function($carry, $item) {
				return $carry * $item;
			},
			1
		);

		do {
			foreach ($monkeys as $index => &$monkey) {
				$inspections[$index] ??= 0;
				foreach ($monkey['items'] as $item) {
					// The monkey escalates worry.
					$result = $monkey['operation']($item);

					// Reduce the worry some.
					$result = $result % $commonProduct;

					$throwTo = $monkey['test']($result);
					$monkeys[$throwTo]['items'][] = $result;
					$inspections[$index]++;
				}

				// All of this monkey's items have been thrown.
				$monkey['items'] = [];
			}
			$round++;
		} while ($round <= 10000);

		rsort($inspections);
		$result = array_reduce(
			array_slice($inspections, 0, 2),
			function ($carry, $item) {
				return $carry * $item;
			},
			1
		);

		printf("The product is %d\n", $result);
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}

	/**
	 * @return array[]
	 */
	protected function getMonkeys(): array
	{
		return [
			[
				'items' => [89, 84, 88, 78, 70],
				'operation' => function ($old) {
					return $old * 5;
				},
				'test' => function ($value) {
					return ($value % 7) === 0 ? 6 : 7;
				},
				'mod' => 7,
			],
			[
				'items' => [76, 62, 61, 54, 69, 60, 85],
				'operation' => function ($old) {
					return $old + 1;
				},
				'test' => function ($value) {
					return ($value % 17) === 0 ? 0 : 6;
				},
				'mod' => 17,
			],
			[
				'items' => [83, 89, 53],
				'operation' => function ($old) {
					return $old + 8;
				},
				'test' => function ($value) {
					return ($value % 11) === 0 ? 5 : 3;
				},
				'mod' => 11,
			],
			[
				'items' => [95, 94, 85, 57],
				'operation' => function ($old) {
					return $old + 4;
				},
				'test' => function ($value) {
					return ($value % 13) === 0 ? 0 : 1;
				},
				'mod' => 13,
			],
			[
				'items' => [82, 98],
				'operation' => function ($old) {
					return $old + 7;
				},
				'test' => function ($value) {
					return ($value % 19) === 0 ? 5 : 2;
				},
				'mod' => 19,
			],
			[
				'items' => [69],
				'operation' => function ($old) {
					return $old + 2;
				},
				'test' => function ($value) {
					return ($value % 2) === 0 ? 1 : 3;
				},
				'mod' => 2,
			],
			[
				'items' => [82, 70, 58, 87, 59, 99, 92, 65],
				'operation' => function ($old) {
					return $old * 11;
				},
				'test' => function ($value) {
					return ($value % 5) === 0 ? 7 : 4;
				},
				'mod' => 5,
			],
			[
				'items' => [91, 53, 96, 98, 68, 82],
				'operation' => function ($old) {
					return $old * $old;
				},
				'test' => function ($value) {
					return ($value % 3) === 0 ? 4 : 2;
				},
				'mod' => 3,
			],
		];
	}
}
