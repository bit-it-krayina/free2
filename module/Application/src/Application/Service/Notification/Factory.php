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
		return new Service($em);
	}
}
