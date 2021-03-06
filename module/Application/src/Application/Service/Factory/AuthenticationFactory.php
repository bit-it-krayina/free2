<?php
/**
 * CsnUser - Coolcsn Zend Framework 2 User Module
 */

namespace Application\Service\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthenticationFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $serviceLocator->get('doctrine.authenticationservice.orm_default');
    }
}
