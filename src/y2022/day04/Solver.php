<?php
declare(strict_types=1);

namespace JPry\AdventOfCode\y2022\day04;

use JPry\AdventOfCode\DayPuzzle;

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
		$this->part1Logic($this->getFileAsArray('input'));
	}

	protected function part2()
	{
		$this->part2Logic($this->getFileAsArray('input'));
	}

	protected function part1Logic($input)
	{
		$overlaps = 0;
		foreach ($input as $pair) {
			[$f, $s] = explode(',', $pair);
			$f = explode('-', $f);
			$s = explode('-', $s);

			if (
				$f[0] >= $s[0] && $f[1] <= $s[1]
				|| $f[0] <= $s[0] && $f[1] >= $s[1]
			) {
				$overlaps++;
			}
		}

		printf("There were %d overlaps\n", $overlaps);
	}

	protected function part2Logic($input)
	{
		$overlaps = 0;
		foreach ($input as $pair) {
			[$f, $s] = explode(',', $pair);
			$f = range(...explode('-', $f));
			$s = range(...explode('-', $s));

			if (count(array_intersect($f, $s))>0) {
				$overlaps++;
			}

		}

		printf("There were %d overlaps\n", $overlaps);
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
