<?php

namespace Application\Service;

use Application\Form\Login;
use Zend\View\Model\ViewModel;

trait ControlUtils
{

	/**
	 *
	 * @var Login
	 */
	private $loginForm;

	/**
	 * @return \Zend\Authentication\AuthenticationServiceInterface
	 */
	protected function getAuthenticationService ()
	{
		if ( empty ( $this -> authService ) )
		{
			$this -> authService = $this -> getServiceLocator ()
					-> get ( 'Zend\Authentication\AuthenticationService' );
		}

		return $this -> authService;
	}

	public function onDispatch ( \Zend\Mvc\MvcEvent $e )
	{

		$this -> setLoginForm ( new Login () );

		$viewHelperManager = $this->getServiceLocator()->get('viewHelperManager');
		$viewHelperManager->get('navigation')->setAcl($e -> getApplication () ->getServiceManager()->get('acl'))->setRole($this -> getRole());

		/**
		 * Было бы хорошо конечно, но это ещё не совсем работает
		 */
		$evm = $e -> getApplication () -> getEventManager ();
		$evm -> attach ( \Zend\Mvc\MvcEvent::EVENT_RENDER, function (\Zend\Mvc\MvcEvent $event) {

					$view = $event -> getViewModel ();

					$view -> setVariables ( array (
						'user' => $this -> getAuthenticationService () -> getIdentity (),
						'someVar' => 'yoyoyo',
						'loginForm' => $this -> getLoginForm (),
						'identity' => $this -> getAuthenticationService () -> getIdentity (),
						'loggedUser' => $this -> getLoggedUser (),
					) );
				} );

		return parent::onDispatch ( $e );
	}

	protected function createViewModel ( $template, $variables = [ ] )
	{
		$view = new ViewModel ();
		$view -> setTemplate ( $template );
		$view -> setTerminal ( true );


		if ( !empty ( $variables ) )
		{
			$view -> setVariables ( $variables );
		}

		return $view;
	}

	/**
	 * OLD
	 */
	public function setLoginForm ( $loginForm )
	{
		$this -> loginForm = $loginForm;
	}

	public function getLoginForm ()
	{
		return $this -> loginForm;
	}

	public function getLoggedUser ()
	{
		if ( $this -> getAuthenticationService () -> hasIdentity () )
		{
			return $this -> getAuthenticationService () -> getIdentity ();
		}

		return ['username' => 'Guest'];
	}


	protected function getRole()
	{
		if ($this->identity())
			return $this->identity()->getRole()->getName();
		else
			return 'guest';
	}
}