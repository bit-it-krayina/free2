<?php
/**
 * This factory exists primarily so that the logger can have it's own adapter instance separate from any other adapters in the application
 * The factory can be overriden in config by Zend's default factory in order to use a shared adapter
 * @author George Steel <george@net-glue.co.uk>
 * @copyright Copyright (c) 2013 Net Glue Ltd (http://netglue.co)
 * @license http://opensource.org/licenses/MIT
 * @package	NetglueLog
 * @subpackage NetglueLog\Service
 * @link https://bitbucket.org/netglue/zf2-log-module
 */

namespace NetglueLog\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\Adapter\Adapter;

class DbAdapterFactory implements FactoryInterface {
	
	/**
	 * Create Db Adapter from config
	 * @param ServiceLocatorInterface $serviceLocator
	 * @return Zend\Db\Adapter\AdapterInterface
	 * @throws \RuntimeException if no configuration is set
	 */
	public function createService(ServiceLocatorInterface $serviceLocator) {
		$config = $serviceLocator->get('Config');
		if(!isset($config['netglue_log']['db'])) {
			throw new \RuntimeException('No database configuration found for the NetglueLog module');
		}
		return new Adapter($config['netglue_log']['db']);
	}
	
}