<?php

use JPry\AdventOfCode\y2024\day02\Solver;

describe('Part 1', function () {
	$solve = fn() => new Solver();

	it('should get the correct result for test input', function () use ($solve) {
		expect($solve()->returnTest1())->toBe(2);
	});

	it('should get the correct result for the input file', function () use ($solve) {
		$result = $solve()->returnPart1();
		echo 'Result: ' . $result . PHP_EOL;
		expect($result)->toBeGreaterThan(0);
	});
});

describe('Part 2', function () {
	$solve = fn() => new Solver();

	it('should get the correct result for the test input', function ($line) use ($solve) {
		$data = array_map('intval', explode(' ', $line));
		expect($solve()->is_line_safe($data))->toBeTrue();
	})->with('day2');

	it('should get the correct result for the input file', function () use ($solve) {
		$result = $solve()->returnPart2();
		expect($result)->toBeGreaterThan(423);
	});
});
