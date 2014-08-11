<?php

namespace NetglueLog\Log\Formatter;

use Zend\Log\Formatter\FormatterInterface;
use Zend\Log\Formatter\Base as BaseFormatter;

use DateTime;
use Traversable;

class Db extends BaseFormatter implements FormatterInterface {
	
	/**
	 * Date format for events
	 * @var string
	 */
	protected $dateFormat = 'U';
	
	/**
	 * DB Table column mapping
	 * @var array|NULL
	 */
	protected $columnMap;
	
	/**
	 * Formats data into a single line to be written by the writer.
	 *
	 * @param array $event event data
	 * @return string formatted line to write to the log
	 */
	public function format($event) {
		/**
		 * Flatten the array to fields acceptable to database
		 */
		$event = $this->toColumns($event);
		/**
		 * We can do this because when Zend\Log\Formatter\Base calls format recursively,
		 * it uses self::format() instead of $this->format()
		 */
		
		/**
		 * We need to make sure that 'extra' is removed for the base formatter,
		 * it is expecting an array and will receive a scalar if set at all
		 */
		if(isset($event['extra'])) {
			$x = $event['extra'];
			unset($event['extra']);
		}
		$event = parent::format($event);
		// Restore 'extra'
		if(isset($x)) {
			$event['extra'] = $x;
		}
		return $event;
	}
	
	/**
	 * Flatten the event data with keys that map to the db column names configured
	 * @param array $data
	 * @return array
	 * @throws \RuntimeException if no column mappings have been provided
	 */
	protected function toColumns(array $data) {
		$map = $this->getColumnMap();
		if(!is_array($map)) {
			throw new \RuntimeException('Column mappings have not been provided to the log formatter');
		}
		$out = array();
		
		if(isset($map['timestampFieldName']) && isset($data['timestamp'])) {
			$out[$map['timestampFieldName']] = $data['timestamp'];
		}
		
		if(isset($map['priorityNameFieldName']) && isset($data['priorityName'])) {
			$out[$map['priorityNameFieldName']] = $data['priorityName'];
		}
		
		if(isset($map['priorityFieldName']) && isset($data['priority'])) {
			$out[$map['priorityFieldName']] = $data['priority'];
		}
		
		if(isset($map['messageFieldName']) && isset($data['message'])) {
			$out[$map['messageFieldName']] = $data['message'];
		}
		
		if(isset($map['logTypeFieldName']) && isset($data['extra']['event_type'])) {
			$out[$map['logTypeFieldName']] = $data['extra']['event_type'];
			unset($data['extra']['event_type']);
		}
		
		if(isset($map['errorCodeFieldName'])) {
			foreach(array('errno', 'code') as $codeKey) {
				if(isset($data['extra'][$codeKey])) {
					$out[$map['errorCodeFieldName']] = $data['extra'][$codeKey];
					unset($data['extra'][$codeKey]);
				}
			}
		}
		
		if(isset($map['exceptionClassFieldName']) && isset($data['extra']['class'])) {
			$out[$map['exceptionClassFieldName']] = $data['extra']['class'];
			unset($data['extra']['class']);
		}
		
		if(isset($map['filePathFieldName']) && isset($data['extra']['file'])) {
			$out[$map['filePathFieldName']] = $data['extra']['file'];
			unset($data['extra']['file']);
		}
		
		if(isset($map['lineFieldName']) && isset($data['extra']['line'])) {
			$out[$map['lineFieldName']] = $data['extra']['line'];
			unset($data['extra']['line']);
		}
		
		if(isset($map['functionFieldName']) && isset($data['extra']['function'])) {
			$out[$map['functionFieldName']] = $data['extra']['function'];
			unset($data['extra']['function']);
		}
		
		if(isset($map['traceFieldName']) && isset($data['extra']['trace'])) {
			$out[$map['traceFieldName']] = json_encode($data['extra']['trace']);
			unset($data['extra']['trace']);
		}
		
		if(isset($map['ipAddressFieldName']) && isset($data['extra']['ip'])) {
			$out[$map['ipAddressFieldName']] = $data['extra']['ip'];
			unset($data['extra']['ip']);
		}
		
		if(isset($map['requestIdFieldName']) && isset($data['extra']['requestId'])) {
			$out[$map['requestIdFieldName']] = $data['extra']['requestId'];
			unset($data['extra']['requestId']);
		}
		
		/**
		 * This turns anything left in 'extra' into a json string
		 */
		if(isset($map['extraFieldName']) && isset($data['extra']) && count($data['extra'])) {
			$out[$map['extraFieldName']] = json_encode($data['extra']);
			if($map['extraFieldName'] !== 'extra') {
				unset($data['extra']);
			}
		}
		
		return $out;
	}
	
	/**
	 * Set the column mappings
	 * @param array $spec
	 * @return Db $this
	 */
	public function setColumnMap(array $spec) {
		$this->columnMap = $spec;
		return $this;
	}
	
	/**
	 * Return column mapping array
	 * @return array|NULL
	 */
	public function getColumnMap() {
		return $this->columnMap;
	}
	
	/**
	 * Get the format specifier for DateTime objects
	 *
	 * @return string
	 */
	public function getDateTimeFormat() {
		return $this->dateFormat;
	}
	
	/**
	 * Set the format specifier for DateTime objects
	 *
	 * @see http://php.net/manual/en/function.date.php
	 * @param string $dateTimeFormat DateTime format
	 * @return FormatterInterface
	 */
	public function setDateTimeFormat($dateTimeFormat) {
		$this->dateFormat = (string) $dateTimeFormat;
		return $this;
	}
	
}