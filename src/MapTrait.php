<?php

namespace JPry\AdventOfCode;

trait MapTrait
{
	protected ?Map $map;

	public function setMap(Map $map)
	{
		$this->map = $map;
	}
}
