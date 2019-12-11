<?php

namespace JPry\AdventOfCode;

use InvalidArgumentException;

class Directory
{
	private $location = '';

	/**
	 * Input constructor.
	 * @param string $location
	 * @throws InvalidArgumentException When the directory is invalid.
	 */
	public function __construct(string $location)
	{
		$location = realpath($location);
		if (!file_exists($location) || !is_dir($location)) {
			throw new InvalidArgumentException(sprintf('%s is not a valid directory', $location));
		}

		$this->location = $location;
	}

	public function getDirectory()
	{
		return $this->location;
	}
}
