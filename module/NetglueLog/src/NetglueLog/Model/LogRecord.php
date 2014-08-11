<?php

namespace NetglueLog\Model;
use DateTime;
use NetglueLog\Log\Processor\EventType;

class LogRecord {

	protected $data = array();

	protected $columnMap;

	protected $date;

	public function setColumnMap(array $map) {
		$this->columnMap = $map;
		return $this;
	}

	public function exchangeArray($data) {
		$this->data = $data;
	}

	public function __get($prop) {
		if(isset($this->columnMap[$prop])) {
			return $this->data[$this->columnMap[$prop]];
		}
		return NULL;
	}

	/**
	 * @return int|NULL
	 */
	public function getId() {
		$id = $this->idFieldName;
		if(empty($id)) {
			return NULL;
		}
		return (int) $id;
	}

	/**
	 * @return int|NULL
	 */
	public function getTimestamp() {
		$time = $this->timestampFieldName;
		if(empty($time)) {
			return NULL;
		}
		return (int) $time;
	}

	public function getDateTime() {
		if($this->date instanceof DateTime) {
			return $this->date;
		}
		if($time = $this->getTimestamp()) {
			$this->date = DateTime::createFromFormat('U', $time);
			return $this->date;
		}
		return NULL;
	}

	public function getPriorityName() {
		$name = $this->priorityNameFieldName;
		if(empty($name)) {
			return NULL;
		}
		return $name;
	}

	public function getPriority() {
		$priority = $this->priorityFieldName;
		if(!is_numeric($priority) && empty($priority)) {
			return NULL;
		}
		return (int) $priority;
	}

	public function getMessage() {
		$msg = $this->messageFieldName;
		return empty($msg) ? NULL : $this->messageFieldName;
	}

	public function getLogType() {
		$type = $this->logTypeFieldName;
		if(empty($type)) {
			return NULL;
		}
		return $type;
	}

	public function isPhpError() {
		return $this->getLogType() === EventType::EVENT_TYPE_PHP_ERROR;
	}

	public function isException() {
		return $this->getLogType() === EventType::EVENT_TYPE_EXCEPTION;
	}

	public function isGeneral() {
		return $this->getLogType() === EventType::EVENT_TYPE_APPLICATION;
	}

	public function getErrorCode() {
		$code = $this->errorCodeFieldName;
		if(!is_numeric($code) && empty($code)) {
			return NULL;
		}
		return (int) $code;
	}

	public function getExceptionClass() {
		$class = $this->exceptionClassFieldName;
		return empty($class) ? NULL : $class;
	}

	public function getFile() {
		$file = $this->filePathFieldName;
		return empty($file) ? NULL : $file;
	}

	public function getLine() {
		$line = $this->lineFieldName;
		return empty($line) ? NULL : (int) $line;
	}

	public function getFunction() {
		$func = $this->functionFieldName;
		return empty($func) ? NULL : $func;
	}

	public function getTrace() {
		$trace = $this->traceFieldName;
		return empty($trace) ? NULL : $trace;
	}

	public function getIpAddress() {
		$ip = $this->ipAddressFieldName;
		return empty($ip) ? NULL : $ip;
	}

	public function getRequestId() {
		$req = $this->requestIdFieldName;
		return empty($req) ? NULL : $req;
	}

	public function getExtra() {
		$extra = $this->extraFieldName;
		if(empty($extra)) {
			return NULL;
		}
		return json_decode($extra);
	}

	public function hasProject()
	{
		return isset ( $this->getExtra()->project_id );
	}

	public function getProject()
	{
		return $this->getExtra()->project_id ;
	}

}