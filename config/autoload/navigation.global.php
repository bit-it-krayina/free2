<?php
/**
 * Coolcsn Zend Framework 2 Navigation Module
 *
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
                 'label' => 'Logs',
                 'route' => 'netglue_log',
				 'action'     => 'index',
				 'resource'	  => 'NetglueLog\Controller\LogController',
				 'privilege'  => 'index',
             ),
			 array(
                 'label' => 'Projects',
                 'route' => 'project',
				 'action'     => 'list',
				 'resource'	  => 'Application\Controller\Projects',
				 'privilege'  => 'index',
             ),
			 array(
				'label' => 'Volunteers',
				'uri'	=> '#',
				'route' => 'user-admin',
				'action' => 'index',
				'resource' => 'Application\Controller\Admin',
				'privilege' => 'index',
				'class' => '',
				'pages' => array(
					array (
						'label' => 'List',
						'route' => 'user-admin',
						'action' => 'index',
						'resource' => 'Application\Controller\Admin',
						'privilege' => 'index',
					),
					array (
						'label' => 'Add',
						'route' => 'user-admin',
						'resource' => 'Application\Controller\Admin',
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
				'label' => '<span class="fi-bell-alt"></span><span class="badge badge-notif"></span>',
				'uri'	=> '#',
				'route' => 'index',
				'action' => 'notifications',
				'resource' => 'Application\Controller\Index',
				'privilege' => 'notifications',
//				'pages' => array(
//					array (
//						'label' => 'Notification',
//						'route' => 'user-index',
//						'action' => 'notifications',
//						'resource' => 'CsnUser\Controller\Index',
//						'privilege' => 'notifications',
//					),
//					array (
//						'label' => 'Запрошення до "Ua roads"',
//						'uri'	=> '#',
//					),
//					array (
//						'label' => 'Новий відгук від "Ua elections"',
//						'uri'	=> '#',
//					),
//				),

             ),
			 array(
				'label' => '<span class="fi-user"></span>',
				'uri'	=> '#',
//				'route' => 'home',
//				'controller' => 'Registration',
//				'action'     => 'edit-profile',
//				'resource'	  => 'Application\Controller\Registration',
//				'privilege'  => 'edit-profile',
				'pages' => array(
					array (
						'label' => 'LOG IN',
						'route' => 'index',
						'controller' => 'Index',
						'action'     => 'login',
						'resource'	  => 'Application\Controller\Index',
						'privilege'  => 'login',
					),
					array (
						'label' => 'Registration',
						'route' => 'user-register',
						'controller' => 'Registration',
						'action'     => 'index',
						'resource'	  => 'Application\Controller\Registration',
						'privilege'  => 'index',
					),
					array (
						'label' => 'Profile',
						'route' => 'index',
						'controller' => 'Index',
						'action'     => 'profile',
						'resource'	  => 'Application\Controller\Index',
						'privilege'  => 'profile',
					),
					array (
						'label' => 'Settings',
						'route' => 'user-register',
						'controller' => 'Registration',
						'action'     => 'edit-profile',
						'resource'	  => 'Application\Controller\Registration',
						'privilege'  => 'edit-profile',
					),

					array (
						'label' => 'Logout',
						'route' => 'index',
						'controller' => 'Index',
						'action'     => 'logout',
						'resource'	  => 'Application\Controller\Index',
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