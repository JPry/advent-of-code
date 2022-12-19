<?php

namespace JPry\AdventOfCode\Command;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Message;
use JPry\AdventOfCode\Utils\BaseDir;
use JPry\AdventOfCode\Utils\Utils;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Dotenv\Dotenv;

/**
 * Class DownloadInput
 */
class DownloadInput extends Command
{
	use BaseDir;
	use Utils;

	/**
	 * Configures the current command.
	 */
	protected function configure()
	{
		$this
			->setName('input:download')
			->setAliases(['input'])
			->setDescription('Download input file(s) directly from Advent of Code')
			->addArgument(
				'days',
				InputArgument::IS_ARRAY,
				'The day(s) of input to obtain. Cannot obtain future days',
				[date('j')]
			)
			->addOption(
				'year',
				null,
				InputOption::VALUE_REQUIRED,
				'The year to obtain. Defaults to the current year.',
				date('Y')
			);
	}

	/**
	 * Executes the current command.
	 *
	 * This method is not abstract because you can use this class
	 * as a concrete class. In this case, instead of defining the
	 * execute() method, you set the code to execute by passing
	 * a Closure to the setCode() method.
	 *
	 * @return int 0 if everything went fine, or an exit code
	 *
	 * @throws GuzzleException When there is a problem with the request.
	 * @see setCode()
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$days = $this->normalizeDays($input->getArgument('days'));
		$year = $input->getOption('year');

		$client = new Client(
			[
				'base_uri' => 'https://adventofcode.com',
				'cookies' => new CookieJar(true, $this->getCookies()),
			]
		);

		$errors = 0;
		foreach ($days as $day) {
			$inputPath = "{$this->getInputBaseDir()}/{$year}/{$day}/input.txt";

			// If the file exists and isn't empty, assume it's got the correct content.
			if (file_exists($inputPath) && strlen(trim(file_get_contents($inputPath))) > 0) {
				$output->writeln(sprintf('<info>Input found for day %s, skipping...</info>', $day));
				continue;
			}

			// Download and save the input data.
			try {
				$intDay = (int)$day;
				$url = "/{$year}/day/{$intDay}/input";
				$response = $client->get($url);

				file_put_contents($inputPath, $response->getBody());
				$output->writeln(sprintf('<info>Added content to input file: %s</info>', $inputPath));
			} catch (ClientException $e) {
				$errors++;
				$output->writeln(sprintf("<info>Request:\n%s</info>", Message::toString($e->getRequest())));
				$output->writeln(sprintf("<error>Client error:\n%s</error>", Message::toString($e->getResponse())));
			} catch (ConnectException $e) {
				$errors++;
				$output->writeln(sprintf("<info>Request:\n%s</info>", Message::toString($e->getRequest())));
				$output->writeln(sprintf("<error>Networking error:\n%s</error>", $e->getMessage()));
			} catch (ServerException $e) {
				$errors++;
				$output->writeln(sprintf("<info>Request:\n%s</info>", Message::toString($e->getRequest())));
				$output->writeln(sprintf("<error>Server error:\n%s</error>", Message::toString($e->getResponse())));
			}
		}

		return $errors > 0 ? Command::FAILURE : Command::SUCCESS;
	}

	protected function getCookies(): array
	{
		$cookies = [];

		$cookies[] = new SetCookie(
			[
				'Domain' => '.adventofcode.com',
				'Name' => 'session',
				'Value' => getenv('AOC_SESSION'),
				'Secure' => true,
				'HttpOnly' => true,
			]
		);

		return $cookies;
	}
}
