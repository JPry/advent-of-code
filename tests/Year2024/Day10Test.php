<?php

declare(strict_types=1);

use JPry\AdventOfCode\y2024\day10\Solver;

describe('Part 1', function() {
	it('should get the correct result for test input', function($data, $expected) {
		$solver = new Solver();
		$result = $solver->returnTest1(explode("\n", $data));
		expect($result)->toBe($expected);
	})->with('day 10');

	it('should get the result for the input file', function() {
		$result = (new Solver())->returnPart1();
		printResult($result, __FILE__);
		expect($result)->toBeGreaterThan(247)->toBe(694);
	});
});

describe('Part 2', function() {

	it('should get the correct result for the test input', function($data, $expected) {
		$solver = new Solver();
		$result = $solver->returnTest2(explode("\n", $data));
		expect($result)->toBe($expected);
	})->with('day 10 part 2');

	it('should get the correct result for the real input', function() {
		$result = (new Solver())->returnPart2();
		printResult($result, __FILE__);
		expect($result)->toBeGreaterThan(0);
	});
});
