<?php
/**
 * This factory injects dependencies into the default Neglue DB Log writer
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
use NetglueLog\Log\Formatter\Db as Formatter;

class FormatterFactory implements FactoryInterface {
	
	/**
	 * Create Db Writer from config
	 * @param ServiceLocatorInterface $serviceLocator
	 * @return Zend\Db\Adapter\AdapterInterface
	 * @throws \RuntimeException if no configuration is set
	 */
	public function createService(ServiceLocatorInterface $serviceLocator) {
		$options = $serviceLocator->get('NetglueLog\Service\Options');
		$formatter = new Formatter;
		$formatter->setColumnMap($options->getColumnMap());
		return $formatter;
	}
	
}