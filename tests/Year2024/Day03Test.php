<?php

use JPry\AdventOfCode\y2024\day03\Solver;

describe('Part 1', function() {
	it('should get the correct result for test input', function() {
		$solver = new Solver();
		expect($solver->returnTest1($solver->getFileContents('test')))->toBe(161);
	});

	it('should get the result for the input file', function() {
		$solver = new Solver();
		$result = $solver->returnPart1();
		echo 'Result: ' . $result . PHP_EOL;
		expect($result)->toBeGreaterThan(0);
	});
});

describe('Part 2', function() {

	it('should get the correct result for the test input', function() {
		$solver = new Solver();
		$input = "xmul(2,4)&mul[3,7]!^don't()_mul(5,5)+mul(32,64](mul(11,8)undo()?mul(8,5))";
		expect($solver->returnTest2($input))->toBe(48);
	});

	it('should get the correct result for the real input', function() {
		$solver = new Solver();
		$result = $solver->returnPart2();
		printResult($result, __FILE__);
		expect($result)->toBeGreaterThan(0);
	});
});
