<?php

namespace JPry\AdventOfCode;

abstract class DayPuzzle
{
	/**
	 * @var Input
	 */
	protected $input;

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
		$namespace = preg_replace('#/([^\d]+)?(\d+)#', '/$2', $namespace);
		$namespace = ltrim($namespace, '/');
		$namespace = str_replace('/', DIRECTORY_SEPARATOR, $namespace);
		return $namespace;
	}

	protected function getHandleForFile(string $filename, string $mode = 'r')
	{
		$file = $this->input->getFile($filename);
		return fopen("{$file['dirname']}/{$file['basename']}", $mode);
	}

	abstract protected function getNamespace();
	abstract protected function part1();
	abstract protected function part2();
}
