<?php
declare(strict_types=1);

namespace JPry\AdventOfCode\y2022\day10;

use JPry\AdventOfCode\DayPuzzle;

/**
 * Day Solver Class.
 *
 * @link https://adventofcode.com/2022/day/10
 */
class Solver extends DayPuzzle
{
	private bool $doSprite = false;

	public function runTests()
	{
		$this->part1Logic($this->getHandleForFile('test'), 220);
		$this->doSprite = true;
		$this->part1Logic($this->getHandleForFile('test'), 240);
	}

	protected function part1()
	{
		$this->part1Logic($this->getHandleForFile(), 220);
	}

	protected function part2()
	{
		$this->doSprite = true;
		$this->part1Logic($this->getHandleForFile(), 240);
	}

	/**
	 * @param resource $input
	 * @return void
	 */
	protected function part1Logic($input, $cycles)
	{
		$cycle = 1;
		$x = 1;
		$strengths = [];
		$nextRead = 1;
		$add = 0;
		$instruction = $this->getInstruction(trim(fgets($input)));

		do {
			// Start of cycle
			if ($nextRead === $cycle) {
				switch ($instruction['op']) {
					case 'noop':
						$nextRead = $cycle + $instruction['toComplete'];
						$add = 0;
						break;

					case 'addx':
						$nextRead = $cycle + $instruction['toComplete'];
						$add = $instruction['value'];
						break;
				}
			}

			// During cycle
			$this->maybePrint($x, $cycle);
			if ($cycle % 40 === 20) {
				$strengths[] = $cycle * $x;
			}
			if ($cycle % 40 === 0 && $this->doSprite) {
				echo "\n";
			}

			// End of cycle
			$cycle++;
			if ($nextRead === $cycle) {
				$x += $add;
				$line = fgets($input);
				if (false === $line) {
					break;
				}
				$instruction = $this->getInstruction(trim($line));
			}
		} while ($cycle <= $cycles);

		fclose($input);

		if (!$this->doSprite) {
			printf("The sum of strengths is: %d\n", array_sum($strengths));
		}
	}

	protected function part2Logic($input)
	{
	}

	protected function getInstruction(string $line): array
	{
		if ('noop' === $line) {
			return [
				'op' => 'noop',
				'toComplete' => 1,
			];
		} else {
			[$op, $value] = explode(' ', $line);
			return [
				'op' => $op,
				'value' => (int) $value,
				'toComplete' => 2,
			];
		}
	}

	protected function maybePrint($x, $cycle)
	{
		if (!$this->doSprite) {
			return;
		}

		$index = ($cycle - 1) % 40;

		if ($x - 1 <= $index && $index <= $x + 1) {
			echo '#';
		} else {
			echo ' ';
		}
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
