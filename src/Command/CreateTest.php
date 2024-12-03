<?php

namespace JPry\AdventOfCode\Command;

use Exception;
use JPry\AdventOfCode\Utils\BaseDir;
use JPry\AdventOfCode\Utils\DayArgument;
use JPry\AdventOfCode\Utils\YearOption;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateTest extends Command
{
	use DayArgument;
	use YearOption;
	use BaseDir;

	/**
	 * @return void
	 */
	protected function configure()
	{
		$this
			->setName('create:test')
			->setDescription('Create a new Pest test from the template')
			->configureDayArgument()
			->configureYearOption()
			->addOption(
				'with-dataset',
				'd',
				InputOption::VALUE_NONE,
				'Whether to create a dataset for the test',
			);
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$year = (int) $input->getOption('year');
		$createDataset = $input->getOption('with-dataset');

		// Set up the days to create.
		try {
			/** @var array $days */
			$days = $this->normalizeDays($input->getArgument('days'));
		} catch (Exception $e) {
			$output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
			return Command::FAILURE;
		}

		// Ensure the year folder exists.
		$this->maybeCreateYearFolder($year, $output);

		// Create the dataset file if needed.
		$this->maybeCreateDatasetFile($year, $createDataset, $output);

		foreach ($days as $day) {
			$testFile = "{$this->getYearTestsBaseDir($year)}/Day{$day}Test.php";
			if (file_exists($testFile)) {
				$output->writeln(sprintf('<warning>Test file already exists: %s</warning>', $testFile));
				continue;
			}

			$result = file_put_contents(
				$testFile,
				$this->getTestFileContents($year, $day)
			);

			if (false !== $result) {
				$output->writeln(sprintf('<info>Created test file: %s</info>', $testFile));
			} else {
				$output->writeln(sprintf('<error>Failed to create test file: %s</error>', $testFile));
			}

			if ($createDataset) {
				$datasetFile = $this->getDatasetFile($year);
				if (str_contains(file_get_contents($datasetFile), "dataset('day{$day}'")) {
					continue;
				}

				file_put_contents($datasetFile, "dataset('day{$day}', function() {\n\n});\n\n", FILE_APPEND);
			}
		}

		return Command::SUCCESS;
	}

	private function getTestFileContents(mixed $year, mixed $day): string
	{
		return <<<PHP
<?php

declare(strict_types=1);

use JPry\AdventOfCode\y{$year}\day{$day}\Solver;

describe('Part 1', function() {
	it('should get the correct result for test input', function() {
		
	})->todo(note: 'Need to write this test');

	it('should get the result for the input file', function() {
		
	})->todo(note: 'Need to write this test');
});

describe('Part 2', function() {

	it('should get the correct result for the test input', function() {
		
	})->todo(note: 'Need to write this test');

	it('should get the correct result for the real input', function() {
		
	})->todo(note: 'Need to write this test');
});

PHP;
	}

	/**
	 * @param mixed $year
	 * @return void
	 */
	protected function maybeCreateYearFolder(int $year, OutputInterface $output): void
	{
		$yearDir = $this->getYearTestsBaseDir($year);
		if (!file_exists($yearDir)) {
			$result = mkdir($yearDir, 0755, true);
			if ($result) {
				$output->writeln(sprintf('<info>Created year directory: %s</info>', $yearDir));
			} else {
				$output->writeln(sprintf('<error>Failed to create year directory: %s</error>', $yearDir));
			}
		}
	}

	/**
	 * @param mixed $year
	 * @param bool $createDataset
	 * @param OutputInterface $output
	 * @return void
	 */
	protected function maybeCreateDatasetFile(int $year, bool $createDataset, OutputInterface $output): void
	{
		if (!$createDataset) {
			return;
		}

		$datasetFile = $this->getDatasetFile($year);
		if (file_exists($datasetFile)) {
			return;
		}

		file_put_contents($datasetFile, "<?php\n\n");

		$output->writeln(sprintf('<info>Created dataset file: %s</info>', $datasetFile));
	}

	/**
	 * @param mixed $year
	 * @return string
	 */
	protected function getDatasetFile($year): string
	{
		return "{$this->getYearTestsBaseDir($year)}/Dataset.php";
	}
}
