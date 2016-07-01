<?php

require_once('Gender.php');

class Female extends Gender {
	public static $LABEL = 'female';
	public static $COLOR = '#f7b5ef';
	protected $biasPoints = 0;
}
