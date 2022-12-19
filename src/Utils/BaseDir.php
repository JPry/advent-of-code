<?php

namespace JPry\AdventOfCode\Utils;

/**
 * Trait BaseDir
 *
 * @since %VERSION%
 */
trait BaseDir
{
	/**
	 * Get the base directory for the whole project.
	 *
	 * @return string
	 */
	protected function getBaseDir(): string
	{
		static $dir = null;
		if (null === $dir) {
			$dir = dirname(__DIR__, 2);
		}

		return $dir;
	}

	/**
	 * Get the base input directory for the project.
	 *
	 * @return string
	 */
	protected function getInputBaseDir(): string
	{
		return "{$this->getBaseDir()}/input";
	}
}
