<?php

namespace JPry\AdventOfCode\y2021\day09;

trait MapTrait
{
	protected ?Map $map;

	public function setMap(Map $map)
	{
		$this->map = $map;
	}
}
