<?php
declare(strict_types=1);

namespace JPry\AdventOfCode\y2022\day07;

use Elastic\Apm\CustomErrorData;
use Exception;
use JPry\AdventOfCode\DayPuzzle;
use JPry\AdventOfCode\Utils\WalkResource;

class Solver extends DayPuzzle
{
	use WalkResource;

	protected array $structure = [
		'/' => [
			'children' => [],
			'size' => 0,
		]
	];

	protected array $dirStack = [];

	public function runTests()
	{
		$data = $this->getHandleForFile('test');
		$this->part1Logic($data);
		$this->part2Logic($data);
	}

	protected function part1()
	{
		$this->part1Logic($this->getHandleForFile('input'));
	}

	protected function part2()
	{
	}

	protected function part1Logic($input)
	{
		$listing = false;
		$this->walkResourceWithCallback(
			$input,
			function($line) use (&$listing) {
				if (str_starts_with($line, '$')) {
					$listing = false;
				}

				if ('$ cd /' === $line) {
					$this->dirStack = [];
					return;
				}

				if ('$ ls' === $line) {
					$listing = true;
					return;
				}

				if (str_starts_with($line, '$ cd')) {
					[,, $dirName] = explode(' ', $line);
					if ('..' === $dirName) {
						array_pop($this->dirStack);
					} else {
						$this->dirStack[] = $dirName;
					}
					return;
				}

				if ($listing) {
					$currentDir = '/' . join('/', $this->dirStack);
					$currentDir = str_replace('//', '/', $currentDir);
					if (str_starts_with($line, 'dir')) {
						[, $dirName] = explode(' ', $line);
						if (array_key_exists($dirName, $this->structure)) {
							throw new Exception("You're in trouble, duplicate dir name found");
						}

						$newDir = str_replace('//', '/', "{$currentDir}/{$dirName}");
						$this->structure[$newDir] = [
							'children' => [],
							'size' => 0,
						];
						$this->structure[$currentDir]['children'][] = $dirName;
					} else {
						[$size, $name] = explode(' ', $line);
						$size = (int) $size;
						$this->structure[$currentDir]['children'][$name] = $size;
						$this->structure[$currentDir]['size'] += $size;
					}
				}
			}
		);

		// Add child directory sizes to all parents.
		foreach ($this->structure as $directory => $data) {
			if ('/' === $directory) {
				continue;
			}

			$directoryPieces = explode('/', $directory);
			array_pop($directoryPieces);
			$current = join('/', $directoryPieces);
			$current = $current ?: '/';

			do {
				$this->structure[$current]['size'] += $data['size'];

				array_pop($directoryPieces);
				$current = join('/', $directoryPieces);
				$current = $current ?: '/';

				$data = $this->structure[$current];
			} while ('/' !== $current);
		}

		$sizes = [];
		foreach ($this->structure as $directory => $data) {
			$sizes[$directory] = $data['size'];
		}

		$sizes = array_filter(
			$sizes,
			function($item) {
				return $item < 100000;
			}
		);

		printf("Largest sizes: %d\n", array_sum($sizes));
	}

	protected function addDir(string $dirName)
	{

	}

	protected function part2Logic($input)
	{

	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
