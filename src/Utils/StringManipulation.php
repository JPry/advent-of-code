<?php

namespace JPry\AdventOfCode\Utils;

trait StringManipulation
{
	/**
	 * Convert a string to a keyed array, with the string pieces as the keys.
	 *
	 * @param string $str
	 * @param int $pieceLength The length of each piece
	 * @param mixed $value The value to assign to each element of the array
	 * @return array
	 */
	protected function stringToKeyedArray(string $str, int $pieceLength = 1, mixed $value = 1): array
	{
		return array_fill_keys(str_split($str), $value);
	}
}
