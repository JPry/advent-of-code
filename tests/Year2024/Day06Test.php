<?php

declare(strict_types=1);

use JPry\AdventOfCode\y2024\day06\Solver;

describe('Part 1', function() {
	it('should get the correct result for test input', function() {
		$result = (new Solver())->returnTest1();
		expect($result)->toBe(41);
	})->done(note: 'Need to write this test');

	it('should get the result for the input file', function() {
		$result = (new Solver())->returnPart1();
		printResult($result, __FILE__);
		expect($result)->toBeGreaterThan(0);
	})->done(note: 'Need to write this test');
});

describe('Part 2', function() {

	it('should get the correct result for the test input', function() {
		
	})->todo(note: 'Need to write this test');

	it('should get the correct result for the real input', function() {
		
	})->todo(note: 'Need to write this test');
});
