<?php

namespace NetglueLog\Log\Processor;

use Zend\Log\Processor\ProcessorInterface;

class RemoteAddress implements ProcessorInterface {
	
	/**
	 * If we can figure it out, add the remote ip address to the log event array
	 * @param array $event
	 * @return array
	 */
	public function process(array $event) {
		$ip = NULL;
		if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'] != '') {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != '') {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		if(!isset($event['extra'])) {
			$event['extra'] = array();
		}
		$event['extra']['ip'] = $ip;
		return $event;
	}
	
}