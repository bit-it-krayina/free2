<?php
/**
 * CsnUser - Coolcsn Zend Framework 2 User Module
 */

namespace Application\Service\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Options\ModuleOptions;

class ModuleOptionsFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        return new ModuleOptions(isset($config['csnuser']) ? $config['csnuser'] : array());
    }
}
