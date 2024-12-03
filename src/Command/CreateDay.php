<?php

namespace JPry\AdventOfCode\Command;

use Exception;
use JPry\AdventOfCode\Utils\BaseDir;
use JPry\AdventOfCode\Utils\DayArgument;
use JPry\AdventOfCode\Utils\YearOption;
use LogicException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateDay
 *
 * @since %VERSION%
 */
class CreateDay extends Command
{
	use BaseDir;
	use DayArgument;
	use YearOption;

	/** @var string */
	private string $baseDir;

	/**
	 * @param string|null $name The name of the command; passing null means it must be set in configure()
	 *
	 * @throws LogicException When the command name is empty
	 */
	public function __construct(string $name = null)
	{
		$this->baseDir = $this->getBaseDir();
		parent::__construct($name);
	}

	protected function configure(): void
	{
		$this
			->setName('new')
			->setDescription('Create a new test from the template')
			->configureDayArgument()
			->configureYearOption()
			->addOption(
				'new-year',
				null,
				InputOption::VALUE_NONE,
				'Whether to generate a new year of puzzles'
			)
			->addOption(
				'download',
				'd',
				InputOption::VALUE_NONE,
				'Whether to download the input data (if available).'
			);
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		// Determine if we need to create a new year first.
		$year = $input->getOption('year');
		if ($input->getOption('new-year')) {
			$this->createNewYear($year, $output);
		}

		// Set up the days to create.
		try {
			/** @var array $days */
			$days = $this->normalizeDays($input->getArgument('days'));
		} catch (Exception $e) {
			$output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
			return Command::FAILURE;
		}

		foreach ($days as $day) {
			// Create the input files.
			$inputBase = "{$this->getInputBaseDir()}/{$year}/{$day}";
			if (!file_exists($inputBase)) {
				mkdir($inputBase);
				$output->writeln(sprintf('<info>Created input directory: %s</info>', $inputBase));
			}

			touch("{$inputBase}/input.txt");
			touch("{$inputBase}/test.txt");

			// Create the solution files.
			$templatePath = "{$this->baseDir}/src/y{$year}/template";
			$dayPath = "{$this->baseDir}/src/y{$year}/day{$day}";

			if (!file_exists($dayPath)) {
				mkdir($dayPath);
				$output->writeln(sprintf('<info>Created day files:       %s</info>', $dayPath));
			}

			foreach (glob("{$templatePath}/*.php") as $file) {
				$base = basename($file);
				$newPath = "{$dayPath}/{$base}";
				if (file_exists($newPath)) {
					$output->writeln(sprintf('<info>File "%s" exists, skipping...</info>', $base));
					continue;
				}

				$contents = file_get_contents($file);
				file_put_contents(
					$newPath,
					str_replace(['\\template', '%DAY%'], ["\\day{$day}", $day], $contents)
				);
				$output->writeln(sprintf('<info>Created file:            %s', $newPath));
			}

			$output->writeln('');
		}

		if ($input->getOption('download')) {
			$command = $this->getApplication()->get('input:download');
			$command->run(
				new ArrayInput(
					[
						'days' => $days,
						'--year' => $year,
					]
				),
				$output
			);
		}

		if ($input->getOption('add-tests')) {
			$command = $this->getApplication()->get('create:test');
			$command->run(
				new ArrayInput(
					[
						'days' => $days,
						'--year' => $year,
					]
				),
				$output
			);
		}

		return Command::SUCCESS;
	}

	private function createNewYear(string $year, OutputInterface $output)
	{
		// Create the directories
		$directories = [
			'input' => "{$this->baseDir}/input/{$year}",
			'year' => "{$this->baseDir}/src/y{$year}",
			'template' => "{$this->baseDir}/src/y{$year}/template",
		];

		foreach ($directories as $key => $directory) {
			if (!file_exists($directory)) {
				mkdir($directory);
				$output->writeln(sprintf('<info>Created %s directory: %s</info>', $key, $directory));
			}
		}

		// Create the template file.
		$templateFile = "{$directories['template']}/Solver.php";
		file_put_contents(
			$templateFile,
			<<< PHP
<?php
declare(strict_types=1);

namespace JPry\\AdventOfCode\\y{$year}\\template;

use JPry\\AdventOfCode\\DayPuzzle;

/**
 * Day Solver Class.
 *
 * @link https://adventofcode.com/{$year}/day/%DAY%
 */
class Solver extends DayPuzzle
{
	public function runTests()
	{
		\$data = \$this->getFileAsArray('test');
		\$this->part1Logic(\$data);
		\$this->part2Logic(\$data);
	}

	protected function part1()
	{
	}

	protected function part2()
	{
	}

	protected function part1Logic(\$input)
	{

	}

	protected function part2Logic(\$input)
	{

	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
PHP
		);

		$output->writeln(sprintf('<info>Created template file: %s</info>', $templateFile));
	}
}
