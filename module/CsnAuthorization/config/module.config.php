<?php
/**
 * Coolcsn Zend Framework 2 Authorization Module
 * 
*/

namespace CsnAuthorization;

return array(
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity',
                ),
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver',
                )
            )
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'acl' => function ($sm) {
                $config = $sm->get('config');
                if ($config['acl']['use_database_storage'])
                    return new \CsnAuthorization\Acl\AclDb($sm->get('doctrine.entitymanager.orm_default'));
                else
                    return new \CsnAuthorization\Acl\Acl($config);
            }
        ),
    ),
    'view_helpers' => array(
        'factories' => array(
            'isAllowed' => function($sm) {
              $sm = $sm->getServiceLocator(); // $sm was the view helper's locator
              $auth = $sm->get('AuthenticationService');
              $acl = $sm->get('acl');

              $helper = new \CsnAuthorization\View\Helper\IsAllowed($auth, $acl);
              return $helper;
            }
        ),
    ),
    'controller_plugins' => array(
        'factories' => array(
            'isAllowed' => function($sm) {
              $sm = $sm->getServiceLocator(); // $sm was the view helper's locator
              $auth = $sm->get('AuthenticationService');
              $acl = $sm->get('acl');

              $plugin = new \CsnAuthorization\Controller\Plugin\IsAllowed($auth, $acl);
              return $plugin;
            }
        ),
    ),
);