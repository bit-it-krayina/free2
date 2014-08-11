<?php
/**
 * A Factory to create a log controller instance
 * @author George Steel <george@net-glue.co.uk>
 * @copyright Copyright (c) 2013 Net Glue Ltd (http://netglue.co)
 * @license http://opensource.org/licenses/MIT
 * @package	Netglue_ContactModule
 * @link https://bitbucket.org/netglue/zf2-log-module
 */

namespace NetglueLog\Service;

/**
 * To instantiate a controller
 */
use NetglueLog\Controller\LogController;

/**
 * To implement factory interface
 */
use Zend\ServiceManager\FactoryInterface;

/**
 * To accept Service Locator Objects
 */
use Zend\ServiceManager\ServiceLocatorInterface;


/**
 * A Factory to create a log controller instance
 * @author George Steel <george@net-glue.co.uk>
 * @copyright Copyright (c) 2013 Net Glue Ltd (http://netglue.co)
 * @license http://opensource.org/licenses/MIT
 * @package	Netglue_ContactModule
 * @link https://bitbucket.org/netglue/zf2-log-module
 */
class LogControllerFactory implements FactoryInterface {
	
	/**
	 * Create Service, Return a fresh log controller with everything required setup for use
	 * @return LogController
	 * @param ServiceLocatorInterface $services
	 */
	public function createService(ServiceLocatorInterface $services) {
		
		$serviceLocator = $services->getServiceLocator();
		$options = $serviceLocator->get('NetglueLog\Service\Options');
		
		$controller = new LogController;
		$controller->setLogTable($serviceLocator->get('NetglueLog\LogTable'));
		$controller->setMaxAgeInDays($options->getMaxAgeInDays());
		return $controller;
	}
	
	
}