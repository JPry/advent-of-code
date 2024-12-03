<?php

namespace JPry\AdventOfCode\Command;

use JPry\AdventOfCode\DayPuzzle;
use JPry\AdventOfCode\Utils\DayArgument;
use JPry\AdventOfCode\Utils\YearOption;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Solve
 *
 * @since %VERSION%
 */
class Solve extends Command
{
	use DayArgument;
	use YearOption;

	protected function configure(): void
	{
		$this
			->setName('solve')
			->setDescription('Solve a puzzle')
			->configureDayArgument()
			->addOption(
				'year',
				null,
				InputOption::VALUE_REQUIRED,
				'The year solver to run.',
				date('Y')
			)
			->addOption(
				'test',
				't',
				InputOption::VALUE_NONE,
				'Whether to run the test data'
			);
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$days = $this->normalizeDays($input->getArgument('days'));
		$year = $input->getOption('year');
		$doTests = $input->getOption('test');

		foreach ($days as $day) {
			$className = "{$this->getBaseNamespace()}\\y{$year}\\day{$day}\\Solver";

			/** @var DayPuzzle $class */
			$class = new $className($output);
			$doTests ? $class->runTests() : $class->run();
		}

		return Command::SUCCESS;
	}

	protected function getBaseNamespace(): string
	{
		return 'JPry\\AdventOfCode';
	}
}
