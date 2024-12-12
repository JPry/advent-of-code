<?php

namespace JPry\AdventOfCode;

use AllowDynamicProperties;
use Symfony\Component\Console\Output\OutputInterface;

#[AllowDynamicProperties]
abstract class DayPuzzle
{
	/**
	 * @var Input
	 */
	protected ?Input $input;

	protected ?OutputInterface $output;

	public function __construct(?OutputInterface $output = null, ?Input $input = null)
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
		$this->output = $output;
	}

	protected function writeln(...$messages)
	{
		if ($this->output instanceof OutputInterface) {
			$this->output->writeln(...$messages);
		} else {
			foreach ($messages as $message) {
				echo $message . PHP_EOL;
			}
		}
	}

	public function run()
	{
		$this->part1();
		$this->part2();
	}

	public function returnPart1()
	{
		return $this->part1();
	}

	public function returnPart2()
	{
		return $this->part2();
	}

	public function returnTest1($input = null, $filename = 'test')
	{
		return $this->part1Logic($this->getTestInputArray($input, $filename));
	}

	public function returnTest2($input = null, $filename = 'test')
	{
		return $this->part2Logic($this->getTestInputArray($input, $filename));
	}

	protected function getTestInputArray($input = null, $filename = 'test')
	{
		return $input ?? $this->getFileAsArray($filename);
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

	public function getFileContents(string $fileName = 'input'): string
	{
		return trim(file_get_contents($this->getFilePath($fileName)));
	}

	public function getFileAsArray(string $filename = 'input'): array
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
	abstract protected function part1Logic($input);
	abstract protected function part2Logic($input);
}
