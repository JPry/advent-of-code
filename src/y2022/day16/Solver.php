<?php
declare(strict_types=1);

namespace JPry\AdventOfCode\y2022\day16;

use JPry\AdventOfCode\DayPuzzle;
use JPry\AdventOfCode\Utils\WalkResource;

class Solver extends DayPuzzle
{
	use WalkResource;

	/** @var int[] */
	protected array $flowRates = [];

	public function runTests()
	{
		$data = $this->getHandleForFile('test');
		$this->part1Logic($data);
		$this->part2Logic($data);
	}

	protected function part1()
	{
	}

	protected function part2()
	{
	}

	/**
	 * @param resource $input
	 * @return void
	 */
	protected function part1Logic($input)
	{
		/** @var Valve[] $valves */
		$valves = [];
		$tunnels = [];
		$this->walkResourceWithCallback(
			$input,
			function($line) use (&$valves, &$tunnels) {
				[$v, $t] = explode('; ', $line);
				preg_match('#Valve\s(\w+).*=(\d+)$#', $v, $matches);
				$id = $matches[1];
				$flowRate = (int) $matches[2];
				$this->flowRates[] = $flowRate;
				$valves[$id] = new Valve($flowRate);

				preg_match('#tunnels? leads? to valves? (.*)$#', $t, $matches);
				$tunnels[$id] = explode(', ', $matches[1]);
			}
		);

		// Unique and sort flow rates.
//		$this->flowRates = array_unique($this->flowRates);
		sort($this->flowRates);
		$middle2 = (int) floor(count($this->flowRates) / 2);
		$middle1 = $middle2 - 1;

		$medianFlow = (int) floor(($this->flowRates[$middle1] + $this->flowRates[$middle2]) / 2);

		$currentValve = 'AA';
		$minutesLeft = 30;
		$moveTo = $this->determineMoveTo();
		$nextAction = 'move';
		$maxFlow = array_pop($this->flowRates);
		do {
			$current = $valves[$currentValve];
			switch ($nextAction) {
				case 'move':
					$currentValve = $moveTo;
					if ($this->shouldOpenValve($current)) {
						$moveTo = $this->determineMoveTo();
					} else {
						$nextAction = 'open';
					}
					break;

				case 'open':
					$current->open($minutesLeft);
					$nextAction = 'move';
					$moveTo = $this->determineMoveTo();
					$maxFlow = count($this->flowRates) > 0 ? array_pop($this->flowRates) : 0;
					break;

				default:
					// Just countdown
					break;
			}

			$minutesLeft--;
		} while ($minutesLeft > 0);

		$pressureReleased = array_reduce(
			$valves,
			/**
			 * @param int $carry
			 * @param Valve $valve
			 * @return int
			 */
			function(int $carry, Valve $valve) {
				return $carry += $valve->getTotalPressureReleased();
			},
			0
		);

		printf("Total pressure released: %d\n", $pressureReleased);
	}

	protected function determineMoveTo(): string
	{
		static $key = 0;
		static $order = [
			'DD',
			'CC',
			'BB',
			'AA',
			'II',
			'JJ',
			'II',
			'AA',
			'DD',
			'EE',
			'FF',
			'GG',
			'HH',
			'GG',
			'FF',
			'EE',
			'DD',
			'CC',
		];

		$return = $order[$key] ?? '';
		$key++;

		return $return;
	}

	protected function shouldOpenValve(Valve $valve): bool
	{
		// Can't open a valve that's already open.
		if ($valve->isOpen()) {
			return false;
		}

		// No point in opening a valve with a flow rate of 0.
		if ($valve->getFlowRate() === 0) {
			return false;
		}

		// Here's where there needs to be some other logic around whether to open or move on
		// Todo: logic

		return true;
	}

	protected function part2Logic($input)
	{

	}

	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}
