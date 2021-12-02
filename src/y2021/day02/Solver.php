<?php


namespace JPry\AdventOfCode\y2021\day02;

use JPry\AdventOfCode\DayPuzzle;

class Solver extends DayPuzzle
{
	public function runTests()
	{
		$handle = $this->getHandleForFile('test');
		$this->part1Logic($handle);
		rewind($handle);
		$this->part2Logic($handle);
	}

	protected function part1()
	{
		$handle = $this->getHandleForFile('input');
		$this->part1Logic($handle);
		fclose($handle);
	}

	protected function part2()
	{
		$handle = $this->getHandleForFile('input');
		$this->part2Logic($handle);
		fclose($handle);
	}

	protected function part1Logic($input)
	{
		$totals = [
			'forward' => 0,
			'depth' => 0,
		];

		while ($line = fgets($input)) {
			preg_match('#^(forward|up|down)\s*(\d+)#', $line, $matches);
			switch($matches[1]) {
				case 'forward':
					$totals['forward'] += $matches[2];
					break;

				case 'up':
					$totals['depth'] -= $matches[2];
					break;

				case 'down':
					$totals['depth'] += $matches[2];
					break;
			}
		}

		printf(
			"Forward: %d\nDepth: %d\nProduct: %d\n",
			$totals['forward'],
			$totals['depth'],
			array_product($totals)
		);
	}

	protected function part2Logic($input)
	{
		$totals = [
			'forward' => 0,
			'depth' => 0,
			'aim' => 0,
		];

		while ($line = fgets($input)) {
			preg_match('#^(forward|up|down)\s*(\d+)#', $line, $matches);
			[, $direction, $distance] = $matches;
			switch($direction) {
				case 'forward':
					$totals['forward'] += $distance;
					$totals['depth'] += ($totals['aim'] * $distance);
					break;

				case 'up':
					$totals['aim'] -= $distance;
					break;

				case 'down':
					$totals['aim'] += $distance;
					break;
			}
		}

		printf(
			"Forward: %d\nDepth: %d\nAim: %d\nProduct: %d\n",
			$totals['forward'],
			$totals['depth'],
			$totals['aim'],
			array_product(array_slice($totals, 0, 2))
		);
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
