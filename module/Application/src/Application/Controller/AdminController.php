<?php

/**
 * CsnUser - Coolcsn Zend Framework 2 User Module
 *
 */

namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\User;
use Application\Options\ModuleOptions;
use Application\Service\UserService as UserCredentialsService;
use Application\Service\ControlUtils;
/**
 * Admn controller
 */
class AdminController extends AbstractActionController
{
	use ControlUtils;


	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $entityManager;



	/**
	 * Index action
	 *
	 * Method to show an user list
	 *
	 * @return Zend\View\Model\ViewModel
	 */
	public function indexAction ()
	{
		if ( !$this -> identity ()  )
		{
			return $this -> redirect () -> toRoute ( $this -> getOptions () -> getLoginRedirectRoute () );
		}

		$users = $this -> getEntityManager () -> getRepository ( 'Application\Entity\User' ) -> findall ();
		return $this -> createViewModel ( 'application/admin/index', array (
			'users' => $users,
			'navMenu' => $this->getOptions()->getNavMenu()
			) );
	}

	/**
	 * Create action
	 *
	 * Method to create an user
	 *
	 * @return Zend\View\Model\ViewModel
	 */
	public function createUserAction ()
	{
		if ( !$this -> identity () )
		{
			return $this -> redirect () -> toRoute ( $this -> getOptions () -> getLoginRedirectRoute () );
		}

		try
		{
			$user = new User;

			$form = $this -> getUserFormHelper () -> createUserForm ( $user, 'CreateUser' );
			$request = $this -> getRequest ();
			if ( $request -> isPost () )
			{
				$form -> setValidationGroup ( 'username', 'email', 'firstName', 'lastName',
						'password', 'passwordVerify', 'state', 'role', 'question',
						'answer', 'csrf' , 'location', 'resume');
				$form -> setData ( $request -> getPost () );
				if ( $form -> isValid () )
				{
					$entityManager = $this -> getEntityManager ();
					$user -> setEmailConfirmed ( false );
					$user -> setRegistrationDate ( new \DateTime () );
					$user -> setRegistrationToken ( md5 ( uniqid ( mt_rand (), true ) ) );
					$user -> setPassword ( UserCredentialsService::encryptPassword ( $user -> getPassword () ) );
					$entityManager -> persist ( $user );
					$entityManager -> flush ();
					$this -> flashMessenger () -> addSuccessMessage ( $this -> getTranslatorHelper () -> translate ( 'User created Successfully' ) );
					return $this -> redirect () -> toRoute ( 'user-admin' );
				}
			}
		}
		catch ( \Exception $e )
		{
			return $this -> getServiceLocator () -> get ( 'csnuser_error_view' ) -> createErrorView (
							$this -> getTranslatorHelper () -> translate ( 'Something went wrong during user creation! Please, try again later.' ),
							$e, $this -> getOptions () -> getDisplayExceptions (), false
			);
		}


		return $this -> createViewModel ( 'application/admin/new-user-form', array (
			'form' => $form,
			'navMenu' => $this->getOptions()->getNavMenu()
			) );
	}

	/**
	 * Edit action
	 *
	 * Method to update an user
	 *
	 * @return Zend\View\Model\ViewModel
	 */
	public function editUserAction ()
	{
		if ( !$this -> identity () )
		{
			return $this -> redirect () -> toRoute ( $this -> getOptions () -> getLoginRedirectRoute () );
		}

		try
		{
			$id = ( int ) $this -> params () -> fromRoute ( 'id', 0 );

			if ( $id == 0 )
			{
				$this -> flashMessenger () -> addErrorMessage ( $this -> getTranslatorHelper () -> translate ( 'User ID invalid' ) );
				return $this -> redirect () -> toRoute ( 'user-admin' );
			}

			$entityManager = $this -> getEntityManager ();
			$user = $entityManager -> getRepository ( 'Application\Entity\User' ) -> find ( $id );

			$form = $this -> getUserFormHelper () -> createUserForm ( $user, 'EditUser' );

			$form -> setAttributes ( array (
				'action' => $this -> url () -> fromRoute ( 'user-admin',
						array ( 'action' => 'edit-user', 'id' => $id ) ),
			) );

			$request = $this -> getRequest ();
			if ( $request -> isPost () )
			{
				$form -> setValidationGroup ( 'username', 'email', 'firstName', 'lastName',
						'state', 'role', 'question', 'answer', 'csrf' , 'location', 'resume');
				$form -> setData ( $request -> getPost () );
				if ( $form -> isValid () )
				{
					$entityManager -> persist ( $user );
					$entityManager -> flush ();
					$this -> flashMessenger () -> addSuccessMessage ( $this -> getTranslatorHelper () -> translate ( 'User Updated Successfully' ) );
					return $this -> redirect () -> toRoute ( 'user-admin' );
				}
			}
		}
		catch ( \Exception $e )
		{
			return $this -> getServiceLocator () -> get ( 'csnuser_error_view' ) -> createErrorView (
							$this -> getTranslatorHelper () -> translate ( 'Something went wrong during update user process! Please, try again later.' ),
							$e, $this -> getOptions () -> getDisplayExceptions (), false
			);
		}

		return $this -> createViewModel ( 'application/admin/edit-user-form', array (
			'form' => $form,
			'navMenu' => $this->getOptions()->getNavMenu(),
			'headerLabel' => $this -> getTranslatorHelper () -> translate ( 'Edit User' ) . ' - ' . $user -> getUserName (),
			) );
	}

	/**
	 * Delete action
	 *
	 * Method to delete an user from his ID
	 *
	 * @return Zend\View\Model\ViewModel
	 */
	public function deleteUserAction ()
	{
		if ( !$this -> identity () )
		{
			return $this -> redirect () -> toRoute ( $this -> getOptions () -> getLoginRedirectRoute () );
		}

		$id = ( int ) $this -> params () -> fromRoute ( 'id', 0 );

		if ( $id == 0 )
		{
			$this -> flashMessenger () -> addErrorMessage ( $this -> getTranslatorHelper () -> translate ( 'User ID invalid' ) );
			return $this -> redirect () -> toRoute ( 'user-admin' );
		}

		try
		{
			$entityManager = $this -> getEntityManager ();
			$user = $entityManager -> getRepository ( 'Application\Entity\User' ) -> find ( $id );
			$entityManager -> remove ( $user );
			$entityManager -> flush ();
			$this -> flashMessenger () -> addSuccessMessage ( $this -> getTranslatorHelper () -> translate ( 'User Deleted Successfully' ) );
		}
		catch ( \Exception $e )
		{
			return $this -> getServiceLocator () -> get ( 'csnuser_error_view' ) -> createErrorView (
							$this -> getTranslatorHelper () -> translate ( 'Something went wrong during user delete process! Please, try again later.' ),
							$e, $this -> getOptions () -> getDisplayExceptions (), false
			);
		}

		return $this -> redirect () -> toRoute ( 'user-admin' );
	}

	/**
	 * Disable action
	 *
	 * Method to disable an user from his ID
	 *
	 * @return Zend\View\Model\ViewModel
	 */
	public function setUserStateAction ()
	{
		if ( !$this -> identity () )
		{
			return $this -> redirect () -> toRoute ( $this -> getOptions () -> getLoginRedirectRoute () );
		}

		$id = ( int ) $this -> params () -> fromRoute ( 'id', 0 );
		$state = ( int ) $this -> params () -> fromRoute ( 'state', -1 );

		if ( $id === 0 || $state === -1 )
		{
			$this -> flashMessenger () -> addErrorMessage ( $this -> getTranslatorHelper () -> translate ( 'User ID or state invalid' ) );
			return $this -> redirect () -> toRoute ( 'user-admin' );
		}

		try
		{
			$entityManager = $this -> getEntityManager ();
			$user = $entityManager -> getRepository ( 'Application\Entity\User' ) -> find ( $id );
			$user -> setState ( $entityManager -> find ( 'Application\Entity\State', $state ) );
			$entityManager -> persist ( $user );
			$entityManager -> flush ();
			$this -> flashMessenger () -> addSuccessMessage ( $this -> getTranslatorHelper () -> translate ( 'User Updated Successfully' ) );
		}
		catch ( \Exception $e )
		{
			return $this -> getServiceLocator () -> get ( 'csnuser_error_view' ) -> createErrorView (
							$this -> getTranslatorHelper () -> translate ( 'Something went wrong during user delete process! Please, try again later.' ),
							$e, $this -> getOptions () -> getDisplayExceptions (), false
			);
		}

		return $this -> redirect () -> toRoute ( 'user-admin' );
	}



	/**
	 * get entityManager
	 *
	 * @return EntityManager
	 */
	private function getEntityManager ()
	{
		if ( null === $this -> entityManager )
		{
			$this -> entityManager = $this -> getServiceLocator () -> get ( 'doctrine.entitymanager.orm_default' );
		}

		return $this -> entityManager;
	}



}