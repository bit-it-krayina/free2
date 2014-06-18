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
        $registrationForm = $this->getUserFormHelper()->createUserForm($user, 'SignUp');
        $messages = null;
        return $this->createViewModel('application/index/index',
			array(
				'loginForm'	=> $form,
				'registrationForm'	=> $registrationForm,
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
