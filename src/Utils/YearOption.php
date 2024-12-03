<?php

namespace JPry\AdventOfCode\Utils;

use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class YearOption
 *
 * @since %VERSION%
 */
trait YearOption
{

	protected function configureYearOption(): static
	{
		return $this->addOption(
			'year',
			null,
			InputOption::VALUE_REQUIRED,
			'The year solver to run.',
			date('Y')
		);
	}

	/**
	 * Adds an option.
	 *
	 * @param array|string|null $shortcut The shortcuts, can be null, a string of shortcuts delimited by | or an array of shortcuts
	 * @param int|null $mode The option mode: One of the InputOption::VALUE_* constants
	 * @param mixed|null $default The default value (must be null for InputOption::VALUE_NONE)
	 *
	 * @return $this
	 * @throws InvalidArgumentException If option mode is invalid or incompatible
	 *
	 */
	abstract public function addOption(
		string $name,
		array|string $shortcut = null,
		int $mode = null,
		string $description = '',
		mixed $default = null
	): static;
}
