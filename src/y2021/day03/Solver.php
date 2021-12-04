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

	protected function part2Logic(string $input)
	{
		$rawData = $this->getRawData($input);
		$mostCommon = $this->getMostCommon($rawData);
		$leastCommon = $this->getLeastCommon($rawData);
		$columnIndex = 0;

		// Find the most common items for Oxygen.
		do {
			$oxygenValue = array_values(
				array_filter(
					$oxygenValue ?? $rawData,
					function (array $value) use ($columnIndex, $mostCommon) {
						return intval($value[$columnIndex]) === intval($mostCommon[$columnIndex]);
					}
				)
			);
			$mostCommon = $this->getMostCommon($oxygenValue);
			$columnIndex++;
		} while (1 < count($oxygenValue));

		// Find least common items for CO2.
		$columnIndex = 0;
		do {
			$co2value = array_values(
				array_filter(
					$co2value ?? $rawData,
					function (array $value) use ($columnIndex, $leastCommon) {
						return intval($value[$columnIndex]) === intval($leastCommon[$columnIndex]);
					}
				)
			);
			$leastCommon = $this->getLeastCommon($co2value);
			$columnIndex++;
		} while (1 < count($co2value));

		$oxygen = bindec(join('', $oxygenValue[0]));
		printf(
			"Oxygen (binary): %1\$b\nOxygen: %1\$d\n",
			$oxygen
		);

		$co2 = bindec(join('', $co2value[0]));
		printf(
			"CO2 (binary): %1\$b\nCO2: %1\$d\n",
			$co2
		);

		printf("Product: %d\n", $oxygen * $co2);
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
		return $this->getCommon($rawData, 'most');
	}

	protected function getLeastCommon(array $rawData): array
	{
		return $this->getCommon($rawData, 'least');
	}

	protected function getCommon(array $rawData, string $comparator): array
	{
		$common = [];
		foreach (range(0, count($rawData[0]) - 1) as $index) {
			$column = array_column($rawData, $index);
			$total = count($column);
			$columnSum = array_sum($column);

			switch ($comparator) {
				case 'least':
				case '<=':
					$common[$index] = ($columnSum / $total) >= .5 ? 0 : 1;
					break;

				case 'most':
				case '>=':
					$common[$index] = ($columnSum / $total) >= .5 ? 1 : 0;
					break;
			}
		}

		return $common;
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
