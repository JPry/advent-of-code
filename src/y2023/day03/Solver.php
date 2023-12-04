<?php
declare(strict_types=1);

namespace JPry\AdventOfCode\y2023\day03;

use JPry\AdventOfCode\DayPuzzle;
use JPry\AdventOfCode\Map;
use JPry\AdventOfCode\Point;
use RuntimeException;

/**
 * Day Solver Class.
 *
 * @link https://adventofcode.com/2023/day/03
 */
class Solver extends DayPuzzle
{
	public function runTests()
	{
		$data = $this->getFileAsArray('test');
		$this->part1Logic($data);
		$this->part2Logic($data);
	}

	protected function part1()
	{
		$this->part1Logic($this->getFileAsArray());
	}

	protected function part2()
	{
		$this->part2Logic($this->getFileAsArray());
	}

	protected function part1Logic($input)
	{
		$map = new Map(array_map('str_split', $input));
		$sum = [];
		$symbols = [];

		$map->walkMap(
			function($value, $rowIndex, $columnIndex) use (&$symbols) {
				if (preg_match('#[^\d.a]#', $value)) {
					$symbols["{$rowIndex},{$columnIndex}"] = true;
				}
			}
		);

		$checked = $symbols;
		foreach ($symbols as $coordinates => $_) {
			// Look in all directions for numbers.
			$symbolPoint = Point::fromString($coordinates);

			// Get the surrounding points.
			$points = $map->getPointsAround($symbolPoint);
			foreach ($points as $point) {
				// If the point has already been checked, continue.
				if (array_key_exists((string) $point, $checked)) {
					continue;
				}

				// Mark the point as checked.
				$checked[(string) $point] = true;
				$value = $map->getValue($point);

				// If the point is a period, continue.
				if ('.' === $value) {
					continue;
				}

				// If the point is a number, figure out the beginning and end of the number.
				if (is_numeric($value)) {
					$next = clone $point;

					// Walk west until we get a non-number or the end of the row or a point we checked already.
					while (true) {
						try {
							$next = $next->west();
							if (array_key_exists((string) $next, $checked)) {
								break;
							} else {
								$checked[(string) $next] = true;
							}

							$nextValue = $map->getValue($next);
							if (is_numeric($nextValue)) {
								$value = $nextValue . $value;
							} else {
								break;
							}

						} catch (RuntimeException $e) {
							break;
						}
					}

					// Walk east until we get a non-number or the end of the row or a point we checked already.
					$next = clone $point;
					while (true) {
						try {
							$next = $next->east();
							if (array_key_exists((string) $next, $checked)) {
								break;
							} else {
								$checked[(string) $next] = true;
							}

							$nextValue = $map->getValue($next);
							if (is_numeric($nextValue)) {
								$value .= $nextValue;
							} else {
								break;
							}

						} catch (RuntimeException $e) {
							break;
						}
					}

					// If we have a number, add it to the sum.
					$sum[] = (int) $value;
				}
			}
		}

		$this->writeln('The sum is: ' . array_sum($sum));
	}

	protected function part2Logic($input)
	{
		$map = new Map(array_map('str_split', $input));
		$sum = [];
		$gears = [];

		$map->walkMap(
			function($value, $rowIndex, $columnIndex) use (&$gears) {
				if ('*' === $value) {
					$gears["{$rowIndex},{$columnIndex}"] = true;
				}
			}
		);

		$checked = $gears;
		foreach ($gears as $coordinates => $_) {
			// Look in all directions for numbers.
			$symbolPoint = Point::fromString($coordinates);

			// Get the surrounding points.
			$points = $map->getPointsAround($symbolPoint);
			$foundNumbers = [];
			foreach ($points as $point) {
				// If the point has already been checked, continue.
				if (array_key_exists((string) $point, $checked)) {
					continue;
				}

				// Mark the point as checked.
				$checked[(string) $point] = true;
				$value = $map->getValue($point);

				// If the point is a period, continue.
				if ('.' === $value) {
					continue;
				}

				// If the point is a number, figure out the beginning and end of the number.
				if (is_numeric($value)) {
					$next = clone $point;

					// Walk west until we get a non-number or the end of the row or a point we checked already.
					while (true) {
						try {
							$next = $next->west();
							if (array_key_exists((string) $next, $checked)) {
								break;
							} else {
								$checked[(string) $next] = true;
							}

							$nextValue = $map->getValue($next);
							if (is_numeric($nextValue)) {
								$value = $nextValue . $value;
							} else {
								break;
							}

						} catch (RuntimeException $e) {
							break;
						}
					}

					// Walk east until we get a non-number or the end of the row or a point we checked already.
					$next = clone $point;
					while (true) {
						try {
							$next = $next->east();
							if (array_key_exists((string) $next, $checked)) {
								break;
							} else {
								$checked[(string) $next] = true;
							}

							$nextValue = $map->getValue($next);
							if (is_numeric($nextValue)) {
								$value .= $nextValue;
							} else {
								break;
							}

						} catch (RuntimeException $e) {
							break;
						}
					}

					// If we have a number, add it to found array
					$foundNumbers[] = (int) $value;
				}
			}

			// If we have only 2 found numbers, multiply them together and add them to the sum.
			if (2 === count($foundNumbers)) {
				$sum[] = array_product($foundNumbers);
			}
		}

		$this->writeln('The sum is: ' . array_sum($sum));
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
