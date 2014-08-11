<?php

namespace NetglueLog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Log\LoggerInterface;

use NetglueLog\Model\LogTable;

class LogController extends AbstractActionController {
	
	/**
	 * Log Table
	 * @var LogTable
	 */
	protected $table;
	
	/**
	 * Default Max Age in Days
	 * @var int
	 */
	protected $maxAgeInDays = 30;
	
	/**
	 * Log Dashboard...
	 * @return ViewModel
	 */
	public function indexAction() {
		//throw new \Exception('foo');
		//trigger_error('Blah', E_USER_WARNING);
		//$this->logger()->warn('I\'m a warning!');
		$view = new ViewModel;
		$view->maxDays = $this->getMaxAgeInDays();
		$rs = $this->table->findByDate(new \DateTime, 50);
		$rs->buffer();
		$view->records = $rs;
		return $view;
	}
	
	/**
	 * View a single log record in isolation
	 * @return ViewModel
	 */
	public function viewAction() {
		$params = $this->params()->fromRoute();
		$record = $this->table->findById($params['id']);
		if(false === $record) {
			$this->flashMessenger()->addErrorMessage(sprintf('No log record could be found with the id "%s"', $params['id']));
			return $this->redirect()->toRoute('netglue_log');
		}
		$view = new ViewModel;
		$view->record = $record;
		$view->byRequest = array();
		$req = $record->getRequestId();
		if(!empty($req)) {
			$view->byRequest = $rs = $this->table->findByRequestId($req);
			$rs->buffer();
		}
		
		return $view;
	}
	
	/**
	 * List all log records for the given day. Shows todays logs by default
	 * @return ViewModel
	 */
	public function byDayAction() {
		$params = $this->params()->fromRoute();
		$date = new \DateTime;
		$date->setDate($params['year'], $params['month'], $params['day']);
		$rs = $this->table->findByDate($date);
		$rs->buffer();
		return new ViewModel(array(
			'date' => $date,
			'records' => $rs,
		));
	}
	
	/**
	 * List log records for a particular request
	 * @return ViewModel
	 */
	public function byRequestAction() {
		$params = $this->params()->fromRoute();
		if(!isset($params['request']) || empty($params['request'])) {
			return $this->redirect()->toRoute('netglue_log/by-day');
		}
		$rs = $this->table->findByRequestId($params['request']);
		$rs->buffer();
		return new ViewModel(array(
			'request' => $params['request'],
			'records' => $rs,
		));
	}
	
	/**
	 * Truncate/Empty the log table
	 * @return void
	 */
	public function emptyAction() {
		
	}
	
	/**
	 * Delete log records older than the given number of days or older than the configured number of days
	 * @return void
	 */
	public function deleteAgedAction() {
		$params = $this->params()->fromRoute();
		$days = empty($params['days']) ? $this->getMaxAgeInDays() : (int) $params['days'];
		$affected = $this->table->deleteOlderThanDays($days);
		if($affected > 0) {
			$this->flashMessenger()->addSuccessMessage(sprintf(
				'Deleted %d log records more than %d days old',
				$affected,
				$days));
		} else {
			$this->flashMessenger()->addInfoMessage('No log records were old enough to be deleted');
		}
		return $this->redirect()->toRoute('netglue_log');
	}
	
	/**
	 * Set Log Table
	 * @param LogTable $table
	 * @return $this
	 */
	public function setLogTable(LogTable $table) {
		$this->table = $table;
		return $this;
	}
	
	/**
	 * Set the max age in days for keeping log records when we deleted aged records
	 * @param int $days
	 * @return LogController $this
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