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
use Zend\Form\Form;

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
		ini_set('display_errors', 1); error_reporting(E_ALL & ~E_NOTICE);
		
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
				'lastProjects' => $this -> getLastProjects(),
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

	public function testImageAction ()
	{
		if(!$user = $this->identity()) {
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }

//		$file = new \Zend\Form\Element\File('picture');
//		$send = new \Zend\Form\Element\Submit('submit');
//		$hid = new \Zend\Form\Element\Hidden('MAX_FILE_SIZE');
//		$hid->setValue(30000);
//
//		$send->setValue('Send');
//
//		$form = new Form('upload-file');
//		$form->setAttributes ( array (
//					'action' => '/test-image',
//					'name' => 'upload-file',
//					'enctype' => "multipart/form-data",
//				) );
//		$form->add($file)->add($send) -> add($hid);

		$form = $this->getUserFormHelper()->createUserForm($user, 'UploadForm');
		if ( $this -> getRequest () -> isPost () )
		{
			$post = array_merge_recursive (
				$this -> getRequest () -> getPost () -> toArray (),
				$this -> getRequest () -> getFiles () -> toArray ()
			);
			$form->setData($this -> getRequest () -> getPost ());
			print_r($post);
			if ( $form -> isValid () )
			{
				$data = $form -> getData ();

			}
		}

		return new ViewModel(array(
			'uploadForm' => $form,
        ));

	}

}
