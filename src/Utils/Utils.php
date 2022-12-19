<?php

namespace JPry\AdventOfCode\Utils;

use RuntimeException;

/**
 * Trait Utils
 *
 * @since %VERSION%
 */
trait Utils
{
	/**
	 * Normalize a day number to include leading zeros for numbers less than 10.
	 *
	 * @param string $day
	 * @return string
	 */
	protected function normalizeDay(string $day): string
	{
		return sprintf('%1$02d', intval($day));
	}

	protected function getBaseNamespace(): string
	{
		return 'JPry\\AdventOfCode';
	}

	/**
	 * Normalize an array of days into an array of string-ified days.
	 *
	 * @param array $days
	 * @param int $maxValue
	 *
	 * @throws RuntimeException When days is empty, or when one of the days is higher than the max value.
	 * @return string[]
	 */
	protected function normalizeDays(array $days, int $maxValue = 25): array
	{
		if (empty($days)) {
			throw new RuntimeException('Not enough arguments (missing: "days").');
		}

		// Check to see if days was entered as a range.
		if (count($days) === 1 && false !== strpos($days[0], '..')) {
			preg_match('#(\d+)\.\.(\d+)#', $days[0], $matches);
			if (isset($matches[1], $matches[2])) {
				$days = range(...array_slice($matches, 1, 2));
			}
		}

		return array_map(
			function($value) use ($maxValue) {
				if ((int) $value > $maxValue) {
					throw new RuntimeException(sprintf("One of the day values was too high. Found %s", $value));
				}
				return $this->normalizeDay($value);
			},
			$days
		);
	}
}
