<?php

namespace NetglueLog\Service;

use Zend\StdLib\AbstractOptions;

class Options extends AbstractOptions {
	
	/**
	 * @var bool
	 */
	protected $logExceptions = false;
	
	/**
	 * @var bool
	 */
	protected $logErrors = false;
	
	/**
	 * @var bool
	 */
	protected $loggingEnabled = true;
	
	protected $loggerSpec;
	
	protected $tableName;
	
	protected $columnMap;
	
	protected $processors;
	
	protected $maxAgeInDays = 30;
	
	protected $validColumnMapKeys = array(
		'idFieldName',
		'timestampFieldName',
		'priorityNameFieldName',
		'priorityFieldName',
		'messageFieldName',
		'logTypeFieldName',
		'errorCodeFieldName',
		'exceptionClassFieldName',
		'filePathFieldName',
		'lineFieldName',
		'functionFieldName',
		'traceFieldName',
		'ipAddressFieldName',
		'requestIdFieldName',
		'extraFieldName',
	);
	
	
	
	/**
	 * Whether to log exceptions
	 * @param bool $flag
	 * @return Options
	 */
	public function setLogExceptions($flag) {
		$this->logExceptions = (bool) $flag;
		return $this;
	}
	
	/**
	 * Is Exception logging enabled
	 * @return bool
	 */
	public function getLogExceptions() {
		return $this->logExceptions;
	}
	
	/**
	 * Log PHP errors/warnings etc
	 * @param bool $flag
	 * @return Options
	 */
	public function setLogErrors($flag) {
		$this->logErrors = (bool) $flag;
		return $this;
	}
	
	/**
	 * Is Error logging enabled
	 * @return bool
	 */
	public function getLogErrors() {
		return $this->logErrors;
	}
	
	/**
	 * Whether to enable logging
	 * @param bool $flag
	 * @return Options
	 */
	public function setLoggingEnabled($flag) {
		$this->loggingEnabled = (bool) $flag;
		return $this;
	}
	
	/**
	 * Is logging enabled?
	 * @return bool
	 */
	public function getLoggingEnabled() {
		return $this->loggingEnabled;
	}
	
	/**
	 * Set the table name when logging to a db
	 * @param string $name
	 * @return Options
	 */
	public function setTableName($name) {
		$this->tableName = (string) $name;
		return $this;
	}
	
	/**
	 * Return table name configured
	 * @return string
	 */
	public function getTableName() {
		return $this->tableName;
	}
	
	public function setLoggerSpec($spec) {
		$this->loggerSpec = $spec;
		return $this;
	}
	
	public function getLoggerSpec() {
		return $this->loggerSpec;
	}

	public function setProcessors($spec) {
		$this->processors = $spec;
		return $this;
	}
	
	public function getProcessors() {
		return $this->processors;
	}
	
	public function setColumnMap($spec) {
		foreach($spec as $key => $value) {
			if(!in_array($key, $this->validColumnMapKeys, true)) {
				throw new \InvalidArgumentException("{$key} is not a valid column mapping key. Note that the keys are case sensitive");
			}
		}
		$this->columnMap = $spec;
		return $this;
	}
	
	public function getColumnMap() {
		return $this->columnMap;
	}
	
	/**
	 * Set the max age in days for keeping log records when we deleted aged records
	 * @param int $days
	 * @return Options $this
	 */
	public function setMaxAgeInDays($days) {
		$days = (int) $days;
		if(!empty($days)) {
			$this->maxAgeInDays = $days;
		}
		return $this;
	}
	
	/**
	 * Return max log record age in days
	 * @return int
	 */
	public function getMaxAgeInDays() {
		return $this->maxAgeInDays;
	}
	
}