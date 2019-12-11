<?php


namespace JPry\AdventOfCode\y2019\day02;

use JPry\AdventOfCode\DayPuzzle;
use LogicException;
use RuntimeException;

class Solver extends DayPuzzle
{
	protected $original = [];
	protected $final = [];
	protected $position = 0;

	public function testData()
	{
		$data = [
			[
				[1, 0, 0, 0, 99],
				[2, 0, 0, 0, 99],
			],
			[
				[2, 3, 0, 3, 99],
				[2, 3, 0, 6, 99],
			],
			[
				[2, 4, 4, 5, 99, 0],
				[2, 4, 4, 5, 99, 9801],
			],
			[
				[1, 1, 1, 4, 99, 5, 6, 0, 99],
				[30, 1, 1, 4, 2, 5, 6, 0, 99],
			],
		];

		foreach ($data as $test) {
			$this->test($test[0], $test[1]);
		}

		$this->final = $this->original = [];
		echo 'Test was successful.', PHP_EOL;
	}

	protected function part1()
	{
		$this->setup();
		$this->final[1] = 12;
		$this->final[2] = 2;
		$this->runProgram();
		printf("Position 0 contains %s\n", $this->final[0]);
	}

	protected function part2()
	{
		$targetOutput = 19690720;
		$noun = $verb = 0;
		$max = 99;
		do {
			$this->setup();
			$this->final[1] = $noun;
			$this->final[2] = $verb;
			$this->runProgram();

			if ($targetOutput === $this->final[0]) {
				break;
			}

			$noun++;
			if ($noun > $max) {
				$verb++;
				$noun = 0;

				if ($verb > $max) {
					throw new LogicException('Unable to find target output');
				}
			}
		} while (true);

		echo 'Noun: ', $noun, PHP_EOL, 'Verb: ', $verb, PHP_EOL;
	}

	protected function runProgram()
	{
		$this->position = 0;
		$length = count($this->final);
		while ($this->position < $length) {
			try {
				$this->runOpcode();
				$this->position += 4;
			} catch (RuntimeException $e) {
				break;
			}
		}
	}

	protected function runOpcode()
	{
		$f = &$this->final;
		$action = $f[$this->position];
		if (99 === $action) {
			throw new RuntimeException('Time to halt the program!');
		}

		$op1 = $f[$this->position + 1];
		$op2 = $f[$this->position + 2];
		$result = $f[$this->position + 3];

		if (1 === $action) {
			$f[$result] = $f[$op1] + $f[$op2];
		} elseif (2 === $action) {
			$f[$result] = $f[$op1] * $f[$op2];
		} else {
			throw new LogicException(
				sprintf('Invalid option for position %s: %s', $this->position, $f[$this->position])
			);
		}
	}

	protected function setup()
	{
		if (empty($this->original)) {
			$filePath = $this->getFilePath('input');
			$raw = explode(',', trim(file_get_contents($filePath)));
			$this->original = array_map('intval', $raw);
		}
		$this->final = $this->original;
	}

	protected function getNamespace()
	{
		return __NAMESPACE__;
	}

	protected function test($input, $expected)
	{
		$this->original = $this->final = $input;
		$this->runProgram();
		if ($expected !== $this->final) {
			printf(
				'Result was: [%1$s].%3$sExpected:   [%2$s]%3$s',
				join(',', $this->final),
				join(',', $expected),
				PHP_EOL
			);
			throw new LogicException('Value was incorrect for test');
		}
	}
}
