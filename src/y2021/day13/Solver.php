<?php


namespace JPry\AdventOfCode\y2021\day13;

use JPry\AdventOfCode\DayPuzzle;
use JPry\AdventOfCode\Point;

class Solver extends DayPuzzle
{
	protected bool $isTest = false;

	public function runTests()
	{
		$this->isTest = true;
		$data = $this->getFileContents('test');
		$this->part1Logic($data);
		$this->part2Logic($data);
	}

	protected function part1()
	{
		$this->part1Logic($this->getFileContents());
	}

	protected function part2()
	{
	}

	protected function part1Logic(string $input)
	{
		$max = [
			'column' => 0,
			'row' => 0,
		];

		[$dots, $folds] = array_map('trim', explode("\n\n", $input));

		// Convert Dots into points
		$dots = array_map(
			function (string $coordinate) use (&$max) {
				$point = Point::fromReversedString($coordinate);
				$max['column'] = max($point->column, $max['column']);
				$max['row'] = max($point->row, $max['row']);

				return $point;
			},
			explode("\n", $dots)
		);

		// Create the empty map where dots will be placed.
		$map = new Manual(
			array_fill(
				0,
				$max['row'] + 1,
				array_fill(0, $max['column'] + 1, ' ')
			)
		);

		// Mark each dot on the map
		/** @var Point $dot */
		foreach ($dots as $dot) {
			$map->markPoint($dot);
		}

		// Convert folds into a set of instructions.
		$folds = array_map(
			function (string $line) {
				[$foldAlong, $value] = explode('=', $line);
				$foldAlong = substr($foldAlong, -1);
				return [
					// x is column (vertical) fold, y is a row (horizontal) fold
					'foldAlong' => 'x' === $foldAlong ? 'Column' : 'Row',
					'value' => intval($value)
				];
			},
			explode("\n", $folds)
		);

		$this->isTest && $map->printDots();

		$i = 0;
		foreach ($folds as $fold) {
			$map->{"foldAlong{$fold['foldAlong']}"}($fold['value']);
			$this->isTest && $map->printDots();
			if (0 === $i) {
				printf("Counted %d dots visible\n", $map->countDots());
			}
			$i++;
		}

		$map->printDots();
	}

	protected function part2Logic($input)
	{

	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
