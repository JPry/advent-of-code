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

	public function getFiles(): array
	{
		$this->maybeFindFiles();
		return $this->files;
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
			$this->files[$info['basename']] = $info;
		}
	}
}
