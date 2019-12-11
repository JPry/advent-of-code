<?php


namespace JPry\AdventOfCode\y2019\day01;

use JPry\AdventOfCode\DayPuzzle;
use LogicException;

class Solver extends DayPuzzle
{
	protected $part1Result = 0;
	protected $part2Result = 0;

	public function test1()
	{
		$handle = $this->getHandleForFile('test');
		while (list($data, $expected) = fgetcsv($handle)) {
			$result = $this->calculate($data);
			if (!assert($result === (int) $expected)) {
				printf('Got result "%s" for value "%s"%s', $result, $data, PHP_EOL);
				throw new LogicException('Value was incorrect for test');
			}
		}
		fclose($handle);
		echo 'Test passed.', PHP_EOL;
	}

	protected function part1()
	{
		$handle = $this->getHandleForFile('part1');
		while (false !== ($line = fgets($handle))) {
			$line = (int) trim($line);
			$this->part1Result += $this->calculate($line);
		}
		fclose($handle);
		printf('Total fuel for modules is %s', $this->part1Result);
		echo PHP_EOL;
	}


	protected function part2()
	{
		$handle = $this->getHandleForFile('part1');
		while (false !== ($line = fgets($handle))) {
			$line = (int) trim($line);
			$this->part2Result += $this->calculateToZero($line);
		}
		fclose($handle);

		printf('Total fuel for modules (fuel considered) is %s', $this->part2Result);
		echo PHP_EOL;
	}

	protected function calculate(int $value): int
	{
		return (intdiv($value, 3) - 2);
	}

	protected function calculateToZero(int $value): int
	{
		$nextValue = $this->calculate($value);
		$return = 0;
		do {
			$return += $nextValue;
			$nextValue = $this->calculate($nextValue);
		} while ($nextValue > 0);

		return $return;
	}

	protected function getNamespace()
	{
		return __NAMESPACE__;
	}
}
