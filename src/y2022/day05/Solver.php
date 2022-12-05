<?php

declare(strict_types=1);

namespace JPry\AdventOfCode\y2022\day05;

use JPry\AdventOfCode\DayPuzzle;
use JPry\AdventOfCode\Utils\WalkResource;

class Solver extends DayPuzzle
{
	use WalkResource;

	public function runTests()
	{
		$this->part1Logic($this->getHandleForFile('test'));
		$this->part2Logic($this->getHandleForFile('test'));
	}

	protected function part1()
	{
		$this->part1Logic($this->getHandleForFile('input'));
	}

	protected function part2()
	{
		$this->part2Logic($this->getHandleForFile('input'));
	}

	protected function part1Logic($input)
	{
		$crateMap = [];
		$this->walkResourceWithCallback(
			$input,
			function ($line) use (&$crateMap) {
				$line = rtrim($line, "\n");

				// Check for the line with numbers
				if (str_starts_with($line, ' 1 ')) {
					return;
				}

				static $processingMoves = false;
				if ('' === $line) {
					$processingMoves = true;
					return;
				}

				if ($processingMoves) {
					$this->part1Move($line, $crateMap);
				} else {
					$this->makeMap($line, $crateMap);
				}
			},
			false
		);

		print_r($crateMap);

		$result = array_reduce(
			$crateMap,
			function ($carry, $stack) {
				return $carry .= str_replace(['[', ']'], '', end($stack));
			},
			''
		);

		printf("The final order is: %s\n", $result);
	}

	protected function part2Logic($input)
	{
		$crateMap = [];
		$this->walkResourceWithCallback(
			$input,
			function ($line) use (&$crateMap) {
				$line = rtrim($line, "\n");

				// Check for the line with numbers
				if (str_starts_with($line, ' 1 ')) {
					return;
				}

				static $processingMoves = false;
				if ('' === $line) {
					$processingMoves = true;
					return;
				}

				if ($processingMoves) {
					$this->part2Move($line, $crateMap);
				} else {
					$this->makeMap($line, $crateMap);
				}
			},
			false
		);

		print_r($crateMap);

		$result = array_reduce(
			$crateMap,
			function ($carry, $stack) {
				return $carry .= str_replace(['[', ']'], '', end($stack));
			},
			''
		);

		printf("The final order is: %s\n", $result);
	}

	protected function makeMap($line, array &$crateMap)
	{
		$row = array_map(
			function ($crate) {
				return trim($crate);
			},
			str_split($line, 4)
		);

		foreach ($row as $index => $crate) {
			$crateMap[$index + 1] ??= [];
			if (empty($crate)) {
				continue;
			}

			array_unshift($crateMap[$index + 1], $crate);
		}
	}

	protected function part1Move($line, array &$crateMap)
	{
		preg_match('#^move (\d+) from (\d+) to (\d+)#', $line, $matches);
		$number = (int) $matches[1];
		$from = (int) $matches[2];
		$to = (int) $matches[3];

		while ($number > 0) {
			$crateMap[$to][] = array_pop($crateMap[$from]);
			$number--;
		}
	}

	protected function part2Move($line, array &$crateMap)
	{
		preg_match('#^move (\d+) from (\d+) to (\d+)#', $line, $matches);
		$number = (int) $matches[1];
		$from = (int) $matches[2];
		$to = (int) $matches[3];

		$subStack = [];
		while ($number > 0) {
			$subStack[] = array_pop($crateMap[$from]);
			$number--;
		}

		$subStack = array_reverse($subStack);
		foreach ($subStack as $item) {
			$crateMap[$to][] = $item;
		}
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
