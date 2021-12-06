<?php


namespace JPry\AdventOfCode\y2020\day07;

use JPry\AdventOfCode\DayPuzzle;

class Solver extends DayPuzzle
{
	protected $targetColor = 'shiny gold';

	public function runTests()
	{
		$handle = $this->getFilePath('test');
		$this->part1Logic($handle);
		$this->part2Logic($handle);
	}

	protected function part1()
	{
	}

	protected function part2()
	{
	}

	protected function part1Logic($input)
	{
		$rawBags = $this->getRawData($input);
		$opened = [];
		$stack = [];
		$holdsGold = [];
		$targetBag = $rawBags[$this->targetColor];

		// First, eliminate any bags that hold nothing else.
		$bags = array_filter(
			$rawBags,
			function($contents, $color) use ($targetBag) {
				return !empty($contents) && !array_key_exists($color, $targetBag);
			},
			ARRAY_FILTER_USE_BOTH
		);

		$processBag = function ($color, $contents) use ($opened, &$holdsGold, $targetBag, $stack) {
			if (array_key_exists($color, $opened)) {
				return;
			}

			$opened[$color] = 1;
			$stack[] = $color;

			foreach ($contents as $key => $_) {
				if ($this->targetColor === $key) {
					foreach ($stack as $stackColor) {
						$holdsGold[$stackColor] = 1;
					}

				}
			}
		};
	}

	protected function processBag(string $color, array $contents)
	{
	}

	protected function part2Logic($input)
	{
	}

	protected function getRawData(string $input): array
	{
		$lines = file($input, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		$processed = [];

		foreach ($lines as $line) {
			[$color, $contents] = explode(' bags contain ', $line);
			$contains = array_map(
				function ($value) {
					return preg_replace(
						'#^\d+ (.*) bags?\.?#',
						'$1',
						trim($value)
					);
				},
				explode(',', $contents)
			);

			$processed[$color] = 'no other bags.' === trim($contains[0])
				? []
				: array_combine($contains, array_fill(0, count($contains), 1));
		}

		return $processed;
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
