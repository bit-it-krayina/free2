<?php
/**
 * Coolcsn Zend Framework 2 Navigation Module
 *
 * @link https://github.com/coolcsn/CsnAclNavigation for the canonical source repository
 * @copyright Copyright (c) 2005-2013 LightSoft 2005 Ltd. Bulgaria
 * @license https://github.com/coolcsn/CsnAclNavigation/blob/master/LICENSE BSDLicense
 * @authors Stoyan Cheresharov <stoyan@coolcsn.com>, Anton Tonev <atonevbg@gmail.com>
*/

return array(
     'navigation' => array(
         'default' => array(
//             array(
//                 'label' => 'Home',
//                 'route' => 'home',
//				 'resource' => 'Application\Controller\Index',
//				 'privilege' => 'index',
//             ),
			 array(
                 'label' => 'Проекти',
                 'route' => 'project',
				 'action'     => 'list',
				 'resource'	  => 'Application\Controller\Projects',
				 'privilege'  => 'index',
             ),
			 array(
				'label' => 'Volounteers',
				'uri'	=> '#',
				'route' => 'user-admin',
				'action' => 'index',
				'resource' => 'CsnUser\Controller\Admin',
				'privilege' => 'index',
				'class' => '',
				'pages' => array(
					array (
						'label' => 'Список',
						'route' => 'user-admin',
						'action' => 'index',
						'resource' => 'CsnUser\Controller\Admin',
						'privilege' => 'index',
					),
					array (
						'label' => 'Добавити',
						'route' => 'user-admin',
						'resource' => 'CsnUser\Controller\Admin',
						'privilege' => 'index',
						'action' => 'create-user',
					),
				 ),
			 ),
			 array(
				'label' => '<span class="fi-search"></span>',
				'uri'	=> '#',
				'pages' => array(
					array (
						'label' => 'Скоро тут буде гарна форма пошуку',
						'uri'	=> '#',
					),
				),

             ),
			 array(
				'label' => '<span class="fi-bell-alt"></span><span class="badge badge-notif">2</span>',
				'uri'	=> '#',
				'pages' => array(
					array (
						'label' => 'Запрошення до "Ua roads"',
						'uri'	=> '#',
					),
					array (
						'label' => 'Новий відгук від "Ua elections"',
						'uri'	=> '#',
					),
				),

             ),
			 array(
				'label' => '<span class="fi-user"></span>',
				'uri'	=> '#',
//				'route' => 'home',
//				'controller' => 'Registration',
//				'action'     => 'edit-profile',
//				'resource'	  => 'CsnUser\Controller\Registration',
//				'privilege'  => 'edit-profile',
				'pages' => array(
					array (
						'label' => 'Логін',
						'route' => 'user-index',
						'controller' => 'Index',
						'action'     => 'login',
						'resource'	  => 'CsnUser\Controller\Index',
						'privilege'  => 'login',
					),
					array (
						'label' => 'Профіль',
						'route' => 'user-index',
						'controller' => 'Index',
						'action'     => 'profile',
						'resource'	  => 'CsnUser\Controller\Index',
						'privilege'  => 'profile',
					),
					array (
						'label' => 'Налаштування',
						'route' => 'user-register',
						'controller' => 'Registration',
						'action'     => 'edit-profile',
						'resource'	  => 'CsnUser\Controller\Registration',
						'privilege'  => 'edit-profile',
					),

					array (
						'label' => 'Вихід',
						'route' => 'user-index',
						'controller' => 'Index',
						'action'     => 'logout',
						'resource'	  => 'CsnUser\Controller\Index',
						'privilege'  => 'logout'
					),
				),

             ),


		 ),
	 ),
     'service_manager' => array(
         'factories' => array(
             'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
         ),
     ),
);