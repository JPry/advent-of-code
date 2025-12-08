<?php
declare(strict_types=1);

namespace JPry\AdventOfCode\y2024\day13;

use JPry\AdventOfCode\DayPuzzle;

/**
 * Day Solver Class.
 *
 * @link https://adventofcode.com/2024/day/13
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
		return $this->part1Logic($this->splitFileByDoubleNewLine());
	}

	protected function part2()
	{
	}

	protected function part1Logic($input)
	{
		$sections = array_map(
			fn($section) => $this->parseSection($section),
			$input
		);
	}

	protected function parseSection(string $section)
	{
		[$a, $b, $p] = array_map(
			function( $value ) {
				$value = trim($value);
				[$x, $y] = array_map(
					fn($value) => (int) preg_replace('#\D#', '', $value),
					explode(',', $value)
				);

				return [ 'x' => $x, 'y' => $y ];
			},
			array_unique(explode("\n", $section))
		);

		return [
			'a' => $a,
			'b' => $b,
			'p' => $p,
		];
	}

	protected function part2Logic($input)
	{

	}

	public function returnTest1($input = null, $filename = 'test')
	{
		return $this->part1Logic($this->splitFileByDoubleNewLine($filename));
	}


	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
