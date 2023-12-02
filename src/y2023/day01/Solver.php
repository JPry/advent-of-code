<?php
declare(strict_types=1);

namespace JPry\AdventOfCode\y2023\day01;

use JPry\AdventOfCode\DayPuzzle;

/**
 * Day Solver Class.
 *
 * @link https://adventofcode.com/2023/day/01
 */
class Solver extends DayPuzzle
{
	public function runTests()
	{
		$this->part1Logic($this->getFileAsArray('test'));
		$this->part2Logic($this->getFileAsArray('test2'));
	}

	protected function part1()
	{
		$this->part1Logic($this->getFileAsArray('input'));
	}

	protected function part2()
	{
		$this->part2Logic($this->getFileAsArray('input'));
	}

	protected function part1Logic($lines)
	{
		$sum = array_reduce(
			$lines,
			function($carry, $line) {
				$numbers = preg_replace('#\D#', '', $line);

				switch(strlen($numbers)) {
					case 2:
						$pair = $numbers;
						break;
					case 1:
						$pair = "{$numbers}{$numbers}";
						break;
					default:
						$pair = substr($numbers, 0, 1) . substr($numbers, -1);
						break;
				}

				return $carry + intval($pair);
			},
			0
		);

		$this->writeln("The sum is: {$sum}");
	}

	protected function part2Logic($lines)
	{
		$sum = array_reduce(
			$lines,
			function($carry, $line) {
				$numbers = preg_replace('#\D#', '', $this->replaceNumberWords($line));
				switch(strlen($numbers)) {
					case 2:
						$pair = $numbers;
						break;
					case 1:
						$pair = "{$numbers}{$numbers}";
						break;
					default:
						$pair = substr($numbers, 0, 1) . substr($numbers, -1);
						break;
				}

				return $carry + intval($pair);
			},
			0
		);

		$this->writeln("The sum is: {$sum}");
	}

	protected function replaceNumberWords(string $line)
	{
		static $number_map = [
			'one'     => 'o1e',
			'two'     => 't2o',
			'three'   => 't3e',
			'four'    => 'f4r',
			'five'    => 'f5e',
			'sixteen' => 's16n',
			'six'     => 's6x',
			'seven'   => 's7n',
			'eight'   => 'e8t',
			'nine'    => 'n9e',
		];

		return str_replace(
			array_keys($number_map),
			array_values($number_map),
			$line
		);
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
