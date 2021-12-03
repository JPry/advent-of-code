<?php


namespace JPry\AdventOfCode\y2021\day03;

use JPry\AdventOfCode\DayPuzzle;

class Solver extends DayPuzzle
{
	public function runTests()
	{
		$handle = $this->getFilePath('test');
		$this->part1Logic($handle);
		$this->part2Logic($handle);
	}

	protected function part1()
	{
		$this->part1Logic($this->getFilePath('input'));
	}

	protected function part2()
	{
		$this->part2Logic($this->getFilePath('input'));
	}

	protected function part1Logic(string $input)
	{
		$rawData = $this->getRawData($input);
		$mostCommon = $this->getMostCommon($rawData);

		$gamma = bindec(join('', $mostCommon));
		$columnCount = count($mostCommon);
		$epsilon = bindec(substr(sprintf('%b', ~$gamma), -$columnCount));
		printf(
			"Binary: %1\$b\nDecimal (gamma): %1\$d\nInverted binary (epsilon): %2\$b\nInverted (epsilon): %2\$d\n",
			$gamma,
			$epsilon
		);
		printf("Product: %d\n", $gamma * $epsilon);
	}

	protected function part2Logic($input)
	{
		$rawData = $this->getRawData($input);
		$digits = $this->getMostCommon($rawData);
		$columnIndex = 0;

		do {
			$remaining = array_values(
				array_filter(
					$remaining ?? $rawData,
					function (array $value) use ($columnIndex, $digits) {
						return intval($value[$columnIndex]) === intval($digits[$columnIndex]);
					}
				)
			);
			$digits = $this->getMostCommon($remaining);
			$columnIndex++;
		} while (1 < count($remaining));

		$oxygen = bindec(join('', $remaining[0]));
		printf(
			"Oxygen: %b\n",
			$oxygen
		);
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}

	/**
	 * @param array $rawData
	 * @return array
	 */
	protected function getMostCommon(array $rawData): array
	{
		$mostCommon = [];
		foreach (range(0, count($rawData[0]) - 1) as $index) {
			$column = array_column($rawData, $index);
			$total = count($column);
			$columnSum = array_sum($column);
			$mostCommon[$index] = ($columnSum / $total) >= .5 ? 1 : 0;
		}
		return $mostCommon;
	}

	/**
	 * @param string $input
	 * @return array|array[]|false[]
	 */
	protected function getRawData(string $input): array
	{
		return array_map(
			function ($value) {
				return str_split($value);
			},
			file($input, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)
		);
	}
}
