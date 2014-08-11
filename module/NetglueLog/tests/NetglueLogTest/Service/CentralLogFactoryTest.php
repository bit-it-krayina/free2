<?php

namespace NetglueLogTest\Service;

use NetglueLogTest\Bootstrap as BS;


class CentralLogFactoryTest extends \PHPUnit_Framework_TestCase {
	
	/**
	 * @var \Zend\ServiceManager\ServiceManager
	 */
	protected $serviceManager;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		$this->serviceManager = BS::getServiceManager();
	}
	
	public function testServiceManager() {
		$this->assertTrue(is_object($this->serviceManager));
	}
	
	public function testCentralLogAccess() {
		$logger = $this->serviceManager->get('CentralLog');
		$this->assertInstanceOf('Zend\Log\Logger', $logger);
	}
	
}