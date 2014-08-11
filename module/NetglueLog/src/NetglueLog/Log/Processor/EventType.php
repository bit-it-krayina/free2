<?php

namespace NetglueLog\Log\Processor;

use Zend\Log\Processor\ProcessorInterface;

class EventType implements ProcessorInterface {
	
	const EVENT_TYPE_APPLICATION = 'APPLICATION';
	const EVENT_TYPE_PHP_ERROR   = 'PHP_ERROR';
	const EVENT_TYPE_EXCEPTION   = 'EXCEPTION';
	
	/**
	 * Add additional information to the event
	 *
	 * Tries to figure out if the event is a normal logging event, an exception or a php error
	 * @param array $event
	 * @return array
	 */
	public function process(array $event) {
		/**
		 * Try to figure out if this is a normal logging event, a PHP error or an exception
		 */
		$type = self::EVENT_TYPE_APPLICATION;
		if(!isset($event['extra'])) {
			$event['extra'] = array('event_type' => $type);
			return $event;
		}
		
		/**
		 * PHP Error - look for event keys found in Zend\Log\Logger::registerErrorHandler
		 */
		$x = $event['extra'];
		if(isset($x['errno']) && isset($x['file']) && isset($x['line']) && isset($x['context'])) {
			$type = self::EVENT_TYPE_PHP_ERROR;
			unset($event['extra']['context']); // For debugging purposes - this is just way too big most of the time
		}
		
		/**
		 * Our exception handler attached to the MVC event in CentralLogFactory will provide the exception
		 */
		if(isset($x['exception']) && $x['exception'] instanceof \Exception) {
			$type = self::EVENT_TYPE_EXCEPTION;
		}
		
		// Likely an exception logged by ZendLogger exception handler
		// The Backtrace processor doesn't include the index 'trace'
		if(isset($x['file']) && isset($x['line']) && isset($x['trace'])) {
			$type = self::EVENT_TYPE_EXCEPTION;
		}
		
		$event['extra']['event_type'] = $type;
		return $event;
	}
	
}