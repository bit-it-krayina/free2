<?php
/**
 * Coolcsn Zend Framework 2 Authorization Module
 *
 * @link https://github.com/coolcsn/CsnAuthorization for the canonical source repository
 * @copyright Copyright (c) 2005-2013 LightSoft 2005 Ltd. Bulgaria
 * @license https://github.com/coolcsn/CsnAuthorization/blob/master/LICENSE BSDLicense
 * @author Stoyan Cheresharov <stoyan@coolcsn.com>, Stoyan Revov <st.revov@gmail.com>
*/

return array(
    'acl' => array(
        /**
         * By default the ACL is stored in this config file.
         * If you activate the database_storage ACL will be constructed from the database via Doctrine
         * and the roles and resources defined in this config wil be ignored.
         *
         * Defaults to false.
         */
        'use_database_storage' => false,
        /**
         * The route where users are redirected if access is denied.
         * Set to empty array to disable redirection.
         */
        'redirect_route' => array(
            'params' => array(
                //'controller' => 'my_controllet',
                //'action' => 'my_action',
                //'id' => '1',
            ),
            'options' => array(
				// We should redirect to an action Controller accessable for everyone. And this is "home" route
				// There should be a rule in the Acl allowing every role access to the action and controller
				// Usually this is the homepage action in our case CsnCms\Controller\Index action frontPageAction
				// the route 'home' = '/' should be overriden by CsnCms
				// In the case we are using login we enter an endless redirect. If you are loged in in the system as a volunteer
				// to hide from the navigation the login action the coleagues are using Acl to deny access to login.
				// The CsnAuthorisation trys to redirect to not accessable action loginAction and it gets redirected back to it.
				// Much better is to redirect to an action for sure accessable from everyone and there is no better candidate than the homepage
				// the landing page for the requests to the domain.
                'name' => 'home', // 'login',
            ),
        ),
        /**
         * Access Control List
         * -------------------
         */
        'roles' => array(
            'guest'   => null,
            'volunteer'  => 'guest',
            'coordinator'  => 'volunteer',
            'project manager'  => 'coordinator',
            'admin'  => 'project manager',
        ),
        'resources' => array(
            'allow' => array(
				'CsnUser\Controller\Registration' => array(
					'index'	=> 'guest',
					'change-password' => 'volunteer',
					'edit-profile' => 'volunteer',
					'change-email' => 'volunteer',
					'reset-password' => 'guest',
					'confirm-email' => 'guest',
					'registration-success' => 'guest',
				),
				'CsnUser\Controller\Index' => array(
					'login'   => 'guest',
					'logout'  => 'volunteer',
					'index' => 'guest',
				),
				'CsnUser\Controller\Admin' => array(
					'all' => 'admin',
				),
				'CsnCms\Controller\Index' => array(
						'all' => 'guest'
				),
				'CsnCms\Controller\Article' => array(
					'view'	=> 'guest',
					'vote'  => 'volunteer',
					'index' => 'admin',
					'add'	=> 'admin',
					'edit'  => 'admin',
					'delete'=> 'admin',
				),
				'CsnCms\Controller\Translation' => array(
					'index' => 'admin',
					'add'	=> 'admin',
					'edit'  => 'admin',
					'delete'=> 'admin',
				),
				'CsnCms\Controller\Comment' => array(
					'index' => 'volunteer',
					'add'	=> 'volunteer',
					'edit'  => 'volunteer',
					'delete'=> 'volunteer',
				),
				'CsnCms\Controller\Category' => array(
					'index' => 'admin',
					'add'	=> 'admin',
					'edit'  => 'admin',
					'delete'=> 'admin',
				),
				'CsnFileManager\Controller\Index' => array(
					'all' => 'volunteer',
				),
				'Zend' => array(
					'uri'   => 'volunteer'
				),
				'Application\Controller\Index' => array(
					'index'   => 'guest',
					'home'   => 'guest',
					'all' => 'guest',
				),
				'Application\Controller\Projects' => array(
					'all'   => 'guest',
				),
				// for CMS articles
                'all' => array(
					'view'	=> 'guest',
				),
				'Public Resource' => array(
					'view'	=> 'guest',
				),
				'Private Resource' => array(
					'view'	=> 'volunteer',
				),
				'admin Resource' => array(
					'view'	=> 'admin',
				),
            ),
            'deny' => array(
				'CsnUser\Controller\Index' => array (
					'login' => 'volunteer',
				),
				'CsnUser\Controller\Registration' => array (
					'index' => 'volunteer',
				),
            )
        )
    )
);
