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
		$view->log = $record;
		$view->byRequest = array();
		$view -> title = 'Log Record: '.$record->getId();
		$date = $record->getDateTime();
		$view -> byDay = array(
			'year' => $date->format("Y"),
			'month' => $date->format("n"),
			'day' => $date->format("j"),
		);


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

		$title = sprintf('Logs for %s', $date->format("l jS F Y"));

		$y = clone($date);
		$y->sub(new \DateInterval('P1D'));
		$yesterday =  array(
			'year' => $y->format("Y"),
			'month' => $y->format("n"),
			'day' => $y->format("j"),
		);

		$t = clone($date);
		$t->add(new \DateInterval('P1D'));
		$tomorrow = array(
			'year' => $t->format("Y"),
			'month' => $t->format("n"),
			'day' => $t->format("j"),
		);


		$p1w = clone($date);
		$p1w->add(new\DateInterval('P7D'));
		$nextWeek = array(
			'year' => $p1w->format("Y"),
			'month' => $p1w->format("n"),
			'day' => $p1w->format("j"),
		);

		$s1w = clone($date);
		$s1w->sub(new\DateInterval('P7D'));
		$lastWeek = array(
			'year' => $s1w->format("Y"),
			'month' => $s1w->format("n"),
			'day' => $s1w->format("j"),
		);

		return new ViewModel(array(
			'date' => $date,
			'records' => $rs,
			'title' => $title,
			'yesterday' => $yesterday,
			'tomorrow' => $tomorrow,
			'nextWeek' => $nextWeek,
			'lastWeek' => $lastWeek,

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