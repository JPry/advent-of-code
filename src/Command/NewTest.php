<?php

namespace JPry\AdventOfCode\Command;

use LogicException;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use JPry\AdventOfCode\Utils\Utils;

/**
 * Class NewTest
 *
 * @since %VERSION%
 */
class NewTest extends Command
{
	use Utils;

	/** @var string */
	private $baseDir;

	/**
	 * @param string|null $name The name of the command; passing null means it must be set in configure()
	 *
	 * @throws LogicException When the command name is empty
	 */
	public function __construct(string $name = null)
	{
		$this->baseDir = dirname(__DIR__, 2);
		parent::__construct($name);
	}

	protected function configure()
	{
		$this
			->setName('new')
			->setDescription('Create a new test from the template')
			->addArgument(
				'days',
				InputArgument::IS_ARRAY,
				'The day solver to run'
			)
			->addOption(
				'year',
				null,
				InputOption::VALUE_REQUIRED,
				'The year solver to run.',
				date('Y')
			)
			->addOption(
				'new-year',
				null,
				InputOption::VALUE_NONE,
				'Whether to generate a new year of puzzles'
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
		/** @var array $days */
		$days = $input->getArgument('days');
		if (empty($days)) {
			$output->writeln('<error>Not enough arguments (missing: "days").</error>');
			return Command::FAILURE;
		}

		// Check to see if days was entered as a range.
		if (count($days) === 1 && false !== strpos($days[0], '..')) {
			preg_match('#(\d+)\.\.(\d+)#', $days[0], $matches);
			if (isset($matches[1], $matches[2])) {
				$days = range(...array_slice($matches, 1, 2));
			}
		}

		$days = array_map(
			function($value) {
				if ((int) $value > 25) {
					throw new RuntimeException(sprintf("One of the day values was too high. Found %s", $value));
				}
				return $this->normalizeDay($value);
			},
			$days
		);

		foreach ($days as $day) {
			// Create the input files.
			$inputBase = "{$this->baseDir}/input/{$year}/{$day}";
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
				$contents = file_get_contents($file);
				$newPath = "{$dayPath}/{$base}";
				file_put_contents(
					$newPath,
					str_replace('\\template', "\\day{$day}", $contents)
				);
				$output->writeln(sprintf('<info>Created file:            %s', $newPath));
			}

			$output->writeln('');
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
