<?php

namespace JPry\AdventOfCode\y2022\day19;

use Exception;

/**
 * Trait ValidTypes
 *
 * @since %VERSION%
 */
trait ValidTypes
{
	protected array $validTypes = [
		'ore'      => 1,
		'clay'     => 1,
		'obsidian' => 1,
	];

	private function validateTypes(array $types)
	{
		if (count(array_diff_key($types, $this->validTypes)) > 0) {
			throw new Exception('Invalid type');
		}
	}

	private function validateType(string $type) {
		$this->validateTypes([$type => 1]);
	}
}
