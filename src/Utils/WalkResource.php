<?php

namespace JPry\AdventOfCode\Utils;

trait WalkResource
{
	/**
	 * Walk through a resource line-by-line, calling a callback on each line.
	 *
	 * The line will already have trim() called on it to remove extraneous whitespace. The
	 * resource will have fclose() called after it has finished being read.
	 *
	 * @param $resource
	 * @param callable $callback
	 * @param bool $trim
	 * @return void
	 */
	protected function walkResourceWithCallback($resource, callable $callback, bool $trim = true)
	{
		while (false !== ($line = fgets($resource))) {
			if ($trim) {
				$line = trim($line);
			}
			call_user_func_array($callback, [$line]);
		}

		fclose($resource);
	}
}
