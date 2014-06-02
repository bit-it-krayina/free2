<?php

namespace Application;

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
return array (
	'router' => array (
		'routes' => array (
			'static' => array (
				'type' => 'Zend\Mvc\Router\Http\Segment',
				'options' => array (
					'route' => '/static/:action',
					'constraints' => array (
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					),
					'defaults' => array (
						'controller' => 'Application\Controller\Index',
						'action' => 'index',
					),
				),
			),
			'home' => array (
				'type' => 'Zend\Mvc\Router\Http\Literal',
				'options' => array (
					'route' => '/',
					'defaults' => array (
						'controller' => 'Application\Controller\Index',
						'action' => 'index',
					),
				),
			),


			'project' => array (
				'type' => 'Segment',
				'options' => array (
					'route' => '/project/[:action]',
					'constraints' => array (
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					),
					'defaults' => array (
						'controller' => 'Application\Controller\Projects',
						'action' => 'index',
					),
				),
			),


			// The following is a route to simplify getting started creating
			// new controllers and actions without needing to create a new
			// module. Simply drop new controllers in, and you can access them
			// using the path /application/:controller/:action
			'application' => array (
				'type' => 'Literal',
				'options' => array (
					'route' => '/application',
					'defaults' => array (
						'__NAMESPACE__' => 'Application\Controller',
						'controller' => 'Index',
						'action' => 'index',
					),
				),
				'may_terminate' => true,
				'child_routes' => array (
					'default' => array (
						'type' => 'Segment',
						'options' => array (
							'route' => '/[:controller[/:action]]',
							'constraints' => array (
								'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
								'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array (
							),
						),
					),
				),
			),
		),
	),
	'service_manager' => array (
		'abstract_factories' => array (
			'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
			'Zend\Log\LoggerAbstractServiceFactory',
		),
		'factories' => array (
			'mailer' => 'Application\Service\EmailSenderFactory',
			'Zend\Authentication\AuthenticationService' => 'Application\Service\AuthServiceFactory'
		),
		'aliases' => array (
			'translator' => 'MvcTranslator',
		),
		'invokables' => array(
			'importer' => 'Application\Service\Importer\Service',
		),
	),
	'translator' => array (
		'locale' => 'en_US',
		'translation_file_patterns' => array (
			array (
				'type' => 'gettext',
				'base_dir' => __DIR__ . '/../language',
				'pattern' => '%s.mo',
			),
		),
	),
	'controllers' => array (
		'invokables' => array (
			'Application\Controller\Index' => 'Application\Controller\IndexController',
			'Application\Controller\Projects' => 'Application\Controller\ProjectsController',
			'Application\Controller\Auth' => 'Application\Controller\AuthController',
			'Application\Controller\Ajax' => 'Application\Controller\AjaxController',
			'Application\Controller\Teachers' => 'Application\Controller\TeachersController'
		),
	),

	'view_manager' => array (
		'display_not_found_reason' => true,
		'display_exceptions' => true,
		'strategies' => array (
			'ZfcTwigViewStrategy',
		),
		'doctype' => 'HTML5',
		'not_found_template' => 'error/404',
		'exception_template' => 'error/index',
		'template_map' => array (
//			'csn-user/index/login' =>__DIR__ . '/../view/csn-user/index/login.twig',
//			'csn-user/layout/nav-menu' =>__DIR__ . '/../view/csn-user/layout/nav-menu.twig',
			'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
			'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
			'error/404' => __DIR__ . '/../view/error/404.phtml',
			'error/index' => __DIR__ . '/../view/error/index',
		),
		'template_path_stack' => array (
			__DIR__ . '/../view',
		),
	),
	// Placeholder for console routes
	'console' => array (
		'router' => array (
			'routes' => array (
			),
		),
	),
	// Doctrine config
	'doctrine' => array (
		'driver' => array (
			'orm_driver' => array (
				'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
				'cache' => 'array',
				'paths' => array ( __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity' )
			),
			'orm_default' => array (
				'drivers' => array (
					__NAMESPACE__ . '\Entity' => 'orm_driver'
				)
			),
		),
		'authentication' => array (
			'orm_default' => array (
				'object_manager' => 'Doctrine\ORM\EntityManager',
				'identity_class' => 'Application\Entity\User',
				'identity_property' => 'username',
				'credential_property' => 'password',
				'credential_callable' => function(Entity\User $user, $passwordGiven)
				{
					return $user -> getPassword () == md5 ( $passwordGiven );
				},
			),
		),
	),
	'contacts' => [
		'phones' => [
			'mts' => '(050) 056-58-48',
			'life' => '(063) 552-62-53',
		],
		'address' => 'some address',
		'email' => 'email',
		'name' => 'Ярослава Солтівская',
		'skype' => 'skype',
	],

	'projectsApi' => [
		'url' => 'http://ideas.it-krayina.org.ua/'
	],
	'facebookAuth' => [
		'apiId' => '760427617322200',
		'secret' => '0c9967f76181a9573a06043339bfd0da',
	],
);
