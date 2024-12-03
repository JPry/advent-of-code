<?php

namespace JPry\AdventOfCode\Utils;

use DateTimeImmutable as DTI;
use DateTimeZone as DTZ;
use RuntimeException;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Trait DayArgument
 *
 * @since %VERSION%
 */
trait DayArgument
{
	protected function configureDayArgument(): static
	{
		return $this->addArgument(
			'days',
			InputArgument::IS_ARRAY,
			'The day(s) of input to obtain. Cannot obtain future days',
			[(new DTI('now', new DTZ('America/Chicago')))->format('j')]
		);
	}

	/**
	 * Adds an argument.
	 *
	 * @param ?int $mode The argument mode: InputArgument::REQUIRED or InputArgument::OPTIONAL
	 * @param mixed $default The default value (for InputArgument::OPTIONAL mode only)
	 *
	 * @return $this
	 * @throws InvalidArgumentException When argument mode is not valid
	 *
	 */
	abstract public function addArgument(
		string $name,
		int $mode = null,
		string $description = '',
		mixed $default = null
	): static;

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

	/**
	 * Normalize an array of days into an array of string-ified days.
	 *
	 * @param array $days
	 * @param int $maxValue
	 *
	 * @return string[]
	 * @throws RuntimeException When days is empty, or when one of the days is higher than the max value.
	 */
	protected function normalizeDays(array $days, int $maxValue = 25): array
	{
		if (empty($days)) {
			throw new RuntimeException('Not enough arguments (missing: "days").');
		}

		// Check to see if days was entered as a range.
		if (count($days) === 1 && str_contains($days[0], '..')) {
			preg_match('#(\d+)\.\.(\d+)#', $days[0], $matches);
			if (isset($matches[1], $matches[2])) {
				$days = range(...array_slice($matches, 1, 2));
			}
		}

		return array_map(
			function ($value) use ($maxValue) {
				if ((int) $value > $maxValue) {
					throw new RuntimeException(sprintf("One of the day values was too high. Found %s", $value));
				}
				return $this->normalizeDay($value);
			},
			$days
		);
	}
}
