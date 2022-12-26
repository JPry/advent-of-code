<?php

namespace JPry\AdventOfCode;

abstract class DayPuzzle
{
	/**
	 * @var Input
	 */
	protected ?Input $input;

	public function __construct(?Input $input = null)
	{
		if (null === $input) {
			$location = sprintf(
				'%s/input/%s',
				dirname(__FILE__, 2),
				$this->convertInputNamespace()
			);
			$directory = new Directory($location);
			$input = new Input($directory);
		}

		$this->input = $input;
	}

	public function run()
	{
		$this->part1();
		$this->part2();
	}

	protected function convertInputNamespace()
	{
		$namespace = $this->getNamespace();
		$namespace = str_replace([__NAMESPACE__, '\\'], ['', '/'], $namespace);
		$namespace = preg_replace('#/(\D+)?(\d+)#', '/$2', $namespace);
		$namespace = ltrim($namespace, '/');

		return str_replace('/', DIRECTORY_SEPARATOR, $namespace);
	}

	protected function getHandleForFile(string $filename = 'input', string $mode = 'r')
	{
		return fopen($this->getFilePath($filename), $mode);
	}

	protected function getFilePath(string $filename = 'input'): string
	{
		$file = $this->input->getFile($filename);
		return "{$file['dirname']}/{$file['basename']}";
	}

	public function runTests()
	{
	}

	protected function getFileContents(string $fileName = 'input'): string
	{
		return trim(file_get_contents($this->getFilePath($fileName)));
	}

	protected function getFileAsArray(string $filename = 'input'): array
	{
		return file($this->getFilePath($filename), FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
	}

	protected function stringToNumbers(string $string): array
	{
		return array_map('intval', explode(',', trim($string)));
	}

	/**
	 * Get the contents of a file, split by double new lines.
	 *
	 * @param string $filename
	 * @return array
	 */
	protected function splitFileByDoubleNewLine(string $filename = 'input'): array
	{
		return explode("\n\n", file_get_contents($this->getFilePath($filename)));
	}

	abstract protected function getNamespace();

	abstract protected function part1();

	abstract protected function part2();
}
