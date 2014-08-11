<?php

namespace NetglueLog\Log\Writer;

use Zend\Log\Writer\AbstractWriter;

use Zend\Db\Adapter\Adapter;

class Db extends AbstractWriter {
	
	protected $db;
	
	protected $tableName;
	
	public function setAdapter(Adapter $db) {
		$this->db = $db;
		return $this;
	}
	
	public function getAdapter() {
		return $this->db;
	}
	
	public function setTableName($name) {
		$this->tableName = (string) $name;
		return $this;
	}
	
	public function getTableName() {
		return $this->tableName;
	}
	
	
	protected function doWrite(array $event) {
		if(!$this->db instanceof Adapter) {
			throw new \RuntimeException('No database adapter has been provided to the Netglue DB Log Writer');
		}
		if(NULL === $this->getTableName()) {
			throw new \RuntimeException('No table name has been set in the DB Log Writer');
		}
		// Flatten the event data into an array
		$event = $this->formatter->format($event);
		
		$db = $this->db;
		$keys = array_keys($event);
		$sql = 'INSERT INTO ' . $db->platform->quoteIdentifier($this->tableName) . ' (' .
			implode(", ", array_map(array($db->platform, 'quoteIdentifier'), $keys)) . ') VALUES (' .
			implode(", ", array_map(array($db->driver, 'formatParameterName'), $keys)) . ')';
		$statement = $db->query($sql);
		$statement->execute($event);
	}
	
	public function shutdown() {
		$this->db = NULL;
	}
	
}