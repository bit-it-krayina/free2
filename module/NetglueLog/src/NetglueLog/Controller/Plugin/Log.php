<?php
/**
 * Controller Plugin for Logging
 */

namespace NetglueLog\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

use Zend\Log\LoggerAwareInterface;
use Zend\Log\LoggerInterface;

/**
 * Log controller plugin simply proxies to a Logger
 *
 * @see __call()
 * 
 * @method LoggerInterface emerg($message, array $extra = array())
 * @method LoggerInterface alert($message, array $extra = array())
 * @method LoggerInterface crit($message, array $extra = array())
 * @method LoggerInterface err($message, array $extra = array())
 * @method LoggerInterface warn($message, array $extra = array())
 * @method LoggerInterface notice($message, array $extra = array())
 * @method LoggerInterface info($message, array $extra = array())
 * @method LoggerInterface debug($message, array $extra = array())
 */
class Log extends AbstractPlugin implements LoggerAwareInterface {
	
	/**
	 * Logger
	 * @var LoggerInterface
	 */
	protected $logger;
	
	/**
	 * Proxy to the registered logger
	 * @param string $method
	 * @param array $args
	 * @return LoggerInterface
	 * @throws \RuntimeException if no logger has been provided
	 */
	public function __call($method, $args) {
		if(!$this->hasLogger()) {
			throw new \RuntimeException('No logger has been set');
		}
		return call_user_func_array(array($this->logger, $method), $args);
	}
	
	/**
	 * Set Logger
	 * @param LoggerInterface $logger
	 * @return LogController $this
	 */
	public function setLogger(LoggerInterface $logger) {
		$this->logger = $logger;
		return $this;
	}
	
	/**
	 * Return logger
	 * @return LoggerInterface|NULL
	 */
	public function getLogger() {
		return $this->logger;
	}
	
	/**
	 * Whether we've got a logger
	 * @return bool
	 */
	public function hasLogger() {
		return $this->logger instanceof LoggerInterface;
	}
	
}