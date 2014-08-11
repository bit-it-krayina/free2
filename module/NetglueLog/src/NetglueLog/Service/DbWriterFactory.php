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

class DbWriterFactory implements FactoryInterface {
	
	/**
	 * Create Db Writer from config
	 * @param ServiceLocatorInterface $serviceLocator
	 * @return Zend\Db\Adapter\AdapterInterface
	 * @throws \RuntimeException if no configuration is set
	 */
	public function createService(ServiceLocatorInterface $serviceLocator) {
		$options = $serviceLocator->get('NetglueLog\Service\Options');
		
		$formatter = $serviceLocator->get('NetglueLog\Log\Formatter\Db');
		$formatter->setColumnMap($options->getColumnMap());
		
		$writer = new \NetglueLog\Log\Writer\Db;
		$writer->setFormatter($formatter);
		$adapter = $serviceLocator->get('NetglueLog\Service\DbAdapterFactory');
		$writer->setAdapter($adapter);
		$writer->setTableName($options->getTableName());
		
		return $writer;
	}
	
}