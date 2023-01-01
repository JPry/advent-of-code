<?php

namespace JPry\AdventOfCode;

use InvalidArgumentException;

class Input
{
	private $directory;
	private $files = null;

	/**
	 * Input constructor.
	 * @param $directory
	 */
	public function __construct(Directory $directory)
	{
		$this->directory = $directory;
	}

	public function getFile(string $basename): array
	{
		$this->maybeFindFiles();
		if (!isset($this->files[$basename])) {
			throw new InvalidArgumentException(
				sprintf('%s was not found in %s', $basename, $this->directory->getDirectory())
			);
		}

		return $this->files[$basename];
	}

	/**
	 * Create a new file and return its full path.
	 *
	 * @param string $fileName
	 * @return string
	 */
	public function createFile(string $fileName): string
	{
		$info = pathinfo($fileName);
		$name = $info['filename'];
		$extension = $info['extension'];
		$basename = $info['basename'];

		$this->maybeFindFiles();
		if (array_key_exists($name, $this->files)) {
			return "{$this->files[$name]['dirname']}/{$this->files[$name]['basename']}";
		}

		touch("{$this->directory->getDirectory()}/{$basename}");
		$this->files[$name] = [
			'basename' => $basename,
			'dirname' => $this->directory->getDirectory(),
			'extension' => $extension,
			'filename' => $name,
		];

		return "{$this->files[$name]['dirname']}/{$this->files[$name]['basename']}";
	}

	private function maybeFindFiles()
	{
		if (null === $this->files) {
			$this->findFiles();
		}
	}

	private function findFiles()
	{
		$this->files = [];
		$files = glob("{$this->directory->getDirectory()}/*");
		if (false === $files) {
			return;
		}

		foreach ($files as $file) {
			if (is_dir($file)) {
				continue;
			}

			$info = pathinfo($file);
			$this->files[$info['filename']] = $info;
		}
	}
}
