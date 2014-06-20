<?php

namespace Application\Service;

use Application\Form\Login;
use Zend\View\Model\ViewModel;

trait ControlUtils
{
    /**
     * @var ModuleOptions
     */
    protected $options;

	/**
     * @var Zend\Mvc\I18n\Translator
     */
    protected $translatorHelper;

    /**
     * @var Zend\Form\Form
     */
    protected $userFormHelper;

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

			$facebookAuth = $this->getServiceLocator()->get('config')['facebookAuth'];
			$view = $event -> getViewModel ();

			$view -> setVariables ( array (
				'user' => $this -> getAuthenticationService () -> getIdentity (),
//				'identity' => $this -> getAuthenticationService () -> getIdentity (),
				'facebookAuthUrl' => "https://www.facebook.com/dialog/oauth?client_id=".$facebookAuth['apiId']."&redirect_uri=".$this->url()->fromRoute('facebook', ['action' => 'login'], array('force_canonical' => true))
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


	 /**
     * get translatorHelper
     *
     * @return  Zend\Mvc\I18n\Translator
     */
    private function getTranslatorHelper()
    {
        if (null === $this->translatorHelper) {
           $this->translatorHelper = $this->getServiceLocator()->get('MvcTranslator');
        }

        return $this->translatorHelper;
    }

    /**
     * get userFormHelper
     *
     * @return  Zend\Form\Form
     */
    private function getUserFormHelper()
    {
        if (null === $this->userFormHelper) {
           $this->userFormHelper = $this->getServiceLocator()->get('csnuser_user_form');
        }

        return $this->userFormHelper;
    }


	/**
     * get options
     *
     * @return ModuleOptions
     */
    private function getOptions()
    {
        if (null === $this->options) {
            $this->options = $this->getServiceLocator()->get('csnuser_module_options');
        }

        return $this->options;
    }


	public function getLastProjects()
	{
		return $this
						-> getEntityManager ()
						-> getRepository ( 'Application\Entity\Project' )
						-> findBy( [], ['outerId' => 'DESC'], 3);
	}


}