<?php

namespace Application\Service;

use Zend\ServiceManager\FactoryInterface;
/**
 * Description of AuthServiceFactory
 *
 * @author pyatachok
 */
class AuthServiceFactory  implements FactoryInterface
{
	public function createService ( \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator )
	{
		return $serviceLocator -> get ( 'doctrine.authenticationservice.orm_default' );
	}	
}
