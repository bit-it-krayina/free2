<?php
/**
 * Given a log priority number or constant found in Zend Logger, this view helper will
 * return a bootstrap like label string
 */

namespace NetglueLog\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Log\Logger as Log;

class LogLevelLabel extends AbstractHelper {
	
	
/*
EMERG  = 0;
ALERT  = 1;
CRIT   = 2;
ERR    = 3;
WARN   = 4;
NOTICE = 5;
INFO   = 6;
DEBUG  = 7;
*/
	
	/**
	 * Map priority numbers to words
	 * @var array
	 */
	protected $levelMap = array(
		0 => 'Emergency',
		1 => 'Alert',
		2 => 'Critical',
		3 => 'Error',
		4 => 'Warning',
		5 => 'Notice',
		6 => 'Info',
		7 => 'Debug',
	);
	
	/**
	 * Map priority numbers to class names
	 * @var array
	 */
	protected $classMap = array(
		0 => 'label-important',
		1 => 'label-important',
		2 => 'label-important',
		3 => 'label-important',
		4 => 'label-warning',
		5 => 'label-info',
		6 => 'label-inverse',
		7 => '',
	);
	
	/**
	 * Html string for the label with numbered sprintf parameters
	 * @var string
	 */
	protected $format = '<span class="label %1$s">%2$s</span>';
	
	/**
	 * @param int|string $level
	 * @return string
	 */
	public function __invoke($level) {
		if(!is_numeric($level)) {
			$level = $this->toNumber($level);
		} else {
			$level = (int) $level;
		}
		$class = '';
		$name = 'Unknown';
		if(array_key_exists($level, $this->classMap)) {
			$class = $this->classMap[$level];
		}
		if(array_key_exists($level, $this->levelMap)) {
			$name = $this->levelMap[$level];
		}
		return sprintf($this->format, $class, $name);
	}
	
	/**
	 * Try to turn a priority constant string into a priority number
	 * @param string $level
	 * @return int|false
	 */
	public function toNumber($level) {
		$level = strtoupper($level);
		$num = @constant("Log::{$level}");
		if(is_int($num)) {
			return $num;
		}
		return false;
	}
	
	/**
	 * Provide a different format for the html output suitable for sprintf
	 * @param string $format
	 * @return LogLevelLabel $this
	 */
	public function setFormat($format) {
		$this->format = $format;
		return $this;
	}
	
	/**
	 * Return format
	 * @return string
	 */
	public function getFormat() {
		return $this->format;
	}
	
	/**
	 * Set an array to map log priorities to words to display
	 * @param array $map
	 * @return LogLevelLabel $this
	 */
	public function setLevelMap(array $map) {
		$this->levelMap = $map;
		return $this;
	}
	
	/**
	 * Get the current priority word map
	 * @return array
	 */
	public function getLevelMap() {
		return $this->levelMap;
	}
	
	/**
	 * Set the CSS class priority map
	 * @param array $map
	 * @return LogLevelLabel $this
	 */
	public function setClassMap(array $map) {
		$this->classMap = $map;
		return $this;
	}
	
	/**
	 * Get CSS Class to priority map
	 * @return array
	 */
	public function getClassMap() {
		return $this->classMap;
	}
}