<?php

declare(strict_types=1);

namespace JPry\AdventOfCode\y2022\day07;

use Exception;
use JPry\AdventOfCode\DayPuzzle;
use JPry\AdventOfCode\Utils\WalkResource;

/**
 * Day Solver Class.
 *
 * @link https://adventofcode.com/2022/day/7
 */
class Solver extends DayPuzzle
{
	use WalkResource;

	protected array $s = [
		'root' => [
			'parent' => '',
			'children' => [],
			'size' => 0,
		],
	];

	protected string $cwd = '';

	protected array $stack = [];

	protected int $totalSpace = 70000000;

	protected int $freeSpaceNeeded = 30000000;

	public function runTests()
	{
		$data = $this->getHandleForFile('test');
		$this->part1Logic($data);
		$this->part2Logic();
	}

	protected function part1()
	{
		$this->part1Logic($this->getHandleForFile());
	}

	protected function part2()
	{
		$this->part2Logic();
	}

	protected function part1Logic($input)
	{
		$this->walkResourceWithCallback(
			$input,
			function ($line) {
				if ('$ cd /' === $line) {
					$this->cwd = 'root';
					$this->stack = ['root'];
					return;
				}

				if ('$ ls' === $line) {
					return;
				}

				if (str_starts_with($line, '$ cd')) {
					[, , $dirName] = explode(' ', $line);
					if ('..' === $dirName) {
						// We're done reading this directory, add its size to its parent.
						$parent = $this->s[$this->cwd]['parent'];
						$this->s[$parent]['size'] += $this->s[$this->cwd]['size'];

						// Adjust the directory stack and current directory.
						array_pop($this->stack);
						$this->cwd = $parent;
					} else {
						$this->cwd = $this->getFullPath($dirName);
						$this->stack[] = $dirName;
					}
					return;
				}

				if (str_starts_with($line, 'dir')) {
					// figure out the directory name
					[, $dirName] = explode(' ', $line);

					// hash to prevent duplicate names
					$fullPath = $this->getFullPath($dirName);
					if (array_key_exists($fullPath, $this->s)) {
						throw new Exception("You're in trouble, duplicate path found");
					}

					// add to the structure, including the parent hash
					$this->s[$fullPath] = [
						'parent' => $this->cwd,
						'children' => [],
						'size' => 0,
						'addedChildren' => false,
					];

					$this->s[$this->cwd]['children'][] = $dirName;
				} else {
					[$size, $name] = explode(' ', $line);
					$size = (int) $size;
					$this->s[$this->cwd]['children'][$name] = $size;
					$this->s[$this->cwd]['size'] += $size;
				}
			}
		);

		// Add the final directory to its parent.
		$this->s[$this->s[$this->cwd]['parent']]['size'] += $this->s[$this->cwd]['size'];

		$sizes = array_filter(
			array_column($this->s, 'size'),
			function ($item) {
				return $item <= 100000;
			}
		);

		printf("Largest sizes: %d\n", array_sum($sizes));
	}

	protected function getFullPath(string $dirName): string
	{
		$path = sprintf('/%s', join('/', $this->stack));

		return "{$path}/{$dirName}";
	}

	/**
	 * This assumes part 1 has already been run.
	 *
	 * @return void
	 */
	protected function part2Logic()
	{
		$sizes = array_combine(
			array_keys($this->s),
			array_column($this->s, 'size')
		);

		$totalUsed = $this->s['root']['size'];
		printf("\nSpaced used: %s\n", number_format($totalUsed));

		$freeSpace = $this->totalSpace - $totalUsed;
		printf("Space free: %s\n", number_format($freeSpace));

		$spaceNeeded = $this->freeSpaceNeeded - $freeSpace;
		printf("Space still needed: %s\n", number_format($spaceNeeded));

		$filteredSizes = array_filter(
			$sizes,
			function ($item) use ($spaceNeeded) {
				return $item >= $spaceNeeded;
			}
		);

		printf("The smallest directory able to free enough space is: %d\n", min($filteredSizes));
	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
