<?php
/**
 * Service Configuration for the NetglueLog module
 */

return array(
	
	'initializers' => array(
		// The factory includes an initializer method to provide the central log to anything implementing the LoggerAwareInterface
		'NetglueLog\Service\CentralLogFactory' => 'NetglueLog\Service\CentralLogFactory',
	),
	
	'factories' => array(
		'NetglueLog\Service\CentralLogFactory' => 'NetglueLog\Service\CentralLogFactory',
		
		'NetglueLog\Service\DbAdapterFactory' => 'NetglueLog\Service\DbAdapterFactory',
		'NetglueLog\Log\Writer\Db' => 'NetglueLog\Service\DbWriterFactory',
		'NetglueLog\Log\Formatter\Db' => 'NetglueLog\Service\FormatterFactory',
		'NetglueLog\Service\Options' => 'NetglueLog\Service\OptionsFactory',
		
		'NetglueLog\LogTableGateway' => function($sm) {
			$adapter = $sm->get('NetglueLog\Service\DbAdapterFactory');
			$options = $sm->get('NetglueLog\Service\Options');
			$table = $options->getTableName();
			
			$logRecord = new \NetglueLog\Model\LogRecord;
			$logRecord->setColumnMap($options->getColumnMap());
			
			$resultSetPrototype = new \Zend\Db\ResultSet\ResultSet;
			$resultSetPrototype->setArrayObjectPrototype($logRecord);
			
			return new \Zend\Db\TableGateway\TableGateway($table, $adapter, NULL, $resultSetPrototype);
		},
		
		'NetglueLog\LogTable' => function($sm) {
			$options = $sm->get('NetglueLog\Service\Options');
			return new \NetglueLog\Model\LogTable($sm->get('NetglueLog\LogTableGateway'), $options->getColumnMap());
		}
		
	),
	
	'aliases' => array(
		'NetglueLog' => 'NetglueLog\Service\CentralLogFactory',
	),
	
);