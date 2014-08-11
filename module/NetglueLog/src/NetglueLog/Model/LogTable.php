<?php

namespace NetglueLog\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql;

use DateTime;
use DateInterval;

class LogTable {
	
	protected $gateway;
	
	/**
	 * Column Name config
	 * @var array|NULL
	 */
	protected $columnMap;
	
	/**
	 * Construct
	 * @param AbstractTableGateway $gateway
	 * @return void
	 */
	public function __construct(AbstractTableGateway $gateway, array $columnMap) {
		$this->gateway = $gateway;
		$this->columnMap = $columnMap;
	}
	
	/**
	 * Return the configured column name given a known key such as idFieldName
	 * @param string $prop
	 * @return string|NULL
	 */
	public function __get($prop) {
		if(isset($this->columnMap[$prop])) {
			return $this->columnMap[$prop];
		}
		return NULL;
	}
	
	public function select() {
		$sql = $this->gateway->getSql();
		return $sql->select();
	}
	
	/**
	 * Return all log records on the given day. Time is ignored
	 * @param DateTime $date
	 * @param int $limit There's no limit by default
	 * @return ResultSet
	 */
	public function findByDate(DateTime $date, $limit = NULL) {
		$date = clone $date;
		$date->setTime(0,0,0);
		$select = $this->select()->where(array(
			new Sql\Predicate\Between($this->timestampFieldName, $date->getTimestamp(), $date->add(new DateInterval("P1D"))->getTimestamp()),
		));
		$select->order("{$this->timestampFieldName} DESC");
		if(is_numeric($limit)) {
			$select->limit($limit);
		}
		return $this->gateway->selectWith($select);
	}
	
	/**
	 * Select a single log record by id
	 * @param int $id
	 * @return NetglueLog\Model\LogRecord|false
	 */
	public function findById($id) {
		if(empty($id) || !is_numeric($id)) {
			return false;
		}
		$id = (int) $id;
		$select = $this->select()->where(array('id' => $id));
		$select->limit(1);
		$rs = $this->gateway->selectWith($select);
		if($rs->count()) {
			return $rs->current();
		}
		return false;
	}
	
	/**
	 * Find all log records with the matching request id
	 * @param string $id
	 * @return ResultSet
	 */
	public function findByRequestId($id) {
		$id = (string) $id;
		$select = $this->select()->where(array(
			$this->requestIdFieldName => $id,
		));
		$select->order("{$this->timestampFieldName} DESC");
		return $this->gateway->selectWith($select);
	}
	
	/**
	 * Delete records that are older that the given number of days
	 * @param int $maxDays
	 * @return ?
	 */
	public function deleteOlderThanDays($days) {
		$days = (int) $days;
		if(empty($days)) {
			return; //??
		}
		$date = new DateTime;
		$date->sub(new DateInterval("P{$days}D"));
		$time = $date->getTimestamp();
		$affectedRows = $this->gateway->delete(array(
			new Sql\Predicate\Operator($this->timestampFieldName, '<=', $time),
		));
		if($affectedRows > 0) {
			// Perhaps an oportunity to vacuum if SQLite
		}
		return $affectedRows;
	}
	
	public function fetchAll() {
		$s = $this->gateway->select();
		return $s;
	}
}