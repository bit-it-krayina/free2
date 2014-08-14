<?php

/**
 * CsnUser - Coolcsn Zend Framework 2 User Module
 *
 * @link https://github.com/coolcsn/CsnUser for the canonical source repository
 * @copyright Copyright (c) 2005-2013 LightSoft 2005 Ltd. Bulgaria
 * @license https://github.com/coolcsn/CsnUser/blob/master/LICENSE BSDLicense
 * @author Stoyan Cheresharov <stoyan@coolcsn.com>
 * @author Svetoslav Chonkov <svetoslav.chonkov@gmail.com>
 * @author Nikola Vasilev <niko7vasilev@gmail.com>
 * @author Stoyan Revov <st.revov@gmail.com>
 * @author Martin Briglia <martin@mgscreativa.com>
 */
return array (
	'controllers' => array (
		'invokables' => array (

			'Application\Controller\Admin' => 'Application\Controller\AdminController',
		),
	),
	'router' => array (
		'routes' => array (
			'user-admin' => array (
				'type' => 'Segment',
				'options' => array (
					'route' => '/admin[/:action][/:id][/:state]',
					'constraints' => array (
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						'state' => '[0-9]',
					),
					'defaults' => array (
						'controller' => 'Application\Controller\Admin',
						'action' => 'index',
					),
				),
				'may_terminate' => true,
			),
		),
	),
//	'doctrine' => array (
//		'configuration' => array (
//			'orm_default' => array (
//				'generate_proxies' => true,
//			),
//		),
//		'authentication' => array (
//			'orm_default' => array (
//				'object_manager' => 'Doctrine\ORM\EntityManager',
//				'identity_class' => 'Application\Entity\User',
//				'identity_property' => 'username',
//				'credential_property' => 'password',
//				'credential_callable' => 'Application\Service\UserService::verifyHashedPassword',
//			),
//		),
//		'driver' => array (
//			'csnuser_driver' => array (
//				'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
//				'cache' => 'array',
//				'paths' => array (
//					__DIR__ . '/../src/CsnUser/Entity',
//				),
//			),
//			'orm_default' => array (
//				'drivers' => array (
//					'Application\Entity' => 'csnuser_driver',
//				),
//			),
//		),
//	),
	'view_helper_config' => array (
		'flashmessenger' => array (
			'message_open_format' => '<div%s><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><ul><li>',
			'message_close_string' => '</li></ul></div>',
			'message_separator_string' => '</li><li>'
		)
	)
);
