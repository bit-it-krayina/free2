<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{

	public function onBootstrap ( MvcEvent $e )
	{
		$eventManager = $e -> getApplication () -> getEventManager ();
		$moduleRouteListener = new ModuleRouteListener();
		$moduleRouteListener -> attach ( $eventManager );

		$eventManager -> attach ( MvcEvent::EVENT_ROUTE, array ( $this, 'initLocale' ), 1 );

	}

	public function getConfig ()
	{
		return include __DIR__ . '/config/module.config.php';

	}

	public function getAutoloaderConfig ()
	{
		return array (
			'Zend\Loader\StandardAutoloader' => array (
				'namespaces' => array (
					__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
				),
			),
		);

	}

	public function initLocale ( MvcEvent $e )
	{
		//Получаем объект translator'a
		$translator = $e -> getApplication () -> getServiceManager () -> get ( 'translator' );
		$cookies = $e->getApplication()->getRequest()->getCookie();
		if (!empty($cookies['lang']))
		{
			$translator->setLocale($cookies['lang']); // ru_RU, en_US, etc...
		}

	}

}
