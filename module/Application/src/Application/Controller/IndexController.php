<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\SessionManager;
use Zend\Session\Config\StandardConfig;

use Application\Service\EntityManagerAwareInterface;
use Application\Service\EntityManagerAwareTrait;
use Application\Service\MenuTrait;
use Application\Service\ControlUtils;


use CsnUser\Entity\User;
use CsnUser\Options\ModuleOptions;

class IndexController extends AbstractActionController implements EntityManagerAwareInterface
{

	use EntityManagerAwareTrait,
	 MenuTrait,
	 ControlUtils
			;


	public function indexAction ()
	{

		if ($user = $this->identity()) {
            $this->createViewModel('application/index/index');
        }

        $user = new User;
        $form = $this->getUserFormHelper()->createUserForm($user, 'login');
        $messages = null;
        if ($this->getRequest()->isPost()) {
            $form->setValidationGroup('usernameOrEmail', 'password', 'rememberme', 'csrf');
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $data = $form->getData();
                $authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
                $adapter = $authService->getAdapter();
                $usernameOrEmail = $this->params()->fromPost('usernameOrEmail');

                try {
                    $user = $this->getEntityManager()->createQuery("SELECT u FROM CsnUser\Entity\User u WHERE u.email = '$usernameOrEmail' OR u.username = '$usernameOrEmail'")->getResult(\Doctrine\ORM\Query::HYDRATE_OBJECT);
                    $user = $user[0];

                    if(!isset($user)) {
                        $message = 'The username or email is not valid!';
                        return $this->createViewModel('application/index/index', array(
                            'error' => $this->getTranslatorHelper()->translate('Your authentication credentials are not valid'),
                            'loginForm'	=> $form,
                            'messages' => $messages
                        ));
                    }

                    if($user->getState()->getId() < 2) {
                        $messages = $this->getTranslatorHelper()->translate('Your username is disabled. Please contact an administrator.');
                        return $this->createViewModel('application/index/index', array(
                            'error' => $this->getTranslatorHelper()->translate('Your authentication credentials are not valid'),
                            'loginForm'	=> $form,
                            'messages' => $messages
                        ));
                    }

                    $adapter->setIdentityValue($user->getUsername());
                    $adapter->setCredentialValue($this->params()->fromPost('password'));

                    $authResult = $authService->authenticate();
                    if ($authResult->isValid()) {
                        $identity = $authResult->getIdentity();
                        $authService->getStorage()->write($identity);

                        if ($this->params()->fromPost('rememberme')) {
                            $time = 1209600; // 14 days (1209600/3600 = 336 hours => 336/24 = 14 days)
                            $sessionManager = new SessionManager();
                            $sessionManager->rememberMe($time);
                        }

                        return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
                    }

                    foreach ($authResult->getMessages() as $message) {
                      $messages .= "$message\n";
                    }
                } catch (\Exception $e) {
                    return $this->getServiceLocator()->get('csnuser_error_view')->createErrorView(
                        $this->getTranslatorHelper()->translate('Something went wrong during login! Please, try again later.'),
                        $e,
                        $this->getOptions()->getDisplayExceptions(),
                        $this->getOptions()->getNavMenu()
                    );
                }
            }
        }

        return $this->createViewModel('application/index/index',
			array(
				'error' => $this->getTranslatorHelper()->translate('Your authentication credentials are not valid'),
				'loginForm'	=> $form,
				'messages' => $messages,
				'navMenu' => $this->getOptions()->getNavMenu()
			));

	}


	public function facebookAction()
	{
		
	}



	/**
	 * @deprecated since version number
	 */
	public function aboutAction ()
	{
//	$mailer = $this->getServiceLocator()->get('mailer');
		return new ViewModel();
	}

	/**
	 * @deprecated since version number
	 */
	public function contactsAction ()
	{
		return new ViewModel();
	}

	/**
	 * @deprecated since version number
	 */
	public function servicesAction ()
	{
		return new ViewModel();
	}

	public function thanksAction ()
	{
		$result = $this -> params () -> fromRoute ( 'result' );
		$orderId = $this -> params () -> fromRoute ( 'order_id' );
		$order = false;

		if ( !empty ( $orderId ) )
		{
			$order = $this
					-> getEntityManager ()
					-> getRepository ( 'Application\Entity\Order' )
					-> find ( $orderId );
//	error_log (
//		var_export ( array ( $order ), true )
//	);
		}

		return new ViewModel (
				array (
			'result' => $result,
			'order_id' => $orderId,
			'order' => $order,
		) );
	}

	/**
	 * @deprecated since version number
	 */
	public function profileAction ()
	{
		return new ViewModel();
	}

	/**
	 * @deprecated since version number
	 */
	public function userManagementAction ()
	{
		return new ViewModel();
	}
}
