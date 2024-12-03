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

describe('Part 2', function () {
	it('should get the correct result for the test input', function () {
		$data = [
			'7 6 4 2 1', // Safe
			'1 2 7 8 9', // Unsafe
			'9 7 6 2 1', // Unsafe
			'1 3 2 4 5', // Safe
			'8 6 4 4 1', // Safe
			'1 3 6 7 9', // Safe
			'86 85 86 89 92 94 97', // Safe
		];
		expect(solve()->returnTest2($data))->toBe(5);
	});

	it('should get the correct result for the input file', function () {
		$result = solve()->returnPart2();
		expect($result)->toBeGreaterThan(423);
	});
});
