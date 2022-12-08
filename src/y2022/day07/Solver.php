<?php
declare(strict_types=1);

namespace JPry\AdventOfCode\y2022\day07;

use Exception;
use JPry\AdventOfCode\DayPuzzle;
use JPry\AdventOfCode\Utils\WalkResource;

class Solver extends DayPuzzle
{
	use WalkResource;

	protected array $structure = [
		'/' => [
			'parent' => '',
			'children' => [],
			'size' => 0,
		]
	];

	protected array $dirStack = [];

	protected string $currentDir = '';

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
					$this->currentDir = '/';
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
						$this->currentDir = $this->structure[$this->currentDir]['parent'];
					} else {
						$this->currentDir = $this->hashDir($dirName);
					}
					return;
				}

				if ($listing) {
					if (str_starts_with($line, 'dir')) {
						// figure out the directory name
						[, $dirName] = explode(' ', $line);

						// hash to prevent duplicate names
						$hashed = $this->hashDir($dirName);
						if (array_key_exists($hashed, $this->structure)) {
							throw new Exception("You're in trouble, duplicate hash found");
						}

						// add to the structure, including the parent hash
						$this->structure[$hashed] = [
							'parent' => $this->currentDir,
							'children' => [],
							'size' => 0,
						];

						$this->structure[$this->currentDir]['children'][] = $dirName;
					} else {
						[$size, $name] = explode(' ', $line);
						$size = (int) $size;
						$this->structure[$this->currentDir]['children'][$name] = $size;
						$this->structure[$this->currentDir]['size'] += $size;
					}
				}
			}
		);

		// Add child directory sizes to all parents.
		foreach ($this->structure as $directory => $data) {
			if ('/' === $directory) {
				continue;
			}

			do {
				$this->structure[$data['parent']]['size'] += $data['size'];
				$current = $data['parent'];
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

	protected function hashDir(string $dirName): string
	{
		$path = str_replace('//', '/', sprintf('/%s', join('/', $this->dirStack)));
		return md5("{$path}/{$dirName}");
	}

	protected function part2Logic($input)
	{

	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
