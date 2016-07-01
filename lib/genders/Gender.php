<?php

abstract class Gender {
	public static $LABEL;
	public static $COLOR;
	protected $biasPoints = 0;

	public function adjustScore($score) {
		return $score += $this->biasPoints();
	}
}
