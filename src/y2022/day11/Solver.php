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
		$this->monkeys = [
			[
				'items' => [79, 98],
				'operation' => function ($old) {
					return $old * 19;
				},
				'test' => function ($value) {
					return ($value % 23) === 0 ? 2 : 3;
				},
			],
			[
				'items' => [54, 65, 75, 74],
				'operation' => function ($old) {
					return $old + 6;
				},
				'test' => function ($value) {
					return ($value % 19) === 0 ? 2 : 0;
				},
			],
			[
				'items' => [79, 60, 97],
				'operation' => function ($old) {
					return $old * $old;
				},
				'test' => function ($value) {
					return ($value % 13) === 0 ? 1 : 3;
				},
			],
			[
				'items' => [74],
				'operation' => function ($old) {
					return $old + 3;
				},
				'test' => function ($value) {
					return ($value % 17) === 0 ? 0 : 1;
				},
			],
		];
//		$data = $this->splitFileByDoubleNewLine('test');
		$this->part1Logic();
		$this->part2Logic();
	}

	protected function part1()
	{
		$this->monkeys = [
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
				'mod' =>11,
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
		$this->part1Logic();
	}

	protected function part2()
	{
		$this->part2Logic();
	}

	protected function part1Logic()
	{
		$round = 1;
		$this->inspections = [];
		do {
			foreach ($this->monkeys as $index => &$monkey) {
				$this->inspections[$index] ??= 0;
				foreach ($monkey['items'] as $item) {
					$result = $monkey['operation']($item);
					$result = (int) floor($result / 3);
					$throwTo = $monkey['test']($result);
					$this->monkeys[$throwTo]['items'][] = $result;
					$this->inspections[$index]++;
				}

				// All of this monkey's items have been thrown.
				$monkey['items'] = [];
			}
			$round++;
		} while ($round <= 20);

		rsort($this->inspections);
		$result = array_reduce(
			array_slice($this->inspections, 0, 2),
			function($carry, $item) {
				return $carry * $item;
			},
			1
		);

		printf("The product is %d\n", $result);
	}

	protected function part2Logic()
	{
		$round = 1;
		$this->inspections = [];
		do {
			foreach ($this->monkeys as $index => &$monkey) {
				$this->inspections[$index] ??= 0;
				foreach ($monkey['items'] as $item) {
					$result = $monkey['operation']($item);

					$throwTo = $monkey['test']($result);
					$this->monkeys[$throwTo]['items'][] = $result;
					$this->inspections[$index]++;
				}

				// All of this monkey's items have been thrown.
				$monkey['items'] = [];
			}
			$round++;
		} while ($round <= 10000);

		rsort($this->inspections);
		$result = array_reduce(
			array_slice($this->inspections, 0, 2),
			function($carry, $item) {
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
}
