<?php

use JPry\AdventOfCode\y2024\day02\Solver;

function solve(): Solver
{
	static $solver = null;
	if (null === $solver) {
		$solver = new Solver();
	}

	return $solver;
}

describe('Part 1', function () {
	it('should get the correct result for test input', function () {
		expect(solve()->returnTest1())->toBe(2);
	});

	it('should get the correct result for the input file', function () {
		$result = solve()->returnPart1();
		echo 'Result: ' . $result . PHP_EOL;
		expect($result)->toBeGreaterThan(0);
	});
});
});
