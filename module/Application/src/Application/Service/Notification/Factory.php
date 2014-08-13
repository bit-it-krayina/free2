<?php

namespace Application\Service\Notification;

use Application\Service\Notification\Service;
use Zend\ServiceManager\FactoryInterface;

/**
 * Description of Factory
 *
 * @author mice
 */
class Factory implements FactoryInterface
{
	public function createService ( \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator )
	{
		$em = $serviceLocator-> get ( 'Doctrine\ORM\EntityManager' );
		$service = new Service($em, $serviceLocator->get('eventManager'));
		$userInfoChecker = $serviceLocator->get('UserInfoChecker');
		$userInfoChecker->setEntityManager($em);
		$service->getEventManager()->attach(
			Service::EVENT_USER_INFO_UPDATE,
			array($userInfoChecker, 'onCheck')
		);

		return $service;
	}
}
