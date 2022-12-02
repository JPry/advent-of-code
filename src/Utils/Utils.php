<?php

namespace JPry\AdventOfCode\Utils;

/**
 * Trait Utils
 *
 * @since %VERSION%
 */
trait Utils
{
	protected function normalizeDay(string $day): string
	{
		return sprintf('%1$02d', intval($day));
	}

	protected function getBaseNamespace(): string
	{
		return 'JPry\\AdventOfCode';
	}
}
