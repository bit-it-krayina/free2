<?php
/**
 * Routes provided for viewing and managing log records
 * 
 * The module does not use Zend\ModuleManager\Feature\RouteProviderInterface
 * because that would make it difficult to override route config wouldn't it?
 */

$routes = array(
	'type' => 'Literal',
	'options' => array(
		'route' => '/logs',
		'defaults' => array(
			'__NAMESPACE__' => 'NetglueLog\Controller',
			'controller' => 'LogController',
			'action' => 'index',
		),
	),
	'may_terminate' => true,
	'child_routes' => array(
		
		'by-day' => array(
			'type' => 'Segment',
			'options' => array(
				'route' => '/by-day[/:year[/:month[/:day]]]',
				'constraints' => array(
					'year' => '[0-9]{4}',
					'month' => '[0-9]{1,2}',
					'day' => '[0-9]{1,2}',
				),
				'defaults' => array(
					'action' => 'by-day',
					'year' => date("Y"),
					'month' => date("n"),
					'day' => date("j"),
				),
			),
		),
		'view' => array(
			'type' => 'Segment',
			'options' => array(
				'route' => '/view[/:id]',
				'constraints' => array(
					'id' => '[0-9]+',
				),
				'defaults' => array(
					'action' => 'view',
					'id' => NULL,
				),
			),
		),
		'by-request' => array(
			'type' => 'Segment',
			'options' => array(
				'route' => '/by-request[/:request]',
				'constraints' => array(
					'request' => '[a-zA-Z0-9]+',
				),
				'defaults' => array(
					'action' => 'by-request',
					'year' => date("Y"),
					'month' => date("n"),
					'day' => date("j"),
				),
			),
		),
		'delete-aged' => array(
			'type' => 'Segment',
			'options' => array(
				'route' => '/delete-aged[/:days]',
				'constraints' => array(
					'days' => '[0-9]+',
				),
				'defaults' => array(
					'action' => 'delete-aged',
					'days' => NULL,
				),
			),
		),
		'empty' => array(
			'type' => 'Literal',
			'options' => array(
				'route' => '/empty',
				'defaults' => array(
					'action' => 'empty',
				),
			),
		),
		
	), // fi 'child_routes'
);

return array('netglue_log' => $routes);