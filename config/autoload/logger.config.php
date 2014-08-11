<?php

return [];
//
///**
// * Base Configuration for the Net Glue Log Module
// * @author George Steel <george@net-glue.co.uk>
// * @copyright Copyright (c) 2012 Net Glue Ltd (http://netglue.co)
// * @license http://opensource.org/licenses/MIT
// * @package	Netglue_LogModule
// * @link https://bitbucket.org/netglue/zf2-log-module
// */
//
///**
// * Default configuration options
// */
//$options = array(
//	// Enable Logging
//	'loggingEnabled' => true,
//	// Don't log exceptions by default
//	'logExceptions' => true,
//	// Don't log PHP errors by default
//	'logErrors' => true,
//	// Spec for the logger, just like Zend so you can add extra writers, filters etc...
//	'loggerSpec' => array(
//
//	),
//	// Max age in days to keep log records. Used when deleting old log records
//	'maxAgeInDays' => 30,
//
//	/**
//	 * Logging Table Configuration
//	 */
//	'tableName' => 'ng_log',
//	'columnMap' => array(
//		'idFieldName'             => 'id',              // Primary Key (int)
//		'timestampFieldName'      => 'timestamp',       // Unix timestamp (int)
//		'priorityNameFieldName'   => 'priority_name',   // String, corresponds to Zend\Log\Logger::$priorities
//		'priorityFieldName'       => 'priority',        // Int corresponds to Zend\Log\Logger priority contants
//		'messageFieldName'        => 'message',         // String, Log Message
//		'logTypeFieldName'        => 'type',            // String - Essentially an enum('APPLICATION', 'PHP_ERROR', 'EXCEPTION') - see: NetglueLog\Log\Processor\EventType
//		'errorCodeFieldName'      => 'error_code',      // Int|NULL: PHP or Exception Code
//		'exceptionClassFieldName' => 'exception_class', // String|NULL: exception class
//		'filePathFieldName'       => 'file_path',       // String|NULL: File in which error occurred
//		'lineFieldName'           => 'line',            // Int|NULL: Line on which error occured in file
//		'functionFieldName'       => 'called_function', // String|NULL: function name where exception thrown/error triggered
//		'traceFieldName'          => 'trace', 					// String|NULL: Json Encoded Backtrace
//		'ipAddressFieldName'      => 'ip_address',      // String|NULL: If possible, store remote address - see: NetglueLog\Log\Processor\RemoteAddress
//		'requestIdFieldName'      => 'request_id',      // String|NULL: Unique identifier for the request - see: Zend\Log\Processor\RequestId
//		'extraFieldName'          => 'extra',           // String|NULL: Json encoded information not handled by other fields such as user params
//	),
//
//	/**
//	 * Processors add additional information to log events
//	 * Each processor will be passed through the service locator so whatever you list here must return
//	 * something that implements Zend\Log\Processor\ProcessorInterface
//	 */
//	'processors' => array(
//		'NetglueLog\Log\Processor\EventType',
//		'Zend\Log\Processor\RequestId',
//		'Zend\Log\Processor\Backtrace',
//		'NetglueLog\Log\Processor\RemoteAddress',
//	),
//);
//
///**
// * Default Databse Driver Configuration
// *
// * SQLite database, specific to the logger
// * This can be any driver spec and is passed to the Zend factory in exactly the same way as normal
// */
//$dbDriver = array(
//	'driver' => 'Pdo_Mysql',
//	'database' => 'peep_db',
//	'hostname' => 'peep.mysql.ukraine.com.ua',
//	'port' => '3306',
//	'username' => 'peep_db',
//	'password' => 'MjErEhJB',
//	'charset' => 'utf8',
//);
//
//
///**
// * Router Config
// */
//$routes = include __DIR__.'/routes.config.php';
//
//return array(
//	'netglue_log' => array(
//		'options' => $options,
//		'db' => $dbDriver,
//	),
//	'router' => array(
//		'routes' => $routes,
//	),
//	'controllers' => array(
//		'factories' => array(
//			'NetglueLog\Controller\LogController' => 'NetglueLog\Service\LogControllerFactory',
//		),
//		'initializers' => array(
//			'NetglueLog\ControllerInitializer' => function($instance, $sm) {
//				$sl = $sm->getServiceLocator();
//				$log = new \NetglueLog\Service\CentralLogFactory;
//				$log->initialize($instance, $sl);
//			},
//		),
//	),
//	'view_manager' => array(
//		'template_path_stack' => array(
//			'netglue_log' => __DIR__ . '/../view',
//		),
//	),
//);
