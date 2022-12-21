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

	protected function configureYearOption()
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
	 * @param string|array|null $shortcut The shortcuts, can be null, a string of shortcuts delimited by | or an array of shortcuts
	 * @param int|null          $mode     The option mode: One of the InputOption::VALUE_* constants
	 * @param mixed             $default  The default value (must be null for InputOption::VALUE_NONE)
	 *
	 * @throws InvalidArgumentException If option mode is invalid or incompatible
	 *
	 * @return $this
	 */
	abstract public function addOption(string $name, $shortcut = null, int $mode = null, string $description = '', $default = null);
}
