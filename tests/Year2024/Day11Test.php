<?php

declare(strict_types=1);

use JPry\AdventOfCode\y2024\day11\Solver;

describe('Part 1', function() {
	it('should get the correct results for certain numbers', function($number, $expected) {
		$result = (new Solver())->applyRulesToNumber($number);
		expect($result)->toBe($expected);
	})->with('day 11');

	it('should get the correct result for test input', function() {
		$result = (new Solver())->returnTest1();
		expect($result)->toBe(55312);
	});

	it('should get the result for the input file', function() {
		$result = (new Solver())->returnPart1();
		printResult($result, __FILE__);
		expect($result)->toBeGreaterThan(8)->toBe(189167);
	});
});

describe('Part 2', function() {
	it('should get the correct result for the real input', function() {
		$result = (new Solver())->returnPart2();
		printResult($result, __FILE__);
		expect($result)->toBeGreaterThan(189167);
	});
});
