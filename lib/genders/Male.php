<?php

require_once('Gender.php');

class Male extends Gender {
	public static $LABEL = 'male';
	public static $COLOR = '#0000ff';
	protected $biasPoints = 2.01;
}
