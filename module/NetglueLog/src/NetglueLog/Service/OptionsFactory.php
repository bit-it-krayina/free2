<?php
/**
 * Factory to return an options instance
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
use NetglueLog\Service\Options;

class OptionsFactory implements FactoryInterface {
	
	/**
	 * Create Options instance from config
	 * @param ServiceLocatorInterface $serviceLocator
	 * @return NetglueLog\Service\Options
	 */
	public function createService(ServiceLocatorInterface $serviceLocator) {
		$config = $serviceLocator->get('Config');
		$options = isset($config['netglue_log']['options']) ?
			$config['netglue_log']['options'] :
			array();
		
		return new Options($options);
	}
	
}